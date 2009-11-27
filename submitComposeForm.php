<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");

$postElement = new PostElement();
$postElement->summary = htmlspecialchars($postElement->filterSummary($_POST["summary"]));
$postElement->title = htmlspecialchars($postElement->filterTitle($_POST["title"]));
$postElement->content = htmlspecialchars($postElement->filterContent(urldecode($_POST["content"])));
$postElement->indexId = htmlspecialchars($postElement->filterId(urldecode($_POST["indexId"])));
$postElement->smallImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["smallImgURL"])));
$postElement->bigImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["bigImgURL"])));
$postElement->authorUsername = myUsername(myip());
$postElement->add();	

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

echo $postElement->id;
?>