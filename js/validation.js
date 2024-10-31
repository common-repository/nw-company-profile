/**
 * validation.js
 */
jQuery(function ($) {

     /**
     * Input control.
     */
     $(function () {
          $(document).on('keydown', '.input_control', function (e) {
               let k = e.keyCode;
               let str = String.fromCharCode(k);
               // Half-width alphanumeric, Arrows, UnderScore, BackSpace, Tab, Delete
               if (!((str.match(/[\w]/)) || (37 <= k && k <= 40) || (k === 226) || (k === 8) || (k === 9) || (k === 46))) {
                    return false;
               }
          });
     });

     /**
     * Replace characters except Half-width alphanumeric.
     */
     $(function () {
          $(document).on('keyup', '.input_control', function (e) {
               this.value = this.value.replace(/[\W]+/, '');
          });
     });
     $(function () {
          $(document).on('blur', '.input_control', function () {
               this.value = this.value.replace(/[\W]+/, '');
          });
     });

     /**
     * Replace uppercase to lowercase.
     */
     $(function () {
          $(document).on('change', '.input_control', function () {
               var string = $(this).val();
               var lower = string.toLowerCase();
               $(this).val(lower);
          });
     });

     /************************************************************
      *                                                          *
      *   EDIT PROFILES BLOCK                                    *
      *                                                          *
      ************************************************************/

     /**
     * DeCheck duplications.
     */
     $(function () {
          $(document).on('change', '.draft_slug', function () {
               checkDuplications();
          });
     });
     $(function () {
          $(document).on('click', '.rm_form_btn', function () {
               checkDuplications();
          });
     });
     function checkDuplications() {
          $('.draft_slug').each(function (i) {
               var targetSlug = $(this).val();
               var targetIndex = i;
               var flg = 0;
               $('.slug').each(function () {
                    // Compare draft slug with registerd slugs.
                    if (targetSlug === $(this).text()) {
                         flg = 1;
                    }
               });
               $('.draft_slug').each(function (i) {
                    // Compare draft slug with other new slugs.
                    if (targetIndex !== i) {
                         if (targetSlug === $(this).val()) {
                              flg = 1;
                         }
                    }
               });
               if (!targetSlug) {
                    // Ignore empty field.
                    flg = 0;
               }
               if (flg) {
                    $(this).addClass('duplication');
                    if (!$(this).next('p').hasClass('notice')) {
                         $(this).after('<p class="notice">同じIDが存在します！</p>');
                    }
               } else {
                    $(this).removeClass('duplication');
                    $(this).next('p').remove();
               }
          });
          setStatus();
     }

     /**
     * Set state to valid or invalid.
     */
     function setStatus() {
          var flg = 0;
          $('.draft_slug').each(function () {
               if ($(this).hasClass('duplication')) {
                    flg = 1;
               }
          });
          if (flg) {
               $('#submit_btn').prop('disabled', true);
          } else {
               $('#submit_btn').prop('disabled', false);
          }
     }

     /************************************************************
      *                                                          *
      *   EDIT GROUP BLOCK                                       *
      *                                                          *
      ************************************************************/

     /**
      * Create group form.
      */
     $(function () {
          $('#submit_btn_create_group').click(function () {
               if (!$('#create_group_label').val() || !$('#create_group_id').val()) {
                    alert('新規グループ名と新規グループIDを入力してください。')
                    // グループ名またはグループIDのいずれかが空であれば送信無効
                    return false;
               }
          });
     });

     /**
     * DeCheck duplications.
     */
     $(function () {
          $('#create_group_id').change(function () {
               var newValue = $(this).val();
               var flg = false;
               $('#select_box_group option').each(function () {
                    var registerdValue = $(this).attr('value');
                    if (newValue === registerdValue) {
                         flg = true;
                         $('#submit_btn_create_group').prop('disabled', true);
                         $('#create_group_id').after('<p class="notice">同じグループIDが存在します！</p>');
                         return false;
                    }
               });
               if (!flg) {
                    $('#submit_btn_create_group').prop('disabled', false);
                    $(this).next('p').remove();
               }
          });
     });

     /**
      * Edit group form.
      */
     $(function () {
          $('#submit_btn_edit_group').click(function () {
               if (!$('#edit_group_label').val()) {
                    alert('グループ名を入力してください。')
                    // グループ名が空であれば送信無効
                    return false;
               }
          });
     });

     /**
      * Delete group.
      */
     $(function () {
          $('#submit_btn_delete_group').click(function () {
               if (!confirm('本当に削除しますか？')) {
                    // 送信キャンセル
                    return false;
               }
          });
     });


});
