"use strict";var yomiganaManager={s:null,dom:null,target:null,onLoad:function(e){e.s=tinyMCEPopup.editor.selection,e.dom=tinyMCEPopup.dom,tinymce.isIE&&tinyMCEPopup.editor.selection.moveToBookmark(tinyMCEPopup.editor.ieBookmark),e.dom.is(e.s.getNode(),"RT,RP")?e.target=e.dom.getParent(e.s.getNode(),"RUBY"):e.dom.is(e.s.getNode(),"RUBY")&&(e.target=e.s.getNode()),e.target?(document.getElementById("moji").value=e.target.firstChild.nodeValue,document.getElementById("yomi").value=e.dom.getNext(e.target.firstChild,"rt").innerHTML,e.dom.getNext(e.target.firstChild,"rp")&&(document.getElementById("need-paren").checked=!0),document.getElementById("delete").disabled=!1):document.getElementById("moji").value=e.s.getContent(),document.getElementById("yomi").focus()},onSubmit:function(e){var t=document.getElementById("moji").value,o=document.getElementById("yomi").value,n=yomiganaManager.createRuby(t,o,document.getElementById("need-paren").checked);tinymce.isIE&&tinyMCEPopup.editor.selection.moveToBookmark(tinyMCEPopup.editor.ieBookmark),e.target?e.dom.replace(n,e.target):e.s.setNode(n),tinyMCEPopup.close()},onDelete:function(e){if(e.target){tinymce.isIE&&tinyMCEPopup.editor.selection.moveToBookmark(tinyMCEPopup.editor.ieBookmark);var t=e.target.firstChild.nodeValue;e.dom.replace(document.createTextNode(t),e.target,!1)}tinyMCEPopup.close()},createRuby:function(e,t,o){var n=document.createElement("ruby"),d=document.createTextNode(e);n.appendChild(d);var i=document.createElement("rt");if(i.innerHTML=t,o){var r=document.createElement("rp");r.innerHTML="(";var a=document.createElement("rp");a.innerHTML=")",n.appendChild(r),n.appendChild(i),n.appendChild(a)}else n.appendChild(i);return n}};
//# sourceMappingURL=map/ruby.js.map
