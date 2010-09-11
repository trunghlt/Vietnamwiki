<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<?php 
define("DEFAULT_DES", "VietnamWiki.net is an open encyclopedia where experienced travelers share
their knowledge about Vietnam under the supervision of VietnamWiki.net team. Every information
the user send to our website will be checked and confirmed before posted to official pages");

if (isset($_GET["id"])) {
	$postId = postIdFilter($_GET["id"]);
}
	
function getTitle() {
	global $postId;
	if (isset($postId)) {
		$post = new PostElement();
		$post->query($postId);
		return $post->title;
	}
	elseif (isset($_GET["index_id"])) {		
		$indexId = IndexElement::filterId($_GET["index_id"]);
		$index = new IndexElement();
		$index->query($indexId);
		
		$destElement = new DestinationElement();
		$destElement->query($index->destId);
		return $destElement->engName . " - " . $index->name;
	}
	else return "";
}

function getSummary() {
	global $postId;
	if (isset($postId)) {
		$post = new PostElement();
		$post->query($postId);
		return $post->summary;
	}	
	else {
		return DEFAULT_DES;
	}
}

?>

<title><?php echo getTitle();?></title>

<link rel="shortcut icon" href="/images/icon.png"/>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name = "KEYWORDS" 
content = "vietnam, Viet Nam, vietnamwiki, wiki, guide, travel, Sapa, Hanoi, 
Ha Noi, Hue, Hoian, Hoi an, Myson, My Son, Ha Long, halong, phong nha, ke bang,
Nha Trang, Nhatrang, Dalat, Da Lat, Mui ne, muine, Vung tau, Saigon, Sai Gon, 
Ho Chi Minh, Mekong delta, Phu Quoc"> 

<meta name="Description" 
content = "<?php echo getSummary()?>">

<meta name="verify-v1" content="IyUL1eYMgjAMGDWrAeniu500lWWLUCONXP+II/s3I2s=" />
    
<!--<script type="text/javascript" src="/js/mootools-1.2.3-core-yc.js"></script>-->
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/js/integrated.js"></script>
<script type="text/javascript" src="js/jquery/fancybox/jquery.fancybox-1.2.6.pack.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAV1hMY6P-vcrStESIcmxsyBT6xYYt3L8kyrregkxWzQhl2XmzkRRwJXeyUWjeICm6nYeVvYDtg1Br7Q" type="text/javascript"></script>
<script>
	jQuery.noConflict();
</script>
<script src="js/jquery.jcarousel.js" language="javascript"></script>
<link rel="stylesheet" href="js/jquery/fancybox/jquery.fancybox-1.2.6.css" type="text/css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/css/integrated.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie7/skin.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="css/tango/skin.css" />
<script type="text/javascript" src="js/jquery.jcarousel.js"></script>
<style>
/*******TIP WINDOW*******/
#tipWindow {
	position: absolute;
	width: 50px;
	height: 50px;
	left: 90%;
	top: 500px;
	border : 2px solid white;
	z-index: 51;
	opacity: 0.2;
    filter: alpha(opacity=20);
	background: #AAA;
    position: fixed;
}

 /******* TIPBOX *******/  
#tipBox{  
background: #f7fafb;  
 border: 1px solid #ace4ff;  
 font-size: 10px;  
 padding: 3px;  
 width: 180px;  
}  
#tipBox.blue{  
 color: #44a9da;  
}  
#tipBox.width{  
 width: auto;  
}  
#tipBox.big{  
 width: auto;  
 font-size: 40px;  
 line-height: 1em;  
 padding: 1em;  
}  
/******* /TIPBOX *******/ 
</style> 

<script type="text/javascript"><!--
var mapIcons = new Array();
mapDir = "/images/gmap/icons/";
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
    if (des && (des.trim() != "")) {
	    GEvent.addListener(marker, "click", function() {
		  //alert(des);
	      marker.openInfoWindowHtml(des);
	    });
    }
    return marker;
}

function load() 
{
  if (GBrowserIsCompatible())
  {
	  //mapDivs = document.getElementById("map");
	  mapDivs = document.getElementsByClassName("map");
	  for (i = 0; i < mapDivs.length; i++) {
		  var info = mapDivs[i].innerHTML.split(":")[1].split(",", 5);
		  var map = new GMap2(mapDivs[i]);
		  var center = new GLatLng(parseFloat(info[0]),parseFloat(info[1]));
		  map.setCenter(center, parseInt(info[3]));
		  map.addOverlay(new createMarker(center, info[2], info[4]));
		  var customUI = map.getDefaultUI();
		  customUI.maptypes.hybrid = false;
		  map.setUI(customUI);
	  }
  }
}
function addLoadEvent(func) 
{
var oldonload = window.onload;
  if (typeof window.onload != 'function') 
  {
  window.onload = func;
  } 
  else 
  {
  window.onload = function() 
    {
    if (oldonload) 
    {
    oldonload();
    }
    func();
}
}
}
addLoadEvent(load);
if (window.attachEvent) {
	window.attachEvent("onunload", function() {
		GUnload();      // Internet Explorer
	});
} else {
	window.addEventListener("unload", function() {
		GUnload(); // Firefox and standard browsers
    }, false);
}
// --></script>
</head>

<!--  BODY -->
<body id="body" "bgcolor="#D8D8D8">
<div id="grandWrapper">
<div id="tipWindow"></div>

<?php echo render_fbconnect_init_js();?>

<script language="javascript">
var mySlide = new Array();
var composeDialog, editDialog, loginDialog, invalidLoginDialog, 
	deleteConfirmDialog, commentDialog, photoUploadDialog, photoEditDialog, tendayWeatherDialog,
	restoreConfirmDialog, reviewDialog, mustRateAlert, reviewLowerBound;
var currentDestItem, currentIndexItem, currentMySlide, commentSlide;
var URL = "http://www.vietnamwiki.net";
<?php if ($onload_js) { echo $onload_js; } ?>
jQuery(document).ready(function(){
	jQuery("#tipWindow").mouseover(function() {jQuery("#main").fadeOut(1000);});
    jQuery("#tipWindow").mouseout(function() {jQuery("#main").fadeIn(1000);});
	jQuery("a.iframe").fancybox({
		'frameWidth': 	1000,
		'frameHeight': 	530
	});
	jQuery(".price1").tipbox("< $10");
	jQuery(".price2").tipbox("$10-$50");
	jQuery(".price3").tipbox("$50-$100");
	jQuery(".price4").tipbox("$100-$200");
	jQuery(".price5").tipbox("$200-$500");
	jQuery(".price6").tipbox("$500-$1000");
	
	var img = new Image();
	jQuery(img).load(function() {
		jQuery(this).addClass("fullBg");
		jQuery("#body").append(this);
		jQuery(this).css("display", "none");
		jQuery(this).fullBg();
		jQuery(this).fadeIn(500);
	}).attr("src", "http://imm.io/media/14/14ZH.jpg");
	
});
</script>	
<?php
//change_template
	function change_template(){
	$color = new Color;
	$r = $color->query_setting();
	if(count($r)){
		foreach($r as $value){
			$arr2 = explode('-',$value['page']);
			if($arr2[0] == 'view'){
					for($i = 1 ; $i < count($arr2);	$i++)
					{
						if($arr2[$i]=='top'){
							echo "<script>";
							//echo "document.getElementById('header').style.background=\"none\"";		
							echo "document.getElementById('header').style.background=\"$value[color]\"";
							echo "</script>";
						}
						if($arr2[$i]=='body'){
				echo "<script>";		
				echo "document.body.style.background=\"$value[color] url(../css/images/bg/bg.gif) repeat-y scroll center center\"";
				echo "</script>";		
						}
					}		
			}
		}
	}
}
?>
