tinymce.PluginManager.add("yomigana",function(e,t){function o(e,t){var o,n=e.selection.getNode();return n.nodeName==t.toUpperCase()?(o=document.createTextNode(n.innerHTML),e.dom.replace(o,n,!1)):e.selection.getContent().length>0&&(o=e.dom.create(t),e.dom.setHTML(o,e.selection.getContent()),e.selection.setNode(o)),o}e.addButton("dl",{image:WpYomigana.imageBase+"dl.png",type:"splitbutton",tooltip:WpYomigana.dl,stateSelector:"dl",menu:[{text:WpYomigana.dlToggle,data:"list"},{text:WpYomigana.dtToggle,data:"toggle"}],onselect:function(t){switch(t.control.settings.data){case"toggle":var o,n,i,a=e.selection.getNode(),d=e.dom.getParent(a,"dl");if(!d)return;if(e.dom.is(a,"dt"))o=a,n="dd";else if(e.dom.is(a,"dd"))o=a,n="dt";else if(i=e.dom.getParent(a,"dt",d))o=i,n="dd";else{if(!(i=e.dom.getParent(a,"dd",d)))return;o=i,n="dt"}e.dom.rename(o,n);break;default:e.execCommand("InsertDefinitionList")}},onclick:function(){e.execCommand("InsertDefinitionList")}}),e.addButton("small",{image:WpYomigana.imageBase+"small.png",tooltip:WpYomigana.small,stateSelector:"small",onClick:function(){o(e,"small")}}),e.addButton("cite",{image:WpYomigana.imageBase+"cite.png",tooltip:WpYomigana.cite,stateSelector:"cite",onClick:function(){o(e,"cite")}}),e.addButton("q",{image:WpYomigana.imageBase+"q.png",tooltip:WpYomigana.q,stateSelector:"q",onClick:function(){var t,o,n,i=e.selection.getNode(),a=!1,d="";e.dom.is(i,"q")?(t=i,d=jQuery(t).text()):(o=e.dom.getParent(t,"q"))?(t=o,d=jQuery(t).text()):(a=!0,d=e.selection.getContent()),n=e.dom.getAttrib(t,"cite",""),window.WpYomiganaHelper.q(WpYomigana.q,function(){jQuery(this).find("#citeText").val(d),jQuery(this).find("#citeFrom").val(n)},function(o,n){switch(n){case"set":var i=o.find("#citeText").val(),l=o.find("#citeFrom").val();if(a){var c=document.createElement("q");c.appendChild(document.createTextNode(i)),c.cite=l,e.selection.setNode(c)}else e.dom.setAttrib(t,"cite",l);break;case"remove":a||e.dom.replace(document.createTextNode(d),t,!1)}})}})});
//# sourceMappingURL=map/editor_plugin.js.map