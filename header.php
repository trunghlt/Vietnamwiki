<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<?php 

function getTitle() {
	if (isset($_GET["id"])) {
		$postId = postIdFilter($_GET["id"]);
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
content = "VietnamWiki.net is an open encyclopedia where experienced travelers share
their knowledge about Vietnam under the supervision of VietnamWiki.net team. Every information
the user send to our website will be checked and confirmed before posted to official pages">

<meta name="verify-v1" content="IyUL1eYMgjAMGDWrAeniu500lWWLUCONXP+II/s3I2s=" />
    
<script type="text/javascript" src="js/mootools-1.2.3-core-yc.js"></script>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/js/integrated.js"></script>


<script>
	jQuery.noConflict();
</script>

<!-- Google Translate -->
<script src="http://www.google.com/jsapi?key=ABQIAAAAV1hMY6P-vcrStESIcmxsyBQlNdnj7jTOpTnl4Nkq2CnWa36LSRSzhRIQvUz3LkcE_A5gHk7QVRq4gw"></script>									 
<script type="text/javascript">
	google.load("language", "1");
</script>

<link rel="stylesheet" type="text/css" href="/css/integrated.css" />
     
<script language="javascript">
var mySlide = new Array();
var composeDialog, editDialog, loginDialog, invalidLoginDialog, 
	deleteConfirmDialog, commentDialog, photoUploadDialog, photoEditDialog, tendayWeatherDialog,
	restoreConfirmDialog, reviewDialog, mustRateAlert, reviewLowerBound;
var currentDestItem, currentIndexItem, currentMySlide, commentSlide;
var URL = "http://www.vietnamwiki.net";
</script>
</head>
 <body bgcolor="#D8D8D8">
 
<?php echo render_fbconnect_init_js();?>
<?php if ($onload_js) { ?>
	<script type="text/javascript">
		<?php echo $onload_js ;?>
	</script>
<?php } ?>