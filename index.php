<?php
include('core/common.php');
include('core/init.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>VietnamWiki.net - A complete travel guide to Vietnam</title>

<style type="text/css">
body {
	background-color: #8C0003;
	font-family: Arial,Helvetica,serif;
	font-size: 10px;
	padding: 0px;
	margin: 0px;
}
.style2 {font-weight: bold}
.style4 {color: #FFFFFF}
.style5 {color: #00CCFF}
.style6 {color: #00FFFF}

.logo {
	font-size: 2.6em;
	font-weight: bold; 
	font-family: Arial,Helvetica,serif;
	color: #FFFFFF;
	cursor:default;
	font-family: Arial,Helvetica,serif;
}

#slideButtonLeft {
	background: url(/images/vnwkImageMap.png) -360px 0px no-repeat; 
	width: 26px; 
	height: 125px;
}

#slideButtonLeftActive {
	background: url(/images/vnwkImageMap.png) -360px -130px no-repeat; 
	width: 26px; 
	height: 125px; 
	display: none;
}

#top {
	width: 100%;
	height: 68px;
	overflow: hidden;
	padding-top: 10px;
	margin-top: -10px; 
	margin-bottom: 20px; 
	background: #660000;
	border-bottom: 2px solid #550000;
}

#realTop {
	position: absolute;
	width: 100%;
	padding-top: 13px;
}

	#realTop #searchForm {
		margin-top: 0px;
		font-size: 13px; 
		margin-left:10px; 
		width: 600px;
		height: 28px; 
		vertical-align: middle; 
		float: middle;
		background: #FFCCFF;
		border: 1px solid rgb(180, 180, 180);
		vertical-align: middle;
	}

		#realTop #searchForm #searchButton {
			font-size: 15px; 
			font-familty: Arial,Helvetica,serif;
			width: 80px;
			text-align: center;		
		}

#slideShowWrapper {
	background: #440000;
	margin-left: -10px;
	height: 100px;
	width: 1390px;
	overflow: hidden;	
}

	#slideShowWrapper div {
		float: left;
		margin-left: 2px;
		margin-top: 2px;
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

<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="css/index_ie.css" />
<![endif]-->

<link rel="shortcut icon" href="/images/icon.png"/>

<script type='text/javascript' src='js/slideshow.js'> </script>
<script type='text/javascript' src='js/graph.js'> </script>
<script type='text/javascript' src='js/corner/cvi_corner_lib.js'> </script>
<script type="text/javascript" src="js/jquery/jquery-1.2.6.js"></script>
<script type="text/javascript" src="js/jquery/jquery.tipbox.js"></script>
<script type="text/javascript" src="js/shortcut.js"></script>
</head>


<body>

<script language="JavaScript1.2">

jQuery().ready(function(){
	jQuery("span.logo").tipbox("a Wiki means an <strong>open encyclopedia</strong> - where every experienced traveller can edit the content with an account. Press: <ul style=' margin:0 0 0 0'><li><span style='color: red'><strong>Ctrl+C</strong></span> to create a new account</li></ul>", 1);
	shortcut.add("Ctrl+C",function() {
		window.location = "signup.php";
	});		
});


var IE = document.all?true:false

// If NS -- that is, !IE -- then set up for mouse capture
if (!IE) document.captureEvents(Event.MOUSEMOVE)
document.onmouseover = getMouseXY;

var tempX = 0
var tempY = 0

// Main function to retrieve mouse x-y pos.s

function getMouseXY(e) {
  if (IE) { // grab the x-y pos.s if browser is IE
    tempX = event.clientX + document.body.scrollLeft
    tempY = event.clientY + document.body.scrollTop
  } else {  // grab the x-y pos.s if browser is NS
    tempX = e.pageX
    tempY = e.pageY
  }  
  // catch possible negative values in NS4
  if (tempX < 0){tempX = 0}
  if (tempY < 0){tempY = 0}  
  // show the position values in the form named Show
  // in the text fields named MouseX and MouseY

  return true
}


Cupid=new Image();
Cupid.src="";  //specify path to Cupid image
Xpos=700;  //cupids x coordinates, in pixel
Ypos=200;  //cupids y coordinates, in pixel
step=0.02; //Animation speed (smaller is slower)
dismissafter=10;  //seconds after which Cupids should disappear, in seconds
var flying = false;

var imgs = [];
var dest_ids = new Array();
var i = 0;
var j = 0;
</script>
<?php
	$sql = "SELECT id
			FROM destinations";
	$re = mysql_query($sql) or die(mysql_error());
	
	$i = 0;
	while ($r = mysql_fetch_array($re)) {
		?>
		<?php
		$dest_id = $r["id"];
		$sql = "SELECT *
				FROM map_images
				WHERE dest_id = $dest_id";
		$re2 = mysql_query($sql) or die(mysql_error());
		$j = 0;
		?>
		<script language="javascript">
		imgs[<?php echo $i?>] = [];
		dest_ids[<?php echo $i?>] = <?php echo $dest_id ?>;
		</script>		
		<?php
		while ($r2 = mysql_fetch_array($re2)) {
			?>
			<script language="javascript">
			<?php		
			echo "imgs[".$i."][".$j."] = '".$r2["URL"]."';";
			?>			
			</script>
			<?php
				echo "<img id='img".$i."_".$j."' SRC='".$r2["URL"]."' style='position:absolute;visibility:hidden;top:-200;z-index:+2;' width=69 height=60 border=0 />";			
			$j++;
		}
		$i++;
	}
?>
<script language="javascript">
if(!Array.indexOf){
	Array.prototype.indexOf = function(obj){
		for(var i=0; i<this.length; i++){
			if(this[i]==obj){
				return i;
			}
		}
		return -1;
	}
}


function init_images(id) {
	var k = dest_ids.indexOf(id);
	var e;
	amount = imgs[k].length;
	for (i=0; i < amount; i++) 	{
		e = document.getElementById("img"+k+"_"+i);
		cvi_corner.add(e, {radius: 50, shade: 33});
	}

}

function initImageSerie() {
	init_images(23);//Sapa
	init_images(9);//Hue
	init_images(6); //Halong
	init_images(11); //Hoi An
	init_images(12); //Nha Trang
	init_images(26); //Mekong
}

initImageSerie();
yBase=xBase=currStep=a_count=0;
b_count=1;
c_count=2;
d_count=3;
move=1;
var flycupid = 0;
R = 80;
ir = 0;
tcount = 0;

function disappear_cupid(id) {
	var k = dest_ids.indexOf(id);
	for (i in imgs[k]) {
		var e = document.getElementById("img"+k+"_"+i);
		if (e != null) { 
			e.style.visibility = "hidden";
		}
	}
}

function appear_cupid(id) {
	var k = dest_ids.indexOf(id);
	for (i in imgs[k]) {
		var e = document.getElementById("img"+k+"_"+i);
		if (e != null) { 
			e.style.visibility = "visible";
		}
	}
}


function dismisscupid(id){
	flying = false;
	clearInterval(flycupid)
	disappear_cupid(id);
}

function Animate(id){
	var k = dest_ids.indexOf(id);
	var Angle = 2*Math.PI/imgs[k].length;
	var e;
	currStep+=step;
	ir+=3;
	if (ir > R) { ir = R }
	for (i in imgs[k]) {
		  e = document.getElementById("img"+k+"_"+i);
		  if (e != null) {
		  	e.style.top = tempY - 20 + R*Math.sin(i*Angle+currStep)
		  	e.style.left =tempX - 20 + R*Math.cos(i*Angle+currStep)
		  }
	  }
	
}

function do_animate(id, dest_id) {
	//--------Set at mouse position
	if (!flying) {
		Xpos = tempX;
		Ypos = tempY;
		appear_cupid(dest_id);
		flycupid=setInterval('Animate('+dest_id+')',30);
	}
	flying = true;
	return true;
}

function cen(a ,b) {
	return (a + b)/2;
}

//-->
</script><!--webbot bot="HTMLMarkup" startspan -->


<!----------------SEARCH-------------------- -->
<div align="center" id="realTop">
	<span class="logo">VietnamWiki.net</span>
	<span style="font-size:1.6em; color: yellow; cursor: default;">&#223;eta</span>
	<form id="searchForm" action="search_display.php" id="search">
		<input id="search_text" name="search_text" size=70 type="text"/>
		<input type="button" id="searchButton" value="Search" id="search"/>
	</form>
</div>
<div id="top"></div>
<!-------------------------------------------->

<!-- ---------------------------MAP------------------- -->
<div align="center" style="margin-top: -10px;">
	<table width="843">
	<tr>	
	<td width="263"><p class="style4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Capital: <a href="http://www.vietnamwiki.net/viewtopic.php?id=29" class="style5">Hanoi</a></p>
	  <p class="style4">&nbsp;&nbsp;&nbsp;Biggest city: <a href="http://www.vietnamwiki.net/Ho_Chi_Minh-General-Ho_Chi_Minh_general_information-P11" class="style6">Ho Chi Minh</a></p>
	  <p class="style4">&nbsp;Language:  Vietnamese</p>
	  <p class="style4">Area: <a href="http://en.wikipedia.org/wiki/1_E9_m%C2%B2" title="1 E9 m&sup2;" class="style6">331,689&nbsp;km&sup2;</a>&nbsp;(<a title="List of countries and outlying territories by area" href="http://en.wikipedia.org/wiki/List_of_countries_and_outlying_territories_by_area" style="color:#00FFFF">65th</a>)</p>
	  <p class="style4">Population: 87,375,000&nbsp;(<a title="List of countries by population" href="http://en.wikipedia.org/wiki/List_of_countries_by_population" style="color:#00FFFF">13th</a>) </p>
	  <p class="style4">GDP: $251.8 billion&nbsp;(<a title="List of countries by GDP (PPP)" href="http://en.wikipedia.org/wiki/List_of_countries_by_GDP_%28PPP%29" style="color:#00FFFF">36th</a>) </p>
	  <p class="style4">&nbsp;&nbsp;Currency: <span class="style6" lang="vi" xml:lang="vi"><a title="Vietnamese &#273;&#7891;ng" href="http://en.wikipedia.org/wiki/Vietnamese_%C4%91%E1%BB%93ng" style="color:#00FFFF">&#273;&#7891;ng</a> (&#8363;) (<a title="ISO 4217" href="http://en.wikipedia.org/wiki/ISO_4217" style="color:#00FFFF">VND</a>)</span></p>
	  <p class="style4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time Zone: (UTC +7) </p></td>
	<td width="355">
		<div style="position: relative;">
			<div usemap = "#map" style="width: 355px; height: 487px; overflow: hidden;">
				<img usemap="#map" src="images/vnwkImageMap.png" style="border: none;"/>
			</div>
		</div>
		<MAP NAME = "map">
		<AREA id='sapa' onMouseOver="do_animate('sapa', 23)" onMouseOut="dismisscupid(23)" SHAPE = "RECT" COORDS = " 119,  28,  156,  45" HREF = "/Sapa-General-I100">
		<AREA SHAPE = "RECT" COORDS = " 165,  58,  206,  73" HREF = "viewtopic.php?id=29">
		<AREA id='halong' onMouseOver="do_animate('halong', 6)" onMouseOut="dismisscupid(6)" SHAPE = "RECT" COORDS = " 206,  75,  269,  91" HREF = "/Ha_Long-General-I62">
		<AREA id='hue' onMouseOver="do_animate('hue',9)" onMouseOut="dismisscupid(9)" SHAPE = "RECT" COORDS = " 221,  217,  255,  234" HREF = "/Hue-General-I97">
		<AREA id='hoian' onMouseOver="do_animate('hoian', 11)" onMouseOut="dismisscupid(11)" SHAPE = "RECT" COORDS = " 267,  248,  309,  266" HREF = "/Hoi_An_-_My_Son-General-I56">
		<AREA id='nhatrang' onMouseOver="do_animate('nhatrang', 12)" onMouseOut="dismisscupid(12)" SHAPE = "RECT" COORDS = " 286,  342,  336,  363" HREF = "/Nha_Trang-General-I91">
		<AREA SHAPE = "RECT" COORDS = " 252,  365,  291,  380" HREF = "http://www.vietnamwiki.net/Da_Lat-General-Da_Lat__City_of_Eternal_Spring-P17">
		<AREA SHAPE = "RECT" COORDS = " 206,  404,  334,  419" HREF = "http://www.vietnamwiki.net/Ho_Chi_Minh-General-Ho_Chi_Minh_general_information-P11">
		<AREA id='mekong' onMouseOver="do_animate('mekong', 26)" onMouseOut="dismisscupid(26)"  SHAPE = "RECT" COORDS = " 150,  435,  193,  461" HREF = "/Mekong_delta-General-I117">
		<AREA SHAPE = "DEFAULT" NOHREF>
		</MAP>	
		<div align="center">
			<a style="color:#00FFFF; color: yellow;" href="/Vietnam_general-General-Vietnam-P32">I don't know where to go ?!</a>
		</div>
		</td>
	<td width="263">
		<p align="right" class="style4"> <span class="style6"><a href="index2.php" style="color:#00FFFF">Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
		<p align="right" class="style4 style6"> <a href="index2.php?index_id=39" style="color:#00FFFF">Passport & Visa&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p align="right" class="style4 style6"><a href="index2.php?index_id=58" style="color:#00FFFF">Traddition &amp; Culture</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p align="right" class="style4 style6"><a href="index2.php?index_id=116" style="color:#00FFFF">Travel information</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p align="right" class="style4 style6"><a href="index2.php?index_id=123" style="color:#00FFFF">Food&nbsp;</a>&nbsp;&nbsp;&nbsp;</p>
		<p align="right" class="style4 style6"><a href="index2.php?index_id=59" style="color:#00FFFF">Currency</a>&nbsp;&nbsp;&nbsp;</p>
		<p align="right" class="style4 style6"><a href="index2.php?index_id=61" style="color:#00FFFF">Climate&nbsp;</a>&nbsp;&nbsp;</p>
		<p align="right" class="style4 style6"><a href="index2.php?index_id=60" style="color:#00FFFF">Holiday</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p align="right" class="style4 style6"><a href="index2.php?index_id=43" style="color:#00FFFF">History&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<p align="right" class="style4"><span class="style6"><a href="photo.php?dest_id=3&amp;page=1" style="color:#00FFFF">Photos</a>&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
	</tr>
	<tr>
	</tr>
  </table>
</div>   
  
<!-- ------------------------SLIDE SHOW---------------- -->
<div style="width: 100%" align="center">
	<div id="slideShowWrapper">
		<div><script>new fadeshow(fadeimages1, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages2, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages3, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages4, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages5, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages6, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages7, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages1, 150, 90, 0, 5000, 1, 'R')</script></div>
		<div><script>new fadeshow(fadeimages2, 150, 90, 0, 5000, 1, 'R')</script></div>
	</div>
</div>
<!-- Feedback form -->
<?php 
include("feedback.php");
include("googleAnalytical.php");
?>
</span>

</body>
</html>
