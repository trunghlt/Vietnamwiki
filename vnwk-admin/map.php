<?php
include("../core/classes/DestinationElement.php");
include ("projax/projax.php");
include("db.php");
include("common.php");
include("session.php");
session_start();
process(session_id(), myip());

if (!logged_in()) header("location: login.php");
$q = new db;
$id = @$_GET["id"];
$add = @$_POST["Add"];
$URL = @$_POST["URL"];
$del_id = @$_GET["del_id"];

if (isset($id)) {
	if (isset($_POST["lat"]) && isset($_POST["long"]) && isset($_POST["zoomlevel"])) {
		$dest = new DestinationElement;
		$dest->id = $id;
		$dest->setMapDetail($_POST["lat"], $_POST["long"], $_POST["zoomlevel"]);
	}
	
	if ($add) {
		$sql = "INSERT INTO map_images
				(dest_id, URL)
				VALUE($id, '$URL')";
		
		$q->query($sql);			
	}
	
	if ($del_id) {
		$sql = "DELETE
				FROM map_images
				WHERE id=$del_id";
		
		$q->query($sql);
	}
	
	$sql = "SELECT *
			FROM map_images
			WHERE dest_id = ($id)";
	
	$q->query($sql);
	
	$dest = new DestinationElement;
	$dest->query($id);
?>
<!--Confirm-->
<script type="text/javascript" language="javascript">
	function confir(id,del_id){
		if(window.confirm('Do You want delete this destination?'))
		{
			location.href = "map.php?id="+id+"&del_id="+del_id;
		}
	}
</script>

<html>
<head>
<link href='admin.css' rel='stylesheet' type='text/css' />
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAV1hMY6P-vcrStESIcmxsyBSg0YMtASE5KdM7LALqADHM9SZ_PBTZqozQ8fKlIDHry-cBnAxWYeYpSw" type="text/javascript"></script>	
<script type="text/javascript">
//<![CDATA[
var map;
var vnwkIcon = new GIcon();
//vnwkIcon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
//icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
vnwkIcon.image = "../images/marker.png";
vnwkIcon.iconSize = new GSize(32, 32);
vnwkIcon.shadowSize = new GSize(0, 0);
vnwkIcon.iconAnchor = new GPoint(10, 24);
vnwkIcon.infoWindowAnchor = new GPoint(20, 10);   

function createMarker(point,args) {
    var marker = new GMarker(point, args);
    /*
    GEvent.addListener(marker, "click", function() {
      marker.openInfoWindowHtml(tinyMCE.get('description').getContent());
    });
    */
    return marker;
  }


function load() {
	   	   
     if (GBrowserIsCompatible()) {
 		var latCoord = 16.003575733881323;
		<?php if (isset($dest->lat)) {?> latCoord=<?php echo $dest->lat?>;<?php } ?>
		var longCoord = 108.2373046875;
		<?php if (isset($dest->long)) {?> longCoord=<?php echo $dest->long?>;<?php } ?>
		var zoomlevel = 14;
		<?php if (isset($dest->zoomlevel)) {?> zoomlevel=<?php echo $dest->zoomlevel?>;<?php } ?>

		var map = new GMap2(document.getElementById("gmap"));
		map.setCenter(new GLatLng(latCoord,longCoord), zoomlevel);
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
		var point = new GLatLng(latCoord, longCoord);
		var markerD2 = createMarker(point, {icon:vnwkIcon, draggable: true});
		map.addOverlay(markerD2);

		markerD2.enableDragging();

		GEvent.addListener(markerD2, "drag", function(){
			document.getElementById("lat").value=markerD2.getPoint().lat();
			document.getElementById("long").value=markerD2.getPoint().lng();
		});

		GEvent.addListener(map, "zoomend", function() {
			document.getElementById("zoomlevel").value = map.getZoom();
		});

		document.getElementById("lat").value=markerD2.getPoint().lat();
		document.getElementById("long").value=markerD2.getPoint().lng();
		document.getElementById("zoomlevel").value = map.getZoom();

		
     }
}
//]]>
</script>
</head>
<body id='map' onload="load()" onunload="GUnload()" style="margin: 0; padding: 0;">
	<?php		
	While ($r = mysql_fetch_array($q->re)) {
		echo "<a href='#' onclick='confir(".$id.",".$r["id"].");'>(x)</a>";
		echo "<img src='".$r["URL"] . "'/></br>";
	}
	?>
	<form action="map.php?id=<?php echo $id?>" method="post">
		URL: <input id="URL" style="float:left" type="input" name="URL" />
		<input id="Add" name="Add" style="float:left" type="submit" value="Add" />
	</form>
	<div id="gmap" style="width: 800px; height: 500px;"></div>
	<form action="map.php?id=<?php echo $id?>" method="post">
		<label>Lat: </label><input type="text" id="lat" name="lat"/>	
		<label>Long: </label><input type="text" id="long" name="long"/>	
		<label>Zoom level: </label><input type="text" id="zoomlevel" name="zoomlevel"/>
		<input type="submit" id="updateMap" name="updateMap" value="update"/> 
	</form>
</body>
</html>
<?php 
return;
}
?>
<html>
<head>
<script src="projax/js/prototype.js" type="text/javascript"></script>
<script src="projax/js/scriptaculous.js" type="text/javascript"></script>
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>
<body id="map">

<?php
	$sql = "SELECT * 
			FROM destinations
			ORDER BY ord";
			
	$q->query($sql);	
?>
<div id="dest">
<h3>Destinations</h3>
<ul id="list">
	<?php 
	$i = 0;
	while ($r = mysql_fetch_array($q->re)) {	?>
		<li id="item_<?php echo $i?>">
			<?php echo $r["ord"]?>
			<?php echo $r["EngName"]?>
			<a href="map.php?id=<?php echo $r['id']?>" target="mostright">(i)</a>
		</li>
		<?php 
		$i++;
	} ?>
</ul>

</body>
</html>
