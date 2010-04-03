<?php            
require_once("core/common.php");
require_once("core/init.php");
require_once("core/classes/Db.php");
require_once("core/classes/DestinationElement.php");
require_once("core/classes/MapSpot.php");
$dest = new DestinationElement;
$dest->query(filterNumeric($_GET["id"]));
$mapSpotList = MapSpot::getMapSpotsByDestId($dest->id);
$mapmarkerList = MapSpot::query();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
#spots img{
	width:25px;
	height:25px;
}
#spots{
	font-size:10pt;
}
#spots ul{
	margin:0px;
	padding:0px;
	list-style-type:none;
}
#spots div{
	display:block;
	clear:left;
}
#spots .spotsimg{
	width:50px;
	height:50px;
	
}
div.button {
	float: left;
	position: relative;
	background-color : #D8DFEA;
}
	div.button a {
		border-color : #D8DFEA;
		border-style : solid solid none;
		border-width : 1px 1px 0;
		display : block;
		font-size : 13px;
		font-weight : bold;
		padding : 4px 12px;
		white-space : nowrap;
		color:#3B5998;
		cursor:pointer;
		outline-style:none;
		text-decoration:none;	
	}
	
	div.button a:hover {
		color:#FFFFFF;
		text-decoration:none;
		background-color:#627AAD;
		border-color:#627AAD;	
	}

#gmap {
	width: 800px; 
	height: 500px;
	}	
	
#search {
	height: 19px;
}

#addressBox {
	position: absolute;
	top: 275px;
	left: 0px;
	background: white;
	border: 1px solid #DDDDDD;
	padding: 5px 5px;
	z-index: 1000;
	opacity: 0.8;
	}	

select.icon-menu option {
	background-repeat: no-repeat;
	background-position: bottom left;
	padding-left: 40px;
	line-height: 40px;
	height: 40px;
}
</style>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAV1hMY6P-vcrStESIcmxsyBSg0YMtASE5KdM7LALqADHM9SZ_PBTZqozQ8fKlIDHry-cBnAxWYeYpSw" type="text/javascript"></script>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
//<![CDATA[
if (window.attachEvent) {
	window.attachEvent("onunload", function() {
		GUnload();      // Internet Explorer
	});
} else {
	window.addEventListener("unload", function() {
		GUnload(); // Firefox and standard browsers
    }, false);
}
var mapIcons = new Array();
mapDir = "images/gmap/icons/";
mapIcons["others"] = "marker.png";
mapIcons["restaurant"] = "restaurant.png";
mapIcons["beach"] = "beach.png";
mapIcons["bridge"] = "bridgemodern.png";
mapIcons["shop"] = "shoppingmall.png";
mapIcons["bank"] = "bank.png";
mapIcons["hotel"] = "hotel.png";

function createMarker(point, cat, des) {
	var vnwkIcon = new GIcon();
	vnwkIcon.image = mapDir + mapIcons[cat];
	vnwkIcon.shadowSize = new GSize(0, 0);
	if (cat == "others") {
		vnwkIcon.iconAnchor = new GPoint(10, 24);
		vnwkIcon.iconSize = new GSize(32, 32);
	}
	else {
		vnwkIcon.iconAnchor = new GPoint(16, 37);
		vnwkIcon.iconSize = new GSize(32, 37);
	}
	vnwkIcon.infoWindowAnchor = new GPoint(20, 10); 	
    var marker = new GMarker(point, {icon: vnwkIcon, draggable: false});
    if (des!='') {
	    GEvent.addListener(marker, "click", function() {
		  marker.openInfoWindowHtml(des);
	    });
    }
    return marker;
}
           

function htmlspecialchars_decode (string, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: Mirek Slugen
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Mateusz "loonquawl" Zalega
    // +      input by: ReverseSyntax
    // +      input by: Slawomir Kaniecki
    // +      input by: Scott Cariss
    // +      input by: Francois
    // +   bugfixed by: Onno Marsman
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Ratheous
    // +      input by: Mailfaker (http://www.weedem.fr/)
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
    // *     returns 1: '<p>this -> &quot;</p>'
    // *     example 2: htmlspecialchars_decode("&amp;quot;");
    // *     returns 2: '&quot;'

    var optTemp = 0, i = 0, noquotes= false;
    if (typeof quote_style === 'undefined') {
        quote_style = 2;
    }
    string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE' : 1,
        'ENT_HTML_QUOTE_DOUBLE' : 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE' : 4
    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i=0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;
            }
            else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }
        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
        // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
    }
    if (!noquotes) {
        string = string.replace(/&quot;/g, '"');
    }
    // Put this in last place to avoid escape being double-decoded
    string = string.replace(/&amp;/g, '&');

    return string;
}

function showAddress(address)
{
	var map = null;
	var geocoder = null;
		name_address = "<?php echo $dest->engName?>";
		if(name_address=="Phong Nha - Ke Bang"){
			name_address = "Phong Nha";
		}
		if(name_address=="Hoi An - My Son"){
			name_address = "Hoi An";
		}
		address = address +", "+ name_address +", Vietnam";

	map = new GMap2(document.getElementById("gmap"));
	var latCoord = 16.003575733881323;
	<?php if (isset($dest->lat)) {?> latCoord=<?php echo $dest->lat?>;<?php } ?>
		
	var longCoord = 108.2373046875;
	<?php if (isset($dest->long)) {?> longCoord=<?php echo $dest->long?>;<?php } ?>
		
	var zoomlevel = 14;
	<?php if (isset($dest->zoomlevel)) {?> zoomlevel=<?php echo $dest->zoomlevel?>;<?php } ?>
	map.setCenter(new GLatLng(latCoord, longCoord), zoomlevel);
	
	geocoder = new GClientGeocoder();
	if (geocoder)
	{
	  geocoder.getLatLng(address,
	          function(point) {
	            if (!point) {
	              alert(address + " not found");
	            } else {
					map.setCenter(point, 13);
					var customUI = map.getDefaultUI();
					customUI.maptypes.hybrid = false;
					map.setUI(customUI);
					var marker = createMarker(point, "others");
					map.addOverlay(marker);
					
					<?php foreach ($mapmarkerList as $ms1) {	?>					
					des = htmlspecialchars_decode("<?php echo str_replace("\n", "", $ms1['des'])?>", "ENT_QUOTES");	
					map.addOverlay(createMarker(new GLatLng(<?php echo $ms1['latCoord']?>, <?php echo $ms1['longCoord']?>), "<?php echo $ms1['cat']?>", des));
					<?php } ?>
	            }
	          }
	   );
	}

}

function load() {
     if (GBrowserIsCompatible()) {
	 	var spots ="<ul>";
		map = new GMap2(document.getElementById("gmap"), { size:new GSize(800,500)});
		var latCoord = 16.003575733881323;
		<?php if (isset($dest->lat)) {?> latCoord=<?php echo $dest->lat?>;<?php } ?>
		
		var longCoord = 108.2373046875;
		<?php if (isset($dest->long)) {?> longCoord=<?php echo $dest->long?>;<?php } ?>
		
		var zoomlevel = 14;
		<?php if (isset($dest->zoomlevel)) {?> zoomlevel=<?php echo $dest->zoomlevel?>;<?php } ?>
		map.setCenter(new GLatLng(latCoord, longCoord), zoomlevel);

		<?php foreach ($mapSpotList as $ms) {	?>
		des = htmlspecialchars_decode("<?php echo str_replace("\n", "", $ms->des)?>", "ENT_QUOTES");	
		map.addOverlay(createMarker(new GLatLng(<?php echo $ms->lat?>, <?php echo $ms->long?>), "<?php echo $ms->cat?>", des));
		str = mapDir + mapIcons['<?php echo $ms->cat?>'];
		spots += "<li><div><img src='"+str+"' class='spotsimg' align='left'/>"+des+"</div></li>";
		<?php } ?>
		if(spots !=""){
			spots +="</ul>";
			document.getElementById("spots").innerHTML = spots;
		}
		var customUI = map.getDefaultUI();
        customUI.maptypes.hybrid = false;
        map.setUI(customUI)
		map.enableContinuousZoom();
		map.enableDoubleClickZoom();
     }
}
//]]>
</script>	
</head>
<body onload="load()" onunload="GUnload()" style="margin: 0; padding: 0;">
<div>
<div id='spots' style="float:right; width:200px;"><!-- --></div>
<div id="gmap" ></div>
</div>
<div style="margin-left: 150px; margin-top: 5px;">
	<div class="button" ><input type="text" id="search" size="50"/></div><div class="button" onclick="showAddress(document.getElementById('search').value);return false;"><a>Search</a></div>
	<div class="button" style="margin-left: 10px;" onclick="window.print(); return false;"><a>Print</a></div>
</div>

</body>
</html>
