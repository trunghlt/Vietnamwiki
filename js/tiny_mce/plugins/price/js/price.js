tinyMCEPopup.requireLangPack();
var PriceDialog = {
	init : function() {
		var f = document.forms[0];
		// Get the selected contents as text and place it in the input
		
		//tinyMCEPopup.editor.dom.remove(tinyMCEPopup.editor.selection.getStart());
		var str = tinyMCEPopup.editor.dom.getAttrib(tinyMCEPopup.editor.selection.getNode(),'class');
		str1 = str.substring(5,6);
		f.price.value = str1;
	},

	insert : function() {
		// Insert the contents from the input into the document
		var ed = tinyMCEPopup.editor, dom = ed.dom;
	var tl;
	switch(document.forms[0].price.value)
	{
		case '1': tl ="(<$10)";break;
		case '2': tl ="($10-$50)";break;
		case '3': tl ="($50-100$)";break;
		case '4': tl ="($100-$200)";break;
		case '5': tl ="($200-$500)";break;
		case '6': tl ="($500-$1000)";break;
		default: tl = "null"; break;
	}
		
		tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
							class:"price"+document.forms[0].price.value,
							src:tinyMCEPopup.getWindowArg('plugin_url')+ "/img/dollar"+document.forms[0].price.value+".gif",
							title:tl
							}));
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(PriceDialog.init, PriceDialog);
