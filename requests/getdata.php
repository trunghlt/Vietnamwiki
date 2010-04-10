<?php
function getdata($url) 
{
	$url = fopen($url, "r");
	$data = stream_get_contents($url);
	fclose($url);
	$info=substr($data,0,-1);

		$info = str_replace("/images","http://www.vietnamwiki.net/images",$info);
		$info = str_replace("images","http://www.vietnamwiki.net/images",$info);
	return $info;
} 
?>
