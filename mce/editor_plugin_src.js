(function(){
	
	tinymce.create("tinymce.plugins.yomigana",{
		
		getInfo:function(){
			return{
				longname:"Yomigana",
				author:"Takahashi Fumiki",
				authorurl:"http://hametuha.co.jp",
				infourl:"http://hametuha.co.jp",
				version:"1.0"
			};
		},
		
		init:function(editor,url){
			//ルビ
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
			//qタグ
			editor.addCommand('inlineQ', function(){
				editor.windowManager.open({
					file: url + '/q.php',
					width: 350,
					height: 150,
					inline: 1
				},{
					plugin_url: url
				});
			});
			editor.addButton("q", {
				title: "yomigana_dlg.qTitle",
				cmd: 'inlineQ',
				image: url + '/img/q.png'
			});
			//small
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
			editor.addCommand('DL', function(){
				var startNode = this.selection.getStart();
				var endNode = this.selection.getEnd();
				var wrapper;
				if(this.dom.getParent(startNode, 'DL')){
					wrapper = this.dom.getParent(startNode, 'DL');
				}else if(this.selection.getNode().tagName == 'DL'){
					wrapper = this.selection.getNode();
				}
				if(wrapper){
					//親がDL
					for(i = 0, l = wrapper.childNodes.length; i < l; i++){
						var p = document.createElement('p');
						p.innerHTML = wrapper.childNodes[i].innerHTML;
						this.dom.insertAfter(p, wrapper);
					}
					this.dom.remove(wrapper);
				}else{
					//まだDLじゃない
					if(this.selection.getContent().length > 0){
						var dl = this.dom.create('dl');
						if(startNode == endNode){
							//一つの要素が選択されている
							this.dom.rename(startNode, 'dt');
							dl.appendChild(startNode);
							this.selection.setNode(dl);
						}else{
							//2つ以上の要素が選択されている
							var currentNode = startNode;
							var d = this.dom;
							for(i = 0; true;){
								this.dom.add(dl, (i % 2 == 0 ? 'DT' : 'DD'), {}, currentNode.innerHTML);
									i++;
								if(currentNode == endNode || !(currentNode = this.dom.getNext(currentNode, function(node){
										return node && d.isBlock(node);
									})) || i > 100){
									break;
								}
							}
						}
						this.selection.setNode(dl);
					}
				}
			});
			editor.addButton("dl",{
				title: "yomigana_dlg.dlTitle",
				cmd: "DL",
				image: url + '/img/dl.png'
			});
			//キャレットの移動
			editor.onNodeChange.add(function(e,d,g,f){
				g = e.dom.getParent(g,"RUBY,SMALL,Q,DL");
				d.setDisabled("ruby",f);
				d.setActive("ruby",0);
				d.setDisabled("small",f);
				d.setActive("small",0);
				d.setDisabled("q",f);
				d.setActive("q",0);
				if(g){
					do{
						d.setDisabled(g.nodeName.toLowerCase(),0);
						d.setActive(g.nodeName.toLowerCase(),1);
					}while(g = g.parentNode);
				}
			});
		}
	});
	
	tinymce.PluginManager.add("yomigana",tinymce.plugins.yomigana);
})();