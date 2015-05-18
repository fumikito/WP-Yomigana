/**
 * Description
 */

/*global tinymce: true*/

(function ($) {
    'use strict';

    window.WpYomiganaHelper = {

    };


    $(document).ready(function(){

        console.log('test');

        $( "<p>テスト</p>" ).dialog({
            modal: true,

            buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    })  ;

})(jQuery);
