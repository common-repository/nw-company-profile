/**
 * tabs.js
 */
jQuery(function ($) {

     /**
     * Run jquery-ui-tabs script.
     */
     $(function () {
          $("#tabs").tabs({
               active: 0,
               activate: function (event, ui) {
                    // URLのハッシュを書き換える
                    window.location.hash = ui.newTab.context.hash;
                    scrollTopAfterTabsChange();
               }
          });
     });

     var tabIndexs = { '#tabs-edit-profiles': 0, '#tabs-create-group': 1, '#tabs-edit-group': 2, '#tabs-manual': 3 };

     /**
      * URLのハッシュを参照してタブを切り替える（ブラウザバック時など）
      */
     window.addEventListener('hashchange', function () {
          var index = tabIndexs[window.location.hash];
          index = index ? index : 0;
          $('#tabs').tabs("option", "active", index);

          $('div.updated').remove();    // Delete information.
     });

     /**
      * URLのハッシュを参照してタブを切り替える（フォーム送信後）
      */
     $(function () {
          var index = tabIndexs[window.location.hash];
          index = index ? index : 0;
          $('#tabs').tabs("option", "active", index);
          setTimeout(scrollTopAfterTabsChange, 1);     // 1ms後にscrollTop(0,0)
     });

     function scrollTopAfterTabsChange() {
          $("html,body").scrollTop(0, 0);
     }

});
