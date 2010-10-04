<?php
function getdata($url, $start, $stop, $str_to_replace='', $str_replace='', $extra_data='',$str_to_replace1='', $str_replace1='') 
{
$fd = ""; 
$start_pos = $end_pos = 0;
$url = fopen($url, "r");
while(true) {
if($end_pos > $start_pos) {
$result = substr($fd, $start_pos, $end_pos-$start_pos);
$result .= $stop;
break;
}
$data = fread($url, 8192);

if(strlen($data) == 0) break;
$fd .= $data;
if(!$start_pos) $start_pos = strpos($fd, $start);
if($start_pos) $end_pos = strpos(substr($fd, $start_pos), $stop) + $start_pos;
}
fclose($url);

	//$info=str_replace($str_to_replace, $str_replace, $extra_data.$result);
	//$info=str_replace($str_to_replace1, $str_replace1, $info);
	
	$info=str_replace('krpano.swf', 'http://hanoi1000.vn/krpano.swf', $result);
	$info=str_replace('gigapixel.xml', 'http://hanoi1000.vn/gigapixel.xml', $info);
        $info=str_replace('src="', 'src="http://hanoi1000.vn/', $info);
        $info=str_replace('<body>', '', $info);
return $info;
} 
?>
<?php
		$url="http://hanoi1000.vn/index.html?view.hlookat=0.00&view.vlookat=0.00&view.fov=1.4";
		$start='<body>';
		$end='<div id="footer">';
		echo getdata($url,$start,$end,'','','','','');
		echo '</div>';	
		echo '</div>';		
?>