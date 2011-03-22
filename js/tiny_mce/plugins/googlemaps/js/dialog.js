tinyMCEPopup.requireLangPack();

var ExampleDialog = {

	init : function() {
		var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		//f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		//f.somearg.value = tinyMCEPopup.getWindowArg('akey');
	},

	insert : function() {
		// Insert the contents from the input into the document

		var strHtml = '';
		var akey      = document.forms[0].akey.value;
		var divnaam      = document.forms[0].divnaam.value;
		var coords      = document.forms[0].coords.value.split(",");
		var lat = coords[0];
		var long = coords[1];
		var cat = document.forms[0].cat.value;
		var des = tinyMCE.activeEditor.getContent();
		var width      = document.forms[0].width.value;
		var height      = document.forms[0].height.value;
		var zoom      = document.forms[0].zoomlevel.value;
		var hud      = document.forms[0].hud.value;
		var mapstyle      = document.forms[0].mapstyle.value;

		if (width == "") width = 100;

		if (height == "") height = 100;

		if (divnaam == "") divnaam = 'map';

		if (akey == "" || coords == "") {
			alert(tinyMCEPopup.getLang('googlemaps_dlg.missing_stuff'));
		}
		else {
			strHtml = "<div class='map' style='width: "+width+"px; height: "+height+"px'>Please don't change info here. Google Map will be loaded automatically after you submit the post:"+lat+","+long+","+cat+","+zoom+","+des+"</div><p/>";
			tinyMCEPopup.editor.execCommand('mceInsertContent', false, strHtml);
			tinyMCEPopup.close();
		}
	}
};

tinyMCEPopup.onInit.add(ExampleDialog.init, ExampleDialog);
