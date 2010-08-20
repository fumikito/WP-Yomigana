var yomiganaManager = {
	s: null,
	dom: null,
	target: null,
	onLoad: function (t){
		t.s = tinyMCEPopup.editor.selection;
		t.dom = tinyMCEPopup.dom;
		console.log(t.dom.is(t.s.getNode(), "ruby"));
		//すでにrubyタグがつけられている場合はrbタグを取得
		if(t.dom.is(t.s.getNode(), "rb")){
			t.target = t.s.getNode();
		}else if(t.dom.is(t.s.getNode(), "ruby")){
			t.target = t.s.getNode().firstChild;
		}else if(t.dom.is(t.s.getNode(), "p") && t.s.getContent().match(/^<ruby>.*<\/ruby>$/)){
			alert(
				"ルビタグの選択が上手く行きませんでした。\n" +
				"ルビの親文字を選択しなおしてください。\n" +
				"親文字のうち1文字だけを選択すると上手く行きます。"
			);
			tinyMCEPopup.close();
		}
		
		//更新と新規の場合
		if(t.target){
			document.getElementById("moji").value = t.target.innerHTML;
			document.getElementById("yomi").value = t.target.nextSibling.innerHTML;
			document.getElementById("delete").disabled = false;
		}else{
			document.getElementById("moji").value = t.s.getContent();
		}
		//フォームにフォーカスを当てる
		document.getElementById("yomi").focus();
	},
	
	onSubmit: function(t){
		//ルビ用文字の取得
		var ruby_body = document.getElementById("moji").value;
		var ruby_text = document.getElementById("yomi").value;
		//すでにルビを作成済みの場合
		if(t.target){
			t.target.nextSibling.innerHTML = ruby_text;
			t.target.innerHTML = ruby_body;
		}
		//ルビ新規作成の場合
		else{
			var tag = "<ruby><rb>" + t.s.getContent() + "</rb><rt>" + ruby_text + "</rt></ruby>";
			t.s.setContent(tag);
		}
		tinyMCEPopup.close();
	},
	
	onDelete: function(t){
		//タグを脱がせる
		if(t.target){
			var str = t.target.innerHTML;
			var p = t.dom.getParent(t.target, "ruby");
			t.dom.replace(document.createTextNode(str), p, false);
		}
		tinyMCEPopup.close();
	}
};