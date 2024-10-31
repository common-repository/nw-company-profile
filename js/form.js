/**
 * form.js
 */
jQuery(function ($) {

     /**
     * HTML document
     */
     const htmldoc = `
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
     </div>`;

     /**
     * Add HTML document.
     */
     $(function () {
          $(document).on('click', '.add_form_btn', function () {
               $(this).parent().parent().after(htmldoc);
          });
     });

     /**
     * Remove HTML document.
     */
     $(function () {
          $(document).on('click', '.rm_form_btn', function () {
               if (!check_remaining_rm_btn()) {
                    return false;
               }
               $(this).parent().parent().remove();
          });
     });

     function check_remaining_rm_btn() {
          var size = $('.rm_form_btn').length;
          if (size <= 1) {
               return false;
          }
          return true;
     }

     /**
     * Change checked all delete checkbox.
     */
     $(function () {
          $('.all_check_btn').on('change', function () {
               $('.delete_check_btn').prop('checked', this.checked);
               $('.all_check_btn').prop('checked', this.checked);
          });
     });

     /**
     * Do actions.
     */
     $(function () {
          var form = $('#profile_form');
          form.submit(function () {
               name_posts();
               return confirm_delete();
          });
     });

     /**
     * Name new items.
     */
     function name_posts() {
          $('dl.draft').each(function (i) {
               $(this).find('.draft_slug').attr('name', 'add_request_slug_' + i);
               $(this).find('.draft_label').attr('name', 'add_request_label_' + i);
               $(this).find('.draft_contents').attr('name', 'add_request_contents_' + i);
          });
     }

     /**
     * Confirm delete items.
     */
     function confirm_delete() {
          var delete_list = '';
          var delete_count = 0;
          $('.delete_check_btn:checked').each(function () {
               delete_count++;
               delete_list += ($(this).val() + ', ');
          });
          delete_list = delete_list.slice(0, -2);

          if (delete_count) {
               var message = '変更の保存と同時に以下の' + delete_count + '項目を削除します。よろしいですか？\n\n' + delete_list + '';
               if (window.confirm(message)) {
                    return true;
               } else {
                    return false;
               }
          }
     }

     /**
     * Auto resize height of textarea with each input.
     */
     $(function () {
          $(document).on('change keyup keydown paste cut', 'textarea.textarea_type', function () {
               var $textarea = $(this);
               var lineHeight = parseInt($textarea.css('lineHeight'));
               $textarea.on('input', function (evt) {
                    var lines = ($(this).val() + '\n').match(/\n/g).length;
                    $(this).height(lineHeight * lines);
               });
          });
     });

     /**
     * Adjust height of textarea on window loaded.
     */
     $(function () {
          $('textarea.textarea_type').each(function () {
               var $textarea = $(this);
               var lineHeight = parseInt($textarea.css('lineHeight'));
               var lines = ($(this).val() + '\n').match(/\n/g).length;
               $(this).height(lineHeight * lines);
          });
     });
});
