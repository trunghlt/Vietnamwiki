<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");
include("libraries/sendmail.php");
if($_POST["summary"] != NULL && $_POST["title"] != NULL && $_POST["content"] != NULL){
$postElement = new PostElement();
$postElement->summary = htmlspecialchars($postElement->filterSummary($_POST["summary"]));
$postElement->title = htmlspecialchars($postElement->filterTitle($_POST["title"]));
$postElement->content = htmlspecialchars($postElement->filterContent(urldecode($_POST["content"])));
$postElement->indexId = htmlspecialchars($postElement->filterId(urldecode($_POST["indexId"])));
$postElement->smallImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["smallImgURL"])));
$postElement->bigImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["bigImgURL"])));
$postElement->authorUsername = myUsername(myip());
$postElement->reference = htmlspecialchars($postElement->filterReference(urldecode($_POST["ref"])));
$postElement->add(myUser_id(myip()));	

$editionElement = new Edition();
$editionElement->postId = $postElement->id;
$editionElement->userId = myUser_id(myip());
$editionElement->postTitle = $postElement->title;
$editionElement->postSummary = $postElement->summary;
$editionElement->postContent = $postElement->content;
$editionElement->postSmallImgURL = $postElement->smallImgURL;
$editionElement->postBigImgURL = $postElement->bigImgURL;
$editionElement->editDateTime = time();
$editionElement->index_id = $postElement->indexId;
$editionElement->post_ip = myip();
$editionElement->post_username = $postElement->authorUsername;
$editionElement->reference = $postElement->reference;
$editionElement->add();
if($editionElement->postId != 0){
		$row2 = Email::query(1);
		$str = 'http://www.vietnamwiki.net/viewtopic.php?id='.$editionElement->id;
		$message = str_replace('here',$str,$row2['message']);
		$str = $q->query('select email from users where level=1');
		
		while($row = mysql_fetch_assoc($q->re))
		{
			if($row['email']!='')
				sendmail($row['email'],$row2['subject'],$message,0,$row2['from']);	
		}
}
else{
		$row2 = Email::query(1);
		$message = str_replace('here','',$row2['message']);
		$str = $q->query('select email from users where level=1');
		while($row = mysql_fetch_assoc($q->re))
		{
			sendmail($row['email'],$row2['subject'],$message,0,$row2['from']);
		}
}
echo $postElement->id;
}
else{
echo 'null';
}
?>