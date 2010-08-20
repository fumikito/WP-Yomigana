(function(){
	tinymce.create("tinymce.plugins.Yomigana",{
		init:function(editor,url){
			editor.addCommand("Yomigana",function(){
				editor.windowManager.open(
					{
						file: url + "/ruby.php",
						width: 350,
						height:150,
						inline: 1
					},
					{
						plugin_url: url
					}
				);
			});
			editor.addButton("yomigana",{
				title:"yomigana_dlg.btn_title",
				cmd:"Yomigana",
				image: url + "/img/ruby.gif"
			});
			editor.onNodeChange.add(function(e,d,g,f){
				g = e.dom.getParent(g,"RB");
				d.setDisabled("yomigana",f);
				d.setActive("yomigana",0);
				if(g){
					do{
						d.setDisabled(g.nodeName.toLowerCase(),0);
						d.setActive(g.nodeName.toLowerCase(),1);
					}while(g = g.parentNode);
				}
			});
		},
		getInfo:function(){
			return{
				longname:"Yomigana",
				author:"Takahashi Fumiki",
				authorurl:"http://hametuha.co.jp",
				infourl:"http://hametuha.co.jp",
				version:"1.0"
			};
		}
	});
	
	tinymce.PluginManager.add("yomigana",tinymce.plugins.Yomigana);
})();