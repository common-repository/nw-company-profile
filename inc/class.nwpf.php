<?php

/**
 * NWPF
 * Version    : 1.1.2
 * Author     : NAKWEB
 * Created    : April 8, 2020
 * License    : GPLv2 or later
 */

class NWPF
{
     /**
      * Getting the contents.
      *
      * @param string $name ID
      * @return string
      */
     public static function get($name)
     {
          return self::get_contents_from_options($name);
     }

     /**
      * Getting the label.
      *
      * @param string $name ID
      * @return string
      */
     public static function get_label($name)
     {
          return self::get_label_from_options($name);
     }

     /**
      * Getting the options except any options have no contents.
      *
      * @param array $args {
      *     @type array or string 'key' Slug list or single
      *     @type bool 'exclude' Exclude value of 'key' from all options if this is true.
      * }
      *
      * @return array
      */
     public static function gets($args = NULL)
     {
          $options = self::get_options();
          if (empty($options)) {
               return;
          }

          $initial_args = array(
               'key' => NULL,
               'exclude' => false
          );

          if (isset($args) && is_string($args)) {
               $name = $args;
          } else {
               foreach ($initial_args as $key => $value) {
                    if (!(isset($args[$key]))) {
                         $args[$key] = $value;
                    }
               }
               $name = $args['key'];
               $exclude = $args['exclude'];
          }

          $array = array();

          if (is_null($name) || empty($name)) {
               foreach ($options as $key => $value) {
                    if (!empty($value[1])) {
                         $array[$key][0] = $value[0];
                         $array[$key][1] = $value[1];
                    }
               }
               return $array;
          }

          if (is_string($name)) {
               $array[0] = self::get_label($name);
               $array[1] = self::get($name);
               return $array;
          }

          if (!is_bool($exclude)) {
               return;
          }

          if (is_array($name)) {
               if ($exclude) {
                    // Getting the options exclude argument.
                    foreach ($options as $key => $value) {
                         if (!empty($value[1])) {
                              $flg = 0;
                              foreach ($name as $ex_name) {
                                   if ($ex_name === $key) {
                                        $flg = 1;
                                        break;
                                   }
                              }
                              if (!$flg) {
                                   $array[$key][0] = $value[0];
                                   $array[$key][1] = $value[1];
                              }
                         }
                    }
               } else {
                    // Getting the options include argument.
                    foreach ($name as $in_name) {
                         foreach ($options as $key => $value) {
                              if (!empty($value[1])) {
                                   if ($in_name === $key) {
                                        $array[$key][0] = $value[0];
                                        $array[$key][1] = $value[1];
                                        break;
                                   }
                              }
                         }
                    }
               }
               return $array;
          }
     }

     /**
      * Getting the options included in the specified group except any options have no contents.
      *
      * @param array $name group ID
      *
      * @return array
      */
     public static function get_group($name)
     {
          if (get_option(NW_Company_Profile::OPTION_NAME . '_group')) {
               $get_raw_data = get_option(NW_Company_Profile::OPTION_NAME . '_group');
               $groups = unserialize($get_raw_data);
          } else {
               return;
          }

          if (array_key_exists($name, $groups)) {
               $self_args = array('key' => $groups[$name]['items']);
               return self::gets($self_args);
          }

          return false;
     }

     /**
      * Getting the address concat any contents.
      *
      * @param array $args {
      *     @type bool 'sp1' Put a space after zip.
      *     @type bool 'sp2' Put a space before address1.
      *     @type bool 'sp3' Put a space before address2.
      * }
      *
      * @return string
      */
     public static function get_address($args = NULL)
     {
          $options = self::get_options();
          if (empty($options)) {
               return;
          }

          $initial_args = array(
               'sp1' => true,
               'sp2' => false,
               'sp3' => false
          );

          foreach ($initial_args as $key => $value) {
               if (!(isset($args[$key]) && is_bool($args[$key]))) {
                    $args[$key] = $value;
               }
          }

          $address = '';
          if (!empty($tmp = self::get_contents_from_options('zip'))) {
               $address .= $tmp;
               if ($args['sp1']) {
                    $address .= ' ';
               }
          }
          if (!empty($tmp = self::get_contents_from_options('pref'))) {
               $address .= nl2br($tmp);
          }
          if (!empty($tmp = self::get_contents_from_options('city'))) {
               $address .= nl2br($tmp);
          }
          if (!empty($tmp = self::get_contents_from_options('address1'))) {
               if ($args['sp2']) {
                    $address .= ' ';
               }
               $address .= nl2br($tmp);
          }
          if (!empty($tmp = self::get_contents_from_options('address2'))) {
               if ($args['sp3']) {
                    $address .= ' ';
               }
               $address .= nl2br($tmp);
          }
          return $address;
     }

     /**
      * Display the original html.
      * 
      * @param array $args {
      *     @type array 'key' ID list
      *     @type bool 'exclude' Exclude value of 'key' from all options if this is true.
      *     @type string 'address_label' Label of concatenated address information.
      *     @type bool 'sp1' Put a space after zip.
      *     @type bool 'sp2' Put a space before address1.
      *     @type bool 'sp3' Put a space before address2.
      * }
      */
     public static function display($args = NULL)
     {
          $options = self::get_options();
          if (empty($options)) {
               return;
          }

          $initial_args = array(
               'address_label' => '所在地'
          );

          if(isset($args['key'])){
               if (!is_null($args['key'])) {
                    if (is_array($args['key'])) {
                         $display_array = self::gets($args);
                    } else {
                         return;
                    }
               } else {
                    $display_array = self::gets();
               }
          }else{
               $display_array = self::gets();
          }
          if (empty($display_array)) {
               return;
          }

          if (!(isset($args['address_label']) && is_string($args['address_label']))) {
               $args['address_label'] = $initial_args['address_label'];
          }
          $address_label = $args['address_label'];
          $address = self::get_address($args);

          $flg = 0;
          $insertHtml = '<div id="nw_company_profile">';
          foreach ($display_array as $field) {
               foreach ($options as $key => $value) {
                    if ($value[0] === $field[0]) {
                         if ($key === 'zip' || $key === 'pref' || $key === 'city' || $key === 'address1' || $key === 'address2') {
                              if ($flg) {
                                   break;
                              } else {
                                   $flg = 1;
                              }
                         }
                         $insertHtml .= '<dl id="nwpf_list_' . $key . '">';
                         $insertHtml .= '<dt id="nwpf_term_' . $key . '">';
                         if ($key === 'zip' || $key === 'pref' || $key === 'city' || $key === 'address1' || $key === 'address2') {
                              $insertHtml .= $address_label;
                         } else {
                              $insertHtml .= nl2br($field[0]);
                         }
                         $insertHtml .= '</dt>';
                         $insertHtml .= '<dd id="nwpf_desc_' . $key . '">';
                         if ($key === 'zip' || $key === 'pref' || $key === 'city' || $key === 'address1' || $key === 'address2') {
                              $insertHtml .= $address;
                         } else {
                              $insertHtml .= nl2br($field[1]);
                         }
                         $insertHtml .= '</dd>';
                         $insertHtml .= '</dl>';
                         break;
                    }
               }
          }
          $insertHtml .= '</div>';
          echo $insertHtml;
     }

     /**
      * Getting the contents.
      *
      * @param string $name slug
      * @return string
      */
     public static function get_contents_from_options($name)
     {
          $options = self::get_options();
          if (empty($options)) {
               return;
          }

          if(isset($options[$name][1])){
               return $options[$name][1];
          }else{
               return;
          }
     }

     /**
      * Getting the label.
      *
      * @param string $name slug
      * @return string
      */
     public static function get_label_from_options($name)
     {
          $options = self::get_options();
          if (empty($options)) {
               return;
          }

          if(isset($options[$name][0])){
               return $options[$name][0];
          }else{
               return;
          }
     }

     /**
      * Getting the options from wp_option.
      *
      * @return string
      */
     public static function get_options()
     {
          $options = get_option(NW_Company_Profile::OPTION_NAME);
          if (is_array($options)) {
               foreach ($options as &$value) {
                    $value[0] = sanitize_text_field($value[0]);
                    $value[1] = html_entity_decode(stripslashes($value[1]));
               }
          }

          return $options;
     }
}
