<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");

$postElement = new PostElement();
$postElement->id = $postElement->filterId($_POST["id"]);
$postElement->summary = htmlspecialchars($postElement->filterSummary($_POST["summary"]), ENT_QUOTES);
$postElement->title = htmlspecialchars($postElement->filterTitle($_POST["title"]), ENT_QUOTES);
$postElement->smallImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["smallImgURL"])), ENT_QUOTES);
$postElement->bigImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["bigImgURL"])), ENT_QUOTES);
$postElement->content = htmlspecialchars(PostElement::filterContent(urldecode($_POST["content"])), ENT_QUOTES);
$postElement->indexId = $postElement->filterId(urldecode($_POST["indexId"]));
$postElement->save();	

$editionElement = new Edition();
$editionElement->postId = $postElement->id;
$editionElement->userId = myUser_id(myip());
$editionElement->postTitle = $postElement->title;
$editionElement->postSummary = $postElement->summary;
$editionElement->postContent = $postElement->content;
$editionElement->postSmallImgURL = $postElement->smallImgURL;
$editionElement->postBigImgURL = $postElement->bigImgURL;
$editionElement->editDateTime = time();
$editionElement->add();

$content = htmlspecialchars_decode($postElement->content, ENT_QUOTES);
$content = str_replace("|", "&", $content);
$content = str_replace('\"', '"', $content);
$content = str_replace("\'", "'", $content);
echo "<h2>". $postElement->title . "</h2>";      
echo $content;
?>