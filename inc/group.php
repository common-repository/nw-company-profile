<?php

/**
 * 管理画面：グループの作成
 */
function nwpf_echo_create_group_page()
{
     ?>
     <form id="create_group_form" name="create_group_form" method="post" action="">
          <?php wp_nonce_field(NW_Company_Profile::PREFIX . 'field', NW_Company_Profile::PREFIX . 'field_nonce'); ?>
          <input type="hidden" name="<?php echo NW_Company_Profile::HIDDEN_NAME ?>" value="create_group">

          <div class="group_header">
               <div class="group_inner"><span>新規グループID</span><input type="text" id="create_group_id" name="group_id" placeholder="必須" class="input_control"></div>
               <div class="group_inner"><span>新規グループ名</span><input type="text" id="create_group_label" name="group_label" placeholder="必須"></div>
          </div>

          <?php $options = NWPF::gets(); ?>
          <div id="input_area_create_group">
               <div class="group_items_left">
                    <p class="group_title">基本情報の一覧</p>
                    <ul id="sortable_create_group_ex" class="connected_sortable_create_group sortable_box">
                         <?php foreach ((array) $options as $key => $value) : ?>
                              <li id="<?php echo $key ?>"><?php echo $value[0] ?><span><?php echo '[' . $key . ']' ?></span></li>
                         <?php endforeach; ?>
                    </ul>
               </div>
               <div class="group_items_arrow"><i class="fas fa-arrows-alt-h"></i></div>
               <div class="group_items_right">
                    <p class="group_title">新規グループの基本情報</p>
                    <ul id="sortable_create_group_in" class="connected_sortable_create_group sortable_box">
                    </ul>
               </div>
               <input type="hidden" name="create_group_items">
          </div>
          <p class="submit"><input id="submit_btn_create_group" type="submit" name="submit_btn_create_group" class="button-primary" value="変更を保存" /></p>
     </form>
<?php
}

/**
 * 管理画面：グループの編集
 */
function nwpf_echo_edit_group_page()
{
     // グループオプションの取得
     if ($exist = get_option(NW_Company_Profile::OPTION_NAME . '_group')) {
          $get_raw_data = get_option(NW_Company_Profile::OPTION_NAME . '_group');
          $groups = unserialize($get_raw_data);
     }

     if (!$exist || !$groups) {
          echo '<p>グループがありません。</p>';
          return;
     }

     // 初項のIDを取得
     reset($groups);
     $group_id = key($groups);

     if (isset($_POST[NW_Company_Profile::HIDDEN_NAME]) && ($_POST[NW_Company_Profile::HIDDEN_NAME] === 'select_group' || $_POST[NW_Company_Profile::HIDDEN_NAME] === 'edit_group')) {
          if (isset($_POST['selected_group_id'])) {
               $temp_id = sanitize_text_field($_POST['selected_group_id']);
               if (array_key_exists($temp_id, $groups)) {
                    // グループ選択後あるいは変更を保存後であればそのグループのIDを取得
                    $group_id = $temp_id;
               }
               // グループ削除後であれば初項のIDのまま
          }
     }

     // グループ名を取得
     $group_label = $groups[$group_id]['label'];
     ?>

     <form id="select_group_form" name="select_group_form" method="post" action="">
          <?php wp_nonce_field(NW_Company_Profile::PREFIX . 'field', NW_Company_Profile::PREFIX . 'field_nonce'); ?>
          <input type="hidden" name="<?php echo NW_Company_Profile::HIDDEN_NAME ?>" class="select_box" value="select_group">
          <div class="group_select">
               グループの選択：<select name="selected_group_id" id="select_box_group" onchange="submit(this.form)">
                    <?php
                         // グループ一覧を出力
                         $html = '';
                         foreach ((array) $groups as $key => $value) {
                              $html .= '<option value="' . $key . '"';
                              if ($key === $group_id) {
                                   $html .= ' selected="selected"';
                              }
                              $html .= '>' . $value['label'] . '</option>';
                         }
                         echo $html;
                         ?>
               </select>
          </div>
     </form>

     <form id="edit_group_form" name="edit_group_form" method="post" action="">
          <?php wp_nonce_field(NW_Company_Profile::PREFIX . 'field', NW_Company_Profile::PREFIX . 'field_nonce'); ?>
          <input type="hidden" name="<?php echo NW_Company_Profile::HIDDEN_NAME ?>" value="edit_group">
          <input type="hidden" name="selected_group_id" value="<?php echo $group_id ?>">

          <?php
               // グループに属するアイテムのIDを格納
               $items = $groups[$group_id]['items'];

               // グループに属していないアイテムの情報を取得
               $exArgs = array('key' => $items, 'exclude' => true);
               $exOptions = NWPF::gets($exArgs);

               // グループに属するアイテムの情報を取得
               $inArgs = array('key' => $items, 'exclude' => false);
               $inOptions = NWPF::gets($inArgs);
               ?>
          <div class="group_header">
               <div class="group_inner"><span>グループID</span><span class="no_input"><?php echo $group_id ?></span></div>
               <div class="group_inner"><span>グループ名</span><input type="text" id="edit_group_label" name="group_label" value="<?php echo $group_label ?>"></div>
          </div>

          <div id="input_area_group_edit">
               <div class="group_items_left">
                    <p class="group_title">基本情報の一覧</p>
                    <ul id="sortable_edit_group_ex" class="connected_sortable_edit_group sortable_box">
                         <?php foreach ((array) $exOptions as $eKey => $eValue) : ?>
                              <li id="<?php echo $eKey ?>"><?php echo $eValue[0] ?><span><?php echo '[' . $eKey . ']' ?></span></li>
                         <?php endforeach; ?>
                    </ul>
               </div>
               <div class="group_items_arrow"><i class="fas fa-arrows-alt-h"></i></div>
               <div class="group_items_right">
                    <p class="group_title"><?php echo $group_label . ' グループの基本情報' ?></p>
                    <ul id="sortable_edit_group_in" class="connected_sortable_edit_group sortable_box">
                         <?php foreach ((array) $inOptions as $iKey => $iValue) : ?>
                              <li id="<?php echo $iKey ?>"><?php echo $iValue[0] ?><span><?php echo '[' . $iKey . ']' ?></span></li>
                         <?php endforeach; ?>
                    </ul>
               </div>
               <input type="hidden" name="edit_group_items" value="<?php echo implode(',', $items) ?>">
          </div>
          <p class="submit">
               <input id="submit_btn_edit_group" type="submit" name="submit_btn_edit_group" class="button-primary" value="変更を保存" />
               <button id="submit_btn_delete_group" type="submit" name="submit_btn_edit_group" value="delete"><?php echo $group_label . ' グループを削除' ?></button>
          </p>
     </form>

<?php
}

/**
 * 新規グループをデータベースに登録
 */
function nwpf_create_group()
{
     if (!(isset($_POST["create_group_items"]) && isset($_POST["group_id"]) && isset($_POST["group_label"]))) {
          return;
     }

     if (!empty($_POST['group_id']) && !empty($_POST['group_label'])) {
          $id = sanitize_text_field($_POST['group_id']);
          $label = sanitize_text_field($_POST['group_label']);
     } else {
          return;
     }

     $items = sanitize_text_field($_POST['create_group_items']);

     // カンマ区切りの文字列を配列に格納
     $items_array = explode(',', $items);

     if (get_option(NW_Company_Profile::OPTION_NAME . '_group')) {
          $get_raw_data = get_option(NW_Company_Profile::OPTION_NAME . '_group');
          $groups = unserialize($get_raw_data);
          $input_groups = array_merge($groups, array($id => array('label' => $label, 'items' => $items_array)));
     } else {
          $input_groups = array($id => array('label' => $label, 'items' => $items_array));
     }

     $serialized = serialize($input_groups);
     // Update
     update_option(NW_Company_Profile::OPTION_NAME . '_group',  $serialized);

     echo '<div class="updated"><p><strong>' . $label . __('グループを作成しました。') . '</strong></p></div>';
}

/**
 * グループをアップデート
 */
function nwpf_edit_group()
{
     if ((isset($_POST['submit_btn_edit_group']) && $_POST['submit_btn_edit_group'] === 'delete') && isset($_POST["selected_group_id"])) {
          // 削除処理

          // グループオプションの取得
          if (get_option(NW_Company_Profile::OPTION_NAME . '_group')) {
               $get_raw_data = get_option(NW_Company_Profile::OPTION_NAME . '_group');
               $groups = unserialize($get_raw_data);
          } else {
               return;
          }

          $delete_id = sanitize_text_field($_POST["selected_group_id"]);
          $label = $groups[$delete_id]['label'];

          // 連想配列からキーが一致する要素を除外
          unset($groups[$delete_id]);

          $serialized = serialize($groups);
          // Update
          update_option(NW_Company_Profile::OPTION_NAME . '_group',  $serialized);

          echo '<div class="updated"><p><strong>' . $label . __('グループを削除しました。') . '</strong></p></div>';
     } elseif ((isset($_POST['submit_btn_edit_group']) && $_POST['submit_btn_edit_group'] !== 'delete') && isset($_POST["selected_group_id"]) && isset($_POST["group_label"])) {
          // 更新処理

          // グループオプションの取得
          if (get_option(NW_Company_Profile::OPTION_NAME . '_group')) {
               $get_raw_data = get_option(NW_Company_Profile::OPTION_NAME . '_group');
               $groups = unserialize($get_raw_data);
          } else {
               return;
          }

          // グループに属するアイテムの更新
          if (isset($_POST["edit_group_items"])) {
               $items = sanitize_text_field($_POST['edit_group_items']);
               $group_id = sanitize_text_field($_POST['selected_group_id']);
               // カンマ区切りの文字列を配列に格納
               $items_array = explode(',', $items);
               // アイテムの更新
               $groups[$group_id]['items'] = $items_array;
          }

          // ラベルの取得と更新
          $label = sanitize_text_field($_POST["group_label"]);
          $groups[$group_id]['label'] = $label;

          $serialized = serialize($groups);
          // Update
          update_option(NW_Company_Profile::OPTION_NAME . '_group',  $serialized);

          echo '<div class="updated"><p><strong>' . $label . __('グループの内容を変更しました。') . '</strong></p></div>';
     }
}
