<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");
include('libraries/TalkPHP_Gravatar.php');
include("libraries/sendmail.php");
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
$row2 = Email::query(3);
$str = 'http://localhost/review.php?&id='.$review->postId;
$message = str_replace('here',$str,$row2['message']);
$r = Email::query_post($review->postId);
foreach($r as $row)
{
	sendmail($row['email'],$row2['subject'],$message,0,$row2['from']);
}
echo getReviewListHTML($review->postId);
?>