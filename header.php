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
 <script type="text/javascript" src="/js/mootools-1.2.3-core-yc.js"></script>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/js/integrated.js"></script>
<script type="text/javascript" src="js/jquery/fancybox/jquery.fancybox-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/jquery/jquery.tipbox.js"></script>
<script>
	jQuery.noConflict();
</script>
 <script >
jQuery(document).ready(function(){
	jQuery("img.price1").tipbox("(<$10)");
	jQuery("img.price2").tipbox("($10-$50)");
	jQuery("img.price3").tipbox("($50-100$)");
	jQuery("img.price4").tipbox("($100-$200)");
	jQuery("img.price5").tipbox("($200-$500)");
	jQuery("img.price6").tipbox("($500-$1000)");
});
</script>
<!-- Google Translate -->
<script src="http://www.google.com/jsapi?key=ABQIAAAAV1hMY6P-vcrStESIcmxsyBQlNdnj7jTOpTnl4Nkq2CnWa36LSRSzhRIQvUz3LkcE_A5gHk7QVRq4gw"></script>									 
<script type="text/javascript">
	google.load("language", "1");
</script>
<link rel="stylesheet" type="text/css" href="/css/integrated.css" />
</head>

<body bgcolor="#D8D8D8">
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
	jQuery("a.iframe").fancybox({
		'frameWidth': 	800,
		'frameHeight': 	530
	});	
});
</script>