<?php

/**
 * Plugin Name: NW Company Profile
 * Description: ウェブサイト内で汎用的に利用する基本情報を管理できるプラグインです。
 * Version: 1.0.2
 * Author: NAKWEB
 * Author URI: https://www.nakweb.com/
 * License: GPLv2 or later
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
     exit;
}

register_activation_hook(__FILE__, array('NW_Company_Profile', 'myplugin_activation'));
register_uninstall_hook(__FILE__, array('NW_Company_Profile', 'myplugin_uninstall'));

class NW_Company_Profile
{
     /**
      * prefix
      */
     const PREFIX = 'nw_pf_';

     /**
      * ui version
      */
     const UI_VERSION = '1.0.0';

     /**
      * option_name in wp_options
      */
     const OPTION_NAME = 'nw_company_profile_options';

     /**
      * common option_name in wp_options
      */
     const COMMON_NAME = 'nw_common_options';

     /**
      * name hidden type
      */
     const HIDDEN_NAME = 'submit_hidden';

     /**
      * __construct
      */
     public function __construct()
     {
          add_action('plugins_loaded', array($this, 'plugins_loaded'));
     }

     /**
      * Loading translation files.
      */
     public function plugins_loaded()
     {
          if (!class_exists('NW_Common_Menu')) {
               // NW_Common_Menu class is containd in all plugin created by NAKWEB.
               require_once plugin_dir_path(__FILE__) . 'common/class.common.php';
          }
          require_once plugin_dir_path(__FILE__) . './inc/class.nwpf.php';

          add_action('admin_init', array($this, 'register_my_script'), 99);
          add_action('admin_menu', array($this, 'add_nw_sub_menu'), 99);
     }

     /**
      * Register original stylesheets and scripts.
      */
     public function register_my_script()
     {
          // stylesheet
          wp_register_style(self::PREFIX . 'fonts', 'https://use.fontawesome.com/releases/v5.6.1/css/all.css');
          wp_register_style(self::PREFIX . 'settings', plugin_dir_url(__FILE__) . 'css/settings.css', array(), self::UI_VERSION);
          // script
          wp_register_script(self::PREFIX . 'tabs', plugin_dir_url(__FILE__) . 'js/tabs.js', array(), self::UI_VERSION, true);
          wp_register_script(self::PREFIX . 'sortable', plugin_dir_url(__FILE__) . 'js/sortable.js', array(), self::UI_VERSION, true);
          wp_register_script(self::PREFIX . 'form', plugin_dir_url(__FILE__) . 'js/form.js', array(), self::UI_VERSION, true);
          wp_register_script(self::PREFIX . 'validation', plugin_dir_url(__FILE__) . 'js/validation.js', array(), self::UI_VERSION, true);
     }

     /**
      * Add sub menu page.
      */
     public function add_nw_sub_menu()
     {
          $parent_slug = NW_Common_Menu::SLUG;
          $capability = 'edit_pages';
          $add_page = add_submenu_page($parent_slug, '基本情報', '基本情報', $capability, 'nw_pf_settings', array($this, 'settings_page'));

          add_action('admin_print_styles-' . $add_page, array($this, 'enqueue_my_stylesheet'));
          add_action('admin_print_scripts-' . $add_page, array($this, 'enqueue_my_script'));
     }

     /**
      * Enqueue any stylesheets.
      */
     public function enqueue_my_stylesheet()
     {
          wp_enqueue_style(self::PREFIX . 'fonts');
          wp_enqueue_style(self::PREFIX . 'settings');
     }

     /**
      * Enqueue any scripts.
      */
     public function enqueue_my_script()
     {
          wp_enqueue_script('jquery-ui-tabs');
          wp_enqueue_script('jquery-ui-sortable');
          wp_enqueue_script(self::PREFIX . 'tabs');
          wp_enqueue_script(self::PREFIX . 'sortable');
          wp_enqueue_script(self::PREFIX . 'form');
          wp_enqueue_script(self::PREFIX . 'validation');
     }

     /**
      * Display settings page.
      */
     public function settings_page()
     {
          // Administrator and Editor can run.
          if (!current_user_can('edit_pages')) {
               wp_die(__('You do not have sufficient permissions to access this page.'));
          }

          // 外部ファイル読み込み
          require_once plugin_dir_path(__FILE__) . './inc/group.php';

          $profiles = self::genarate_array();

          if (isset($_POST[self::HIDDEN_NAME])) :

               check_admin_referer(self::PREFIX . 'field', self::PREFIX . 'field_nonce');

               switch ($_POST[self::HIDDEN_NAME]):
                    case 'edit_profiles':
                         self::edit_profiles($profiles);
                         break;
                    case 'create_group':
                         nwpf_create_group();
                         break;
                    case 'edit_group':
                         nwpf_edit_group();
                         break;
                    default:
                         break;
               endswitch;

          endif; ?>

               <div class="wrap">
                    <h1>基本情報</h1>
                    <div id="tabs">
                         <ul>
                              <li><a class="tab_btn" href="#tabs-edit-profiles">基本情報の追加・編集</a></li>
                              <li><a class="tab_btn" href="#tabs-create-group">グループの作成</a></li>
                              <li><a class="tab_btn" href="#tabs-edit-group">グループの編集</a></li>
                         </ul>

                         <div id="tabs-edit-profiles">
                              <form id="profile_form" name="profile_form" method="post" action="">
                                   <input type="hidden" name="<?php echo self::HIDDEN_NAME ?>" value="edit_profiles">

                                   <div class="profile_box">

                                        <div id="input_area">
                                             <dl class="profile_title">
                                                  <dt class="label_title">ラベル</dt>
                                                  <dd class="slug_title">ID</dd>
                                                  <dd class="contents_title">設定値</dd>
                                                  <dd class="delete_title"><input type="checkbox" class="all_check_btn">削除</dd>
                                             </dl>
                                             <div id="sortable">
                                                  <?php foreach ($profiles as $key => $value) : ?>
                                                       <dl class="company_profile">
                                                            <input type="hidden" name="<?php echo $key . '_slug' ?>" value="<?php echo $key ?>">
                                                            <dt class="label"><input type="text" name="<?php echo $key . '_label' ?>" value="<?php echo sanitize_text_field($value[0]) ?>"></dt>
                                                            <dd class="slug"><?php echo $key ?></dd>
                                                            <dd class="contents"><textarea name="<?php echo $key . '_contents' ?>" class="textarea_type"><?php echo stripslashes($value[1]) ?></textarea></dd>
                                                            <?php if (isset($value[2]) && $value[2] !== 'core') : ?>
                                                                 <div class="check_all_area"><input type="checkbox" name="<?php echo $key . '_delete' ?>" value="<?php echo $key ?>" class="delete_check_btn"><i class="far fa-trash-alt trash-icon"></i></div>
                                                            <?php else : ?>
                                                                 <div class="blank_area"></div>
                                                            <?php endif; ?>
                                                       </dl>
                                                  <?php endforeach; ?>
                                             </div>

                                             <div class="add_area">
                                                  <div class="btn_box">
                                                       <span class="add_form_btn"><input type="button" value="+"></span>
                                                       <span class="rm_form_btn"><input type="button" value="-"></span>
                                                  </div>
                                                  <dl class="company_profile draft">
                                                       <dt class="label"><input type="text" class="draft_label" name="add_request_label_" placeholder="必須" value=""></dt>
                                                       <dd class="slug"><input type="text" class="draft_slug input_control" name="add_request_slug_" placeholder="必須" value=""></dd>
                                                       <dd class="contents"><textarea class="draft_contents textarea_type" name="add_request_contents_"></textarea></dd>
                                                       <div class="blank_area"></div>
                                                  </dl>
                                             </div>

                                             <dl class="profile_title">
                                                  <dt class="label_title">ラベル</dt>
                                                  <dd class="slug_title">ID</dd>
                                                  <dd class="contents_title">設定値</dd>
                                                  <dd class="delete_title"><input type="checkbox" class="all_check_btn">削除</dd>
                                             </dl>
                                        </div>
                                   </div>
                                   <p class="submit"><input id="submit_btn" type="submit" name="submit" class="button-primary" value="変更を保存" /></p>
                                   <?php wp_nonce_field(self::PREFIX . 'field', self::PREFIX . 'field_nonce'); ?>
                              </form>
                         </div>

                         <div id="tabs-create-group">
                              <?php nwpf_echo_create_group_page(); ?>
                         </div>

                         <div id="tabs-edit-group">
                              <?php nwpf_echo_edit_group_page(); ?>
                         </div>
                    </div>
               </div>
     <?php
          }

          /**
           * Set profiles from db or default.
           *
           * @return array
           */
          public function genarate_array()
          {
               if (get_option(self::OPTION_NAME)) {
                    return get_option(self::OPTION_NAME);
               }

               return self::get_initial_array();
          }

          /**
           * Get initial array.
           *
           * @return array
           */
          public function get_initial_array()
          {
               $initial_array = array();
               $default_keys = self::get_default_keys();
               $core_keys = self::get_core_keys();

               foreach ($default_keys as $key) {
                    $initial_array[$key[0]] = array($key[1], '');
                    foreach ($core_keys as $core) {
                         if ($key[0] === $core) {
                              array_push($initial_array[$key[0]], 'core');
                              break;
                         }
                    }
               }
               return $initial_array;
          }

          /**
           * Declaration of default keys.
           *
           * @return array
           */
          public function get_default_keys()
          {
               return array(
                    array('name', '社名'),
                    array('en', '英語表記'),
                    array('zip', '郵便番号'),
                    array('pref', '都道府県'),
                    array('city', '市区町村'),
                    array('address1', '住所1'),
                    array('address2', '住所2'),
                    array('ceo', '代表者'),
                    array('found', '設立年月日'),
                    array('capital', '資本金'),
                    array('sales', '売上高'),
                    array('employees', '従業員数'),
                    array('tel', '電話番号'),
                    array('tel2', '代表電話番号'),
                    array('fax', 'FAX番号'),
                    array('business', '事業内容'),
                    array('open', '営業時間'),
                    array('time', '電話受付'),
                    array('holiday', '定休日'),
                    array('url', 'URL'),
               );
          }

          /**
           * Can not remove the core keys.
           *
           * @return array
           */
          public function get_core_keys()
          {
               return array(
                    'zip',
                    'pref',
                    'city',
                    'address1',
                    'address2',
               );
          }

          /**
           * Set default label if an item's label is empty.
           *
           * @param string $name
           * @return string
           */
          public function set_default_label($name)
          {
               $keys = self::get_default_keys();

               foreach ($keys as $key) {
                    if ($key[0] === $name) {
                         return $key[1];
                    }
               }
               return false;
          }

          /**
           * Update profiles changed, deleted, added or reset to default.
           *
           * @param array &$profiles
           */
          public function edit_profiles(&$profiles)
          {
               // Save order.
               $sorting_array = array();
               $core_keys = self::get_core_keys();
               foreach ($_POST as $key => $value) {
                    reset($profiles);
                    while (current($profiles)) {
                         if ($value === key($profiles)) {
                              $sorting_array[$value] = array($value);
                              foreach ($core_keys as $key) {
                                   if ($key === $value) {
                                        $sorting_array[$value][2] = 'core';
                                   }
                              }
                              break;
                         }
                         next($profiles);
                    }
               }

               // Update registerd items.
               foreach ($sorting_array as $key => &$value) {
                    if (isset($_POST[$key . '_delete']) && $_POST[$key . '_delete'] === $key) {
                         // Delete
                         unset($sorting_array[$key]);
                    } else {
                         // Update
                         if (empty($_POST[$key . '_label'])) {
                              if ($default_label = self::set_default_label($key)) {
                                   // Set default label.
                                   $value[0] = $default_label;
                              }
                         } else {
                              $value[0] = sanitize_text_field($_POST[$key . '_label']);
                         }
                         $value[1] = wp_kses_post($_POST[$key . '_contents']);
                         next($sorting_array);
                    }
               }
               unset($value);

               // Save added items to db.
               $i = 0;
               while (isset($_POST["add_request_slug_" . $i])) {
                    if (!empty($_POST["add_request_slug_" . $i]) && !empty($_POST["add_request_label_" . $i])) {
                         $key = sanitize_text_field($_POST["add_request_slug_" . $i]);
                         $sorting_array[$key][0] = sanitize_text_field($_POST["add_request_label_" . $i]);
                         $sorting_array[$key][1] = wp_kses_post($_POST["add_request_contents_" . $i]);
                    }
                    $i++;
               }

               // Update profiles.
               $profiles = $sorting_array;
               update_option(self::OPTION_NAME,  $profiles);

               echo '<div class="updated"><p><strong>' . __('設定を保存しました。') . '</strong></p></div>';
          }

          /**
           * Activation
           */
          public static function myplugin_activation()
          {
               self::activation_common();

               add_option(self::OPTION_NAME);
               add_option(self::OPTION_NAME . '_group');
          }

          /**
           * Uninstall
           */
          public static function myplugin_uninstall()
          {
               self::uninstall_common();

               delete_option(self::OPTION_NAME);
               delete_option(self::OPTION_NAME . '_group');
          }

          /**
           * Register nw-ish plugin activated at least once.
           */
          public function activation_common()
          {
               $myplugin_name = plugin_basename(__FILE__);
               $myplugin_name = explode('/', $myplugin_name)[0];
               $option_value = get_option(self::COMMON_NAME);
               if (!empty($option_value)) {
                    $flg = 0;
                    if (!empty($option_value['list'])) {
                         foreach ($option_value['list'] as $plugin) {
                              if ($plugin === $myplugin_name) {
                                   $flg = 1;
                                   break;
                              }
                         }
                    }
                    if (!$flg) {
                         array_push($option_value['list'], $myplugin_name);
                    }
               } else {
                    $option_value['label'] = '';
                    $option_value['list'] = array($myplugin_name);
               }
               update_option(self::COMMON_NAME, $option_value);
          }

          /**
           * Delete common option from wp_options.
           */
          public function uninstall_common()
          {
               // Get nw-ish plugins activated at least once.
               $nw_plugins = get_option(self::COMMON_NAME)['list'];
               if (empty($nw_plugins)) {
                    return;
               }

               // Get nw-ish plugins installed.
               $installed_plugins = scandir(WP_PLUGIN_DIR);
               $prefix = 'nw-';
               $flg = 0;
               if ($installed_plugins) {
                    foreach ($installed_plugins as $installed_plugin) {
                         if (!strncmp($installed_plugin, $prefix, 3)) {
                              foreach ($nw_plugins as $nw_plugin) {
                                   if ($installed_plugin === $nw_plugin) {
                                        if ($flg) {
                                             // Exist NW-ish plugins two or more.
                                             $flg++;
                                        } else {
                                             // Exist NW-ish plugin.
                                             $flg = 1;
                                        }
                                        break;
                                   }
                              }
                              if ($flg >= 2) {
                                   break;
                              }
                         }
                    }
               }
               if ($flg < 2) {
                    // Uninstall last nw-ish plugin.
                    $result = get_option(self::COMMON_NAME);
                    if ($result) {
                         delete_option(self::COMMON_NAME);
                         delete_option(self::OPTION_NAME . '_group');
                    }
               }
          }
     }
     new NW_Company_Profile();
