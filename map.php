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

#map {
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
</style>

 
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAV1hMY6P-vcrStESIcmxsyBSg0YMtASE5KdM7LALqADHM9SZ_PBTZqozQ8fKlIDHry-cBnAxWYeYpSw" type="text/javascript"></script> 
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
//<![CDATA[
var map;
var vnwkIcon = new GIcon();
//icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
//icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
	vnwkIcon.image = "/images/marker.png";
	vnwkIcon.iconSize = new GSize(32, 32);
	vnwkIcon.shadowSize = new GSize(0, 0);
	vnwkIcon.iconAnchor = new GPoint(10, 24);
	vnwkIcon.infoWindowAnchor = new GPoint(20, 10);   

function createMarker(point,args) {
    var marker = new GMarker(point, args);
    GEvent.addListener(marker, "click", function() {
      marker.openInfoWindowHtml(tinyMCE.get('description').getContent());
    });
    return marker;
  }


function showAddress(address)
{
	var map = null;
	var geocoder = null;
	map = new GMap2(document.getElementById("map"));
	map.setCenter(new GLatLng(21.0271485985374,105.85206985473633), 14);
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
					map.setUI(customUI)
					var marker = createMarker(point, {icon:vnwkIcon, draggable: true});
					map.addOverlay(marker);
					marker.enableDragging();
					GEvent.addListener(marker, "drag", function(){
						document.getElementById("coords").value=marker.getPoint().lat()+","+marker.getPoint().lng();
						document.getElementById("zoomlevel").value = map.getZoom();
					});
					
					document.getElementById("coords").value=marker.getPoint().lat()+","+marker.getPoint().lng();
					document.getElementById("zoomlevel").value = map.getZoom();
	            }
	          }
	        );
	}

}

function load() {
	   	   
     if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		map.setCenter(new GLatLng(16.003575733881323,108.2373046875), 14);
//		map.addControl(new GMapTypeControl(2));
//		map.addControl(new GLargeMapControl());
		 var customUI = map.getDefaultUI();
        // Remove MapType.G_HYBRID_MAP
        customUI.maptypes.hybrid = false;
        map.setUI(customUI)

		map.enableContinuousZoom();
		map.enableDoubleClickZoom();

		// "tiny" marker icon


		/////Draggable markers
		var point = new GLatLng(21.0271485985374,105.85206985473633);
		var markerD2 = createMarker(point, {icon:vnwkIcon, draggable: true});
		map.addOverlay(markerD2);

		markerD2.enableDragging();

		GEvent.addListener(markerD2, "drag", function(){
			document.getElementById("coords").value=markerD2.getPoint().lat()+","+markerD2.getPoint().lng();
			document.getElementById("zoomlevel").value = map.getZoom();
		});

		document.getElementById("coords").value=markerD2.getPoint().lat()+","+markerD2.getPoint().lng();
		document.getElementById("zoomlevel").value = map.getZoom();
     }
}
//]]>
</script>	
</head>
<body onload="load()" onunload="GUnload()" style="margin: 0; padding: 0;">

<div id="addressBox">
<form>
<table>
<tbody>
	<tr><td>Coordinates:</td><td><input type="text" size=20 id="coords" value=""/></td></tr>
	<tr><td>Address:</td><td><input type="text" size=20 value=""/></td></tr>
	<tr><td>Description</td><td><textarea cols="20" rows="2" id="description" name="description"></textarea></td></tr>
	<tr><td></td><td><input type="submit" value="Add"/></td></tr>
</tbody>		
</table>
</form>
</div>

<div id="map" ></div>
<div style="margin-left: 100px; margin-top: 5px;">
	<div class="button" ><input type="text" id="search" size="50"/></div><div class="button"><a>Search</a></div>
	<div class="button" style="margin-left: 10px;" onclick="$('#addressBox').toggle()"><a>+ Add a new location</a></div>
	<div class="button" style="margin-left: 10px;"><a>Print</a></div>
</div>

<script language="javascript">
tinyMCE.init({
	// General options
	mode : "exact",
	elements: "description",
	theme: "simple"
});

$(document).ready(function(){
	$("#addressBox").hide();	
});

</script>


</body>
</html>
