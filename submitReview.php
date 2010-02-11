<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");
include('libraries/TalkPHP_Gravatar.php');
$pAvatar = new TalkPHP_Gravatar();
$review = new Review;
$review->userId = myUser_id(myip());
$review->postId = Filter::number($_REQUEST["postId"]);
$review->reviewText= htmlspecialchars(Filter::valueIfIsset($_REQUEST["reviewText"]), ENT_QUOTES);
$review->rateValue = Filter::number($_REQUEST["rateValue"]);
$review->reviewDateTime= time();
if(isset($_POST["name_guess"]) || isset($_POST["email_guess"])){
	if(isset($_POST["name_guess"]))
		$review->name= $_POST["name_guess"];
	if(isset($_POST["email_guess"]))
		$review->email= $_POST["email_guess"];
}
$review->add();
echo getReviewListHTML($review->postId);
?>