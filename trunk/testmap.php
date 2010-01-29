<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery/fancybox/jquery.fancybox-1.2.6.pack.js"></script>

<link rel="stylesheet" href="js/jquery/fancybox/jquery.fancybox-1.2.6.css" type="text/css" media="screen"/>
</head>

<body>
<a class="iframe" href="map.php">This goes to iframe</a>

<script language="javascript">
$(document).ready(function() {

	/* This is basic - uses default settings */
	
	$("a#single_image").fancybox();
	
	/* Using custom settings */
	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});

	$("a.group").fancybox({
		'zoomSpeedIn':		300, 
		'zoomSpeedOut':	300, 
		'overlayShow':		false
	});

	$("a.iframe").fancybox({
		'frameWidth': 	800,
		'frameHeight': 	530
	});
});
</script>	
</body>
</html>
