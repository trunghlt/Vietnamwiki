<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/CommentElement.php");
include("../core/classes/User.php");
if(isset($_POST["commentText"])){
		
		$commentElement = new CommentElement; 
		$commentElement->commentText = $_POST["commentText"];
		$commentElement->postId = $_POST["postId"];
		$commentElement->userId = myUser_id(myip());
		$commentElement->posterIp = myip();
		$commentElement->commentTime = time();
		$post_id = $commentElement->postId;
		if(isset($_POST["editionId"])){
			$commentElement->editionid = $_POST["editionId"];
			$draf = $commentElement->editionid;
			$post_id = $commentElement->editionid;
		}
		$commentElement->add();
}
else if(isset($_POST["commentText2"])){
		$commentElement = new CommentElement; 
		$commentElement->commentText = $_POST["commentText2"];
		$commentElement->postId = $_POST["postId2"];
		$commentElement->userId = myUser_id(myip());
		$commentElement->posterIp = myip();
		$commentElement->commentTime = time();
		$post_id = $commentElement->postId;
		if(isset($_POST["editionId2"])){
			$commentElement->editionid = $_POST["editionId2"];
			$draf = $commentElement->editionid;
			$post_id = $commentElement->editionid;
		}
		if(isset($_POST["name_guess"])){
			$commentElement->name = $_POST["name_guess"];
		}
		if(isset($_POST["email_guess"])){
			$commentElement->email = $_POST["email_guess"];
		}
		$commentElement->add();
}
include("../commentListPainter.php");
?>