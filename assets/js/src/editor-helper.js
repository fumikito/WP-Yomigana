/**
 * Description
 */

/*global tinymce: true*/
/*global WpYomigana*/

(function ($) {
    'use strict';

    $(document).ready(function () {
        window.WpYomiganaHelper = new (function () {


            this.q = function (title, onOpen, callback) {
                $(WpYomigana.qForm).dialog(this.getOption(title, onOpen, callback));
            };

            this.ruby = function (title, onOpen, callback) {
                $(WpYomigana.rubyForm).dialog(this.getOption(title, onOpen, callback));
            };


            this.getOption = function (title, onOpen, callback) {
                return {
                    title: title,
                    modal        : true,
                    closeOnEscape: true,
                    resizable    : true,
                    closeText    : WpYomigana.close,
                    open         : onOpen,
                    buttons      : [{
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

                                    }]
                }
            };

        })();

    });

})(jQuery);
