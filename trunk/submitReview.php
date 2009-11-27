<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");

$review = new Review;
$review->userId = myUser_id(myip());
$review->postId = Filter::number($_REQUEST["postId"]);
$review->rateValue = Filter::number($_REQUEST["rateValue"]);
$review->reviewText= htmlspecialchars(Filter::valueIfIsset($_REQUEST["reviewText"]), ENT_QUOTES);
$review->reviewDateTime= time();
$review->add();

echo getReviewListHTML($review->postId);
?>