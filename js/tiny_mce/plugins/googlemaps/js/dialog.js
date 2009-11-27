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
		var coords      = document.forms[0].coords.value;
		var width      = document.forms[0].width.value;
		var height      = document.forms[0].height.value;
		var zoom      = document.forms[0].zoomlevel.value;
		var hud      = document.forms[0].hud.value;
		var mapstyle      = document.forms[0].mapstyle.value;

			if (width == "")
		width = 100;

			if (height == "")
		height = 100;

			if (divnaam == "")
		divnaam = 'map';

		if (akey == "" || coords == "")
		{
		alert(tinyMCEPopup.getLang('googlemaps_dlg.missing_stuff'));
		}
		else
		{
		strHtml = '<span id="spangooglemaps">\n';
		strHtml += '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='+akey+'" type="text/javascript"></script>';
		strHtml += '<script type="text/javascript">\n';

		strHtml += 'function load() \n';
    strHtml += '{\n';
    strHtml += '  if (GBrowserIsCompatible())\n';
    strHtml += '  {\n';
    strHtml += '  var map = new GMap2(document.getElementById("'+divnaam+'"))\;\n';
    strHtml += '  var center = new GLatLng('+coords+')\;\n';
    strHtml += '  map.setCenter(center, '+zoom+')\;\n';
	strHtml += '  var vnwkMarker = new GIcon(G_DEFAULT_ICON)\;\n';
	strHtml += '  vnwkMarker.image = "/images/marker.png"\;\n';
	strHtml += '  vnwkMarker.iconSize = new GSize(32, 32)\;\n';
	strHtml += '  vnwkMarker.shadowSize = new GSize(0, 0)\;\n';
	strHtml += '  vnwkMarker.iconAnchor = new GPoint(10, 24)\;\n';
    strHtml += '  map.addOverlay(new GMarker(center, {icon: vnwkMarker}))\;\n';

    if(mapstyle==2){  strHtml += '  map.setMapType(G_SATELLITE_MAP);\n';   }
    if(mapstyle==3){  strHtml += '  map.setMapType(G_HYBRID_MAP);\n';   }
    if(mapstyle==4){  strHtml += '  map.setMapType(G_PHYSICAL_MAP);\n';   }

    if(hud==1){
	    strHtml += '  var mapControl = new GMapTypeControl()\;\n';
	    strHtml += '  map.addControl(mapControl)\;\n';
	    strHtml += '  map.addControl(new GLargeMapControl())\;\n';
    }
    if(hud==2){
		strHtml += '  var customUI = map.getDefaultUI();\n';
        strHtml += '  customUI.maptypes.hybrid = false;\n';
        strHtml += '  map.setUI(customUI);\n';
    }
    strHtml += '  }\n';
    strHtml += '}\n';

		strHtml += 'function addLoadEvent(func) \n';
    strHtml += '{\n';
    strHtml += 'var oldonload = window.onload;\n';
    strHtml += '  if (typeof window.onload != \'function\') \n';
    strHtml += '  {\n';
    strHtml += '  window.onload = func;\n';
    strHtml += '  } \n';
    strHtml += '  else \n';
    strHtml += '  {\n';
    strHtml += '  window.onload = function() \n';
    strHtml += '    {\n';
    strHtml += '    if (oldonload) \n';
    strHtml += '    {\n';
    strHtml += '    oldonload();\n';
    strHtml += '    }\n';
    strHtml += '    func();\n';
    strHtml += '}\n';
    strHtml += '}\n';
    strHtml += '}\n';

		strHtml += 'addLoadEvent(load)\;\n';

    strHtml += 'if (window.attachEvent) {\n';
    strHtml += '  window.attachEvent("onunload", function() {\n';
    strHtml += '  GUnload();      // Internet Explorer\n';
    strHtml += '        });\n';
    strHtml += '} else {\n';
    strHtml += 'window.addEventListener("unload", function() {\n';
    strHtml += 'GUnload(); // Firefox and standard browsers\n';
    strHtml += '    }, false);\n';
    strHtml += '}\n';


		strHtml += '</script>\n';

    strHtml += '<div id="'+divnaam+'" style="background:#d3d3d3;width: '+width+'px; height: '+height+'px;" class="googlemaps_dummy">&nbsp;</div>\n';
		strHtml += '</span>\n';
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, strHtml);
		tinyMCEPopup.close();
		}
	}
};

tinyMCEPopup.onInit.add(ExampleDialog.init, ExampleDialog);
