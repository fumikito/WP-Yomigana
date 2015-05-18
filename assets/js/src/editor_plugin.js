/**
 * TinyMCE plugin for WP-Yomigana
 *
 * @author Hametuha inc.
 * @package wp-yomigana
 */

/*global tinymce: true*/
/*global WpYomigana: true*/

tinymce.PluginManager.add('yomigana', function(editor, url) {

    /**
     * Wrap string with single value
     * @param editor
     * @param tagName
     * @return {Object}
     */
    function WrapWithSimpleTag(editor, tagName){
        var target = editor.selection.getNode(),
            newDom;
        if( target.nodeName == tagName.toUpperCase() ){
            // If this node is small, unwrap.
            newDom = document.createTextNode(target.innerHTML);
            editor.dom.replace(newDom, target, false);
        }else if( editor.selection.getContent().length > 0 ){
            // If letters selected, wrap it
            newDom = editor.dom.create(tagName);
            editor.dom.setHTML(newDom, editor.selection.getContent());
            editor.selection.setNode(newDom);
        }
        return newDom;
    }



    //
    // DL Button
    //
    // -----------------------
    //

    editor.addButton('dl', {
        image: WpYomigana.imageBase + 'dl.png',
        type: 'splitbutton',
        tooltip: WpYomigana.dl,
        stateSelector: 'dl',
        menu: [{
                   text: WpYomigana.dlToggle,
                   data: 'list'
               },
               {
                    text: WpYomigana.dtToggle,
                    data: 'toggle'
               }],
        onselect: function(e) {
            switch( e.control.settings.data ){
                case 'toggle':
                    // Switch dt to dd, dd to dt.
                    var node = editor.selection.getNode(),
                        dl   = editor.dom.getParent(node, 'dl'),
                        target, tagName, parent;
                    if( !dl ){
                        return;
                    }
                    if( editor.dom.is(node, 'dt') ){
                        target = node;
                        tagName = 'dd';
                    }else if( editor.dom.is(node, 'dd') ){
                        target = node;
                        tagName = 'dt';
                    }else if( ( parent = editor.dom.getParent(node, 'dt', dl)) ){
                        target = parent;
                        tagName = 'dd';
                    }else if( ( parent = editor.dom.getParent(node, 'dd', dl)) ){
                        target = parent;
                        tagName = 'dt';
                    }else{
                        // This is not dl
                        return;
                    }
                    editor.dom.rename(target, tagName);
                    break;
                default:
                    editor.execCommand('InsertDefinitionList');
                    break;
            }
        },
        onclick: function() {
            editor.execCommand('InsertDefinitionList');
        }
    });

    //
    // small button
    //
    // -----------------------
    //
    editor.addButton('small', {
        image: WpYomigana.imageBase + 'small.png',
        tooltip: WpYomigana.small,
        stateSelector: 'small',
        onClick: function(){
            WrapWithSimpleTag(editor, 'small');
        }
    });


    //
    // Cite Button
    //
    // -----------------------
    //
    editor.addButton('cite', {
        image: WpYomigana.imageBase + 'cite.png',
        tooltip: WpYomigana.cite,
        stateSelector: 'cite',
        onClick: function(){
            WrapWithSimpleTag(editor, 'cite');
        }
    });

    //
    // q Button
    //
    // -----------------------
    //

    editor.addButton('q', {
        image: WpYomigana.imageBase + 'q.png',
        tooltip: WpYomigana.q,
        stateSelector: 'q',
        onClick: function(){
            var s = editor.selection.getNode(),
                create = false,
                text = '',
                q, parent, cite;
            if( editor.dom.is(s, "q") ){
                q = s;
                text = jQuery(q).text();
            }else if( (parent = editor.dom.getParent(q, 'q') ) ){
                q = parent;
                text = jQuery(q).text();
            }else{
                create = true;
                text = editor.selection.getContent();
            }
            cite = editor.dom.getAttrib(q, 'cite', '');
            window.WpYomiganaHelper.q(WpYomigana.q, function(){
                jQuery(this).find('#citeText').val(text);
                jQuery(this).find('#citeFrom').val(cite);
            }, function($modal, action){
                switch( action ){
                    case 'set':
                        var origText = $modal.find('#citeText').val(),
                            citeText = $modal.find('#citeFrom').val();
                        if( create ){
                            // This is first
                            var newQ = document.createElement('q');
                            newQ.appendChild(document.createTextNode(origText));
                            newQ.cite = citeText;
                            editor.selection.setNode(newQ);
                        }else{
                            // Already exist
                            editor.dom.setAttrib(q, 'cite', citeText);
                        }
                        break;
                    case 'remove':
                        if( !create ){
                            // Remove tag
                            editor.dom.replace(document.createTextNode(text), q, false);
                        }
                        break;
                    default:
                        // Do nothing
                        break;
                }
            });
        }
    });

});
