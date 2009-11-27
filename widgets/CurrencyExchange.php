<?php
//code for the Currency Exchange Calculator
$rqst=$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
$url="http://www.onyoursite.com/data/cxc.htp?rqst=".$rqst;
$excCalContent = file_get_contents($url);

//resize the module
$findPattern = '/width="200"/ismU';
$replacePattern= 'width="100%"';
$excCalContent = preg_replace($findPattern, $replacePattern, $excCalContent);
$findPattern = '/200px/';
$replacePattern= '100%';
$excCalContent = preg_replace($findPattern, $replacePattern, $excCalContent);

//delete annoying border
$delPattern = '/2px/';
$excCalContent = preg_replace($delPattern, "border: 0px", $excCalContent); 

//delete annoying arrows
$delPattern = '/<tr><td\salign="center"\sstyle="border-collapse:collapse;border:0;">.*<\/tr>/ismU';
$excCalContent = preg_replace($delPattern, "", $excCalContent); 

echo $excCalContent;
?>
<script language="javascript">
//set default values
document.getElementsByName("org")[0].value = "USD";
document.getElementsByName("des")[0].value = "VND";
</script>
