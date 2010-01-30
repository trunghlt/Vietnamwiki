<?php            
require_once("core/common.php");
require_once("core/init.php");
require_once("core/classes/Db.php");
require_once("core/classes/DestinationElement.php");

$mapIcons = array();
$mapDir = "/images/gmap/icons/";
$mapIcons["restaurant"] = "restaurant.png";
$mapIcons["beach"] = "beach.png";
$mapIcons["bridge"] = "bridgemodern.png";
$mapIcons["shop"] = "shoppingmall.png";
$mapIcons["bank"] = "bank.png";
$mapIcons["hotel"] = "hotel.png";

$dest = new DestinationElement;
$dest->query(filterNumeric($_GET["id"]));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
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
function load() {
     if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("gmap"), { size:new GSize(800,500)});
		var latCoord = 16.003575733881323;
		<?php if (isset($dest->lat)) {?> latCoord=<?php echo $dest->lat?>;<?php } ?>
		
		var longCoord = 108.2373046875;
		<?php if (isset($dest->long)) {?> longCoord=<?php echo $dest->long?>;<?php } ?>
		
		var zoomlevel = 14;
		<?php if (isset($dest->zoomlevel)) {?> zoomlevel=<?php echo $dest->zoomlevel?>;<?php } ?>
		
		map.setCenter(new GLatLng(latCoord, longCoord), zoomlevel);
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

<div id="gmap" ></div>
<div style="margin-left: 150px; margin-top: 5px;">
	<div class="button" ><input type="text" id="search" size="50"/></div><div class="button"><a>Search</a></div>
	<div class="button" style="margin-left: 10px;"><a>Print</a></div>
</div>

</body>
</html>
