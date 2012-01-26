(function(){
	tinymce.create("tinymce.plugins.yomigana",{
		init:function(editor,url){
			editor.addCommand("Ruby",function(){
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
			editor.addButton("ruby",{
				title:"yomigana_dlg.rubyTitle",
				cmd:"Ruby",
				image: url + "/img/ruby.gif"
			});
			editor.addCommand('inlineQ', function(){
				editor.windowManager.open({
					file: url + '/q.php',
					width: 350,
					height: 100
				},{
					plugin_url: url
				});
			});
			editor.addButton("q", {
				title: "yomigana_dlg.qTitle",
				cmd: 'inlineQ',
				image: url + '/img/q.png'
			});
			editor.addCommand("Small", function(){
				var target = this.selection.getNode();
				if(target.nodeName == 'SMALL'){
					//ノード名がSmallだったら外す
					var txt = document.createTextNode(target.innerHTML);
					this.dom.replace(txt, target, false);
				}else if(this.selection.getContent().length > 0){
					//一文字以上選択されていたらsmallでWrapする
					var newDom = this.dom.create('small');
					this.dom.setHTML(newDom, this.selection.getContent());
					this.selection.setNode(newDom);
				}
			});
			editor.addButton("small",{
				title: "yomigana_dlg.smallTitle",
				cmd: 'Small',
				image: url + '/img/small.png'
			});

			editor.onNodeChange.add(function(e,d,g,f){
				g = e.dom.getParent(g,"RUBY,SMALL");
				d.setDisabled("ruby",f);
				d.setActive("ruby",0);
				d.setDisabled("small",f);
				d.setActive("small",0);
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
	
	tinymce.PluginManager.add("yomigana",tinymce.plugins.yomigana);
})();