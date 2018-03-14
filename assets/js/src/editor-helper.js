/**
 * Description
 */

/*global tinymce: true*/
/*global WpYomigana*/

(function ($) {

  'use strict';

  $(document).ready(function () {

    window.WpYomiganaHelper = new (function () {

      let q, ruby;

      this.q = function (title, onOpen, callback) {
        if (!q) {
          q = $(WpYomigana.qForm);
        }
        q.dialog(this.getOption(title, onOpen, callback));
      };

      this.ruby = function (title, onOpen, callback) {
        if (!ruby) {
          ruby = $(WpYomigana.rubyForm);
        }
        ruby.dialog(this.getOption(title, onOpen, callback));
      };


      this.getOption = function (title, onOpen, callback) {
        return {
          title        : title,
          dialogClass  : 'yomigana-dialog',
          modal        : true,
          closeOnEscape: true,
          resizable    : true,
          closeText    : WpYomigana.close,
          open         : onOpen,
          beforeClose  : function (event, ui) {
            $(this).find('#citeFrom, #rubyText').val('');
          },
          buttons      : [
            {
              text : WpYomigana.ok,
              icons: {
                primary: "ui-icon-check"
              },
              click: function () {
                callback($(this), 'set');
                $(this).dialog("close");
              }
            },
            {
              text : WpYomigana.unwrap,
              icons: {
                primary: "ui-icon-closethick"
              },
              click: function () {
                callback($(this), 'remove');
                $(this).dialog("close");
              }
            }
          ]
        }
      };

    })();

  });

})(jQuery);
