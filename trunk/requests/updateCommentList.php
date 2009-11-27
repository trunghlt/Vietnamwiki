<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/CommentElement.php");
$commentElement = new CommentElement; 
$commentElement->commentText = $_POST["commentText"];
$commentElement->postId = $_POST["postId"];
$commentElement->userId = myUser_id(myip());
$commentElement->posterIp = myip();
$commentElement->commentTime = time();
$commentElement->add();
$post_id = $commentElement->postId;
include("../commentListPainter.php");
?>