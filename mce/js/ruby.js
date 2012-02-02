var yomiganaManager = {
	s: null,
	dom: null,
	target: null,
	onLoad: function (t){
		t.s = tinyMCEPopup.editor.selection;
		t.dom = tinyMCEPopup.dom;
		//すでにrubyタグがつけられている場合はrbタグを取得
		if(t.dom.is(t.s.getNode(), "rt,rp")){
			t.target = t.dom.getParent(t.s.getNode(), 'RUBY');
		}else if(t.dom.is(t.s.getNode(), "ruby")){
			t.target = t.s.getNode();
		}
		//更新と新規の場合
		if(t.target){
			document.getElementById("moji").value = t.target.firstChild.nodeValue; //親文字の取得
			document.getElementById("yomi").value = t.dom.getNext(t.target.firstChild, 'rt').innerHTML; //ルビ文字
			if(t.dom.getNext(t.target.firstChild, 'rp')){
				//rpタグのサポートにチェック
				document.getElementById('need-paren').checked = true;
			}
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
		var tag = yomiganaManager.createRuby(ruby_body, ruby_text, document.getElementById('need-paren').checked);
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
	
	createRuby: function(parent, child, needRP){
		var ruby = document.createElement('ruby');
		var rb = document.createTextNode(parent);
		ruby.appendChild(rb);
		var rt = document.createElement('rt');
		rt.innerHTML = child;
		if(needRP){
			var rp1 = document.createElement('rp');
			rp1.innerHTML = '(';
			var rp2 = document.createElement('rp');
			rp2.innerHTML = ')';
			ruby.appendChild(rp1);
			ruby.appendChild(rt);
			ruby.appendChild(rp2);
		}else{
			ruby.appendChild(rt);
		}
		return ruby;
	}
};