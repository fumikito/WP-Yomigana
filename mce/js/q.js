var yomiganaManager = {
	s: null,
	dom: null,
	target: null,
	onLoad: function (t){
		t.s = tinyMCEPopup.editor.selection;
		t.dom = tinyMCEPopup.dom;
		//すでにrubyタグがつけられている場合はrbタグを取得
		if(t.dom.is(t.s.getNode(), "q")){
			t.target = t.s.getNode();
		}
		
		//更新と新規の場合
		if(t.target){
			document.getElementById("citeText").value = t.target.cite;
			document.getElementById("delete").disabled = false;
		}
		//フォームにフォーカスを当てる
		document.getElementById("citeText").focus();
	},
	
	onSubmit: function(t){
		//引用文の取得
		var citeText = document.getElementById("citeText").value;
		//すでにqタグになっていた場合
		if(t.target){
			t.target.cite = citeText;
		}
		//ルビ新規作成の場合
		else{
			var q = document.createElement('q');
			q.cite = citeText;
			q.innerHTML = t.s.getContent();
			t.s.setNode(q);
		}
		tinyMCEPopup.close();
	},
	
	onDelete: function(t){
		//タグを脱がせる
		if(t.target){
			var str = t.target.innerHTML;
			t.dom.replace(document.createTextNode(str), t.target, false);
		}
		tinyMCEPopup.close();
	}
};