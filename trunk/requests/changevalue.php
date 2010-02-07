<?php
$vl=isset($_POST['vl']);
$id = isset($_POST['id']);
if($id && $vl){
include('../core/init.php');
include('../core/classes/Db.php');
include('../core/classes/Follow.php');
include('../core/common.php');
$vl = $_POST['vl'];
$id = $_POST['id'];
	if(isNumeric($id) && isNumeric($vl)){
		$post = new Follow;
		if($vl==1)
		{
			$post->update_follow('0',$id);
			echo '0';
		}
		else
		{
			$post->update_follow('1',$id);
			echo '1';
		}
	}
}
?>