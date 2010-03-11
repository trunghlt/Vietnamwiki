<?php
$vl=isset($_POST['vl']);
$id = isset($_POST['id']);
$comment_email = isset($_POST['email_comment']);
$comment_name = isset($_POST['name_comment']);
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
			$post->update_follow(0,$id);
			echo '0';
		}
		else
		{
			$post->update_follow(1,$id);
			echo '1';
		}
	}
}
if($comment_email || $comment_name){
	if($comment_email){
		 if(preg_match("/[a-zA-Z0-9._]+\@[a-zA-Z0-9]{2,}\.[a-zA-Z]{2,}/", $_POST['email_comment'])){
		 	echo '1';
		 }
		 else
		 	echo '0';
	}
	else if($comment_name){
		 if(preg_match("/^\w+$/", $_POST['name_comment'])){
		 	echo '1';
		 }
		 else
		 	echo '0';
	}
}
?>