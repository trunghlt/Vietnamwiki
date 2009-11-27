<?php
include('class.img.php');
if($_GET['pic']){
	//$img = new img('upload/'.$_GET['pic']);
	$img = new img($_GET['pic']);
	$img->resize();
	$img->show();
}
?>
