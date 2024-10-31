/**
 * sortable.js
 */
jQuery(function ($) {

     /**
     * Run jquery-ui-sortable script.
     */
     $(function () {
          $("#sortable").sortable({
               axis: 'y'
          });
          $("#sortable").disableSelection();
     });

     $(function () {
          $("#sortable_create_group_ex, #sortable_create_group_in").sortable({
               connectWith: ".connected_sortable_create_group",
               dropOnEmpty: true,
               forcePlaceholderSize: true
          }).disableSelection();
          $("#sortable_edit_group_ex, #sortable_edit_group_in").sortable({
               connectWith: ".connected_sortable_edit_group",
               dropOnEmpty: true,
               forcePlaceholderSize: true
          }).disableSelection();
     });

     $(function () {
          $("#sortable_create_group_in,#sortable_edit_group_in").sortable({
               update: function (event, ui) {
                    if ($(this).is("#sortable_create_group_in")) {
                         var target = $('input[name="create_group_items"]');
                    } else {
                         var target = $('input[name="edit_group_items"]');
                    }
                    var result = $(this).sortable("toArray");
                    target.val(result);
               }
          });
     });
});
