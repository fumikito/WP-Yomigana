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
        let target = editor.selection.getNode(),
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
        image: WpYomigana.imageBase + 'dl-2.png',
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
                    let node = editor.selection.getNode(),
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
        image: WpYomigana.imageBase + 'small-2.png',
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
        image: WpYomigana.imageBase + 'cite-2.png',
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
        image: WpYomigana.imageBase + 'q-2.png',
        tooltip: WpYomigana.q,
        stateSelector: 'q',
        onClick: function(){
            let s = editor.selection.getNode(),
                create = false,
                text = '',
                q, parent, cite;
            if( editor.dom.is(s, "q") ){
                q = s;
                text = jQuery(q).text();
            }else if( (parent = editor.dom.getParent(s, 'q') ) ){
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
                        let origText = $modal.find('#citeText').val(),
                            citeText = $modal.find('#citeFrom').val();
                        if( create ){
                            // This is first
                            let newQ = document.createElement('q');
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



    //
    // ruby Button
    //
    // -----------------------
    //
    editor.addButton('ruby', {
        image: WpYomigana.imageBase + 'ruby-2.png',
        tooltip: WpYomigana.ruby,
        stateSelector: 'ruby',
        onClick: function(){
            let s = editor.selection.getNode(),
                create = false,
                text = '',
                ruby, parent, rt = false, rts;
            if( editor.dom.is(s, "ruby") ){
                ruby = s;
                text = ruby.firstChild.nodeValue;
            }else if( (parent = editor.dom.getParent(s, 'ruby') ) ){
                ruby = parent;
                text = ruby.firstChild.nodeValue;
            }else{
                create = true;
                text = editor.selection.getContent();
            }
            rts = editor.dom.select('rt', ruby);
            if(ruby && rts){
                rt = rts[0];
            }
            window.WpYomiganaHelper.ruby(WpYomigana.ruby, function(){
                jQuery(this).find('#rubyBody').val(text);
                jQuery(this).find('#rubyText').val(rt ? rt.firstChild.nodeValue : '');
            }, function($modal, action){
                switch( action ){
                    case 'set':
                        let rubyBody = $modal.find('#rubyBody').val(),
                            rubyText = $modal.find('#rubyText').val();
                        if( create ){
                            // This is first
                            let newRuby = document.createElement('ruby');
                            newRuby.appendChild(document.createTextNode(rubyBody));
                            let newRt = document.createElement('rt');
                            newRt.appendChild(document.createTextNode(rubyText));
                            newRuby.appendChild(newRt);
                            editor.selection.setNode(newRuby);
                        }else{
                            // Already exist
                            ruby.firstChild.nodeValue = rubyBody;
                            rt.firstChild.nodeValue = rubyText;
                        }
                        break;
                    case 'remove':
                        if( !create ){
                            // Remove tag
                            editor.dom.replace(document.createTextNode(text), ruby, false);
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
