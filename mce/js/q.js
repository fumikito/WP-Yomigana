var yomiganaManager = {
	s: null,
	dom: null,
	target: null,
	onLoad: function (t){
		t.s = tinyMCEPopup.editor.selection;
		t.dom = tinyMCEPopup.dom;
		if(tinymce.isIE){
			tinyMCEPopup.editor.selection.moveToBookmark(tinyMCEPopup.editor.ieBookmark);
		}
		//すでにqタグがつけられている場合はqタグを取得
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
		if(t.target){
			//すでにqタグになっていた場合
			t.target.cite = citeText;
		}else{
			//ルビ新規作成の場合
			var q = document.createElement('q');
			q.cite = citeText;
			if(tinymce.isIE){
				tinyMCEPopup.editor.selection.moveToBookmark(tinyMCEPopup.editor.ieBookmark);
			}
			q.innerHTML = t.s.getContent();
			t.s.setNode(q);
		}
		tinyMCEPopup.close();
	},
	
	onDelete: function(t){
		//タグを脱がせる
		if(t.target){
			var str = t.target.innerHTML;
			if(tinymce.isIE){
				tinyMCEPopup.editor.selection.moveToBookmark(tinyMCEPopup.editor.ieBookmark);
			}
			t.dom.replace(document.createTextNode(str), t.target, false);
		}
		tinyMCEPopup.close();
	}
};