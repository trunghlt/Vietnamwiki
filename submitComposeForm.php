<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");
include("libraries/sendmail.php");
if($_POST["summary"] != NULL && $_POST["title"] != NULL && $_POST["content"] != NULL){
$postElement = new PostElement();
$postElement->summary = htmlspecialchars($postElement->filterSummary($_POST["summary"]));
$postElement->title = htmlspecialchars($postElement->filterTitle($_POST["title"]));
$postElement->content = filter_content_script(htmlspecialchars($postElement->filterContent(urldecode($_POST["content"]))));
$postElement->indexId = htmlspecialchars($postElement->filterId(urldecode($_POST["indexId"])));
$postElement->smallImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["smallImgURL"])));
$postElement->bigImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["bigImgURL"])));
$postElement->authorUsername = myUsername(myip());
$postElement->reference = htmlspecialchars($postElement->filterReference(urldecode($_POST["ref"])));
$postElement->add(myUser_id(myip()));	
//create new Post
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
		$str = 'http://www.vietnamwiki.net'.getPostPermalink($postElement->id);
		
$u = new User;
$arr = $u->query_id($editionElement->userId);		
		$message = str_replace('{link}',$str,$row2['message']);
		$message = str_replace('{time}',date("d/m/Y H:i a",$editionElement->editDateTime),$message);
		$message = str_replace('{username}',$arr['username'],$message);
		$message = str_replace('{title}',$editionElement->postTitle,$message);
		
		$r = Email::query_post($postElement->id);
		
		foreach($r as $row)
		{
				if(c_email($row['email']))
					sendmail($row['email'],$row2['subject'],$message,0,$row2['from']);	
		}
echo getPostPermalink($postElement->id);
}
else{
$u = new User;
$arr = $u->query_id($editionElement->userId);
		$row2 = Email::query(3);
		//$message = str_replace('{link}','',$row2['message']);
		$index = new IndexElement;
		$index->query($editionElement->index_id);
		$des = new DestinationElement;
		$des->query($index->destId);
		$des->queryById();
		$str = $des->engName." -> ".$index->name;
				
		$message = str_replace('{time}',date("d/m/Y H:i a",$editionElement->editDateTime),$row2['message']);
		$message = str_replace('{username}',$arr['username'],$message);
		$message = str_replace('{title}',$str,$message);

		$row = $u->query_level(1);
		
		foreach($row as $arr2){
			if($arr2['level']==1){
				$message = str_replace('{message}','This post is waiting for your review before the official content is updated!',$message);
			}
			if(c_email($arr2['email']))
				sendmail($arr2['email'],$row2['subject'],$message,0,$row2['from']);
		}
echo "preview";
}

}
else{
echo 'null';
}
?>
