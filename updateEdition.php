<?php
//This file is called in order to update  the first edition with correct datetime

include("core/common.php");
include("core/init.php");
include("core/classes.php");
$qPostList = new db;
$qPostList->query(" SELECT * FROM posts");
while ($postList = mysql_fetch_array($qPostList->re)) {
	$qEditionList = new db;
	$qEditionList->query("SELECT * FROM editions WHERE post_id = {$postList["post_id"]}");
	$nEditions = mysql_num_rows($qEditionList->re);
	if ($nEditions == 1) {
		$qUser = new db;
		$qUser->query("SELECT * FROM users WHERE username = '{$postList["post_username"]}'");
		$qUserRe = mysql_fetch_array($qUser->re);
		$userId = $qUserRe["id"];
		
		$post = new PostElement;
		$post->query($postList["post_id"]);
		
		$e = mysql_fetch_array($qEditionList->re);
		$edition = new Edition;
		$edition->query($e["id"]);
		$edition->editDateTime = $postList["post_time"];
		$edition->save();
		echo "Update edition $edition->id successfully.<br/>";
		
		/*
		$edition = new Edition;
			$edition->userId = $userId;
			$edition->postId = $post->id;
			$edition->postTitle = htmlspecialchars($post->title, ENT_QUOTES);
			$edition->postSummary = htmlspecialchars($post->summary, ENT_QUOTES);
			$edition->postContent = htmlspecialchars($post->content, ENT_QUOTES);
			$edition->postSmallImgURL = htmlspecialchars($post->smallImgURL, ENT_QUOTES);
			$edition->postBigImgURL = htmlspecialchars($post->bigImgURL, ENT_QUOTES);
			
		$edition->add();		
		echo "Add an edition to post $edition->postId successfully.<br/>";
		*/
	}	
}			
?>