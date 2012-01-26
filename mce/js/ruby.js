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
			document.getElementById("moji").value = t.target.firstChild.nodeValue;
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
		var tag = yomiganaManager.createRuby(ruby_body, ruby_text);
		if(t.target){
			//すでにルビを作成済みの場合
			t.dom.replace(tag, t.target);
		}else{
			//ルビ新規作成の場合
			t.s.setNode(tag);
		}
		tinyMCEPopup.close();
	},
	
	onDelete: function(t){
		//タグを脱がせる
		if(t.target){
			var rubyTxt = t.target.firstChild.nodeValue;
			t.dom.replace(document.createTextNode(rubyTxt), t.target, false);
		}
		tinyMCEPopup.close();
	},
	
	createRuby: function(parent, child){
		var ruby = document.createElement('ruby');
		var rt = document.createElement('rt');
		rt.innerHTML = child;
		var rb = document.createTextNode(parent);
		ruby.appendChild(rb);
		ruby.appendChild(rt);
		return ruby;
	}
};