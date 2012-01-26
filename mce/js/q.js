var yomiganaManager = {
	s: null,
	dom: null,
	target: null,
	onLoad: function (t){
		t.s = tinyMCEPopup.editor.selection;
		t.dom = tinyMCEPopup.dom;
		//すでにrubyタグがつけられている場合はrbタグを取得
		if(t.dom.is(t.s.getNode(), "rt")){
			t.target = t.dom.getParent(t.s.getNode(), 'RUBY');
		}else if(t.dom.is(t.s.getNode(), "ruby")){
			t.target = t.s.getNode();
		}
		
		//更新と新規の場合
		if(t.target){
			document.getElementById("moji").value = t.target.firstChild;
			document.getElementById("yomi").value = t.target.firstChild.nextSibling.innerHTML;
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
			t.dom.replace();
			t.target.nextSibling.innerHTML = ruby_text;
			t.target.innerHTML = ruby_body;
		}
		//ルビ新規作成の場合
		else{
			var tag = "<ruby>" + t.s.getContent() + "<rt>" + ruby_text + "</rt></ruby>";
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