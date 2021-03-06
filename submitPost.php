<?php
include("core/init.php");
include("core/common.php");
include("core/classes.php");
include("libraries/sendmail.php");
//Edit Edition

$postElement = new PostElement();
$postElement->id = $postElement->filterId($_POST["id"]);
$postElement->summary = htmlspecialchars($postElement->filterSummary($_POST["summary"]), ENT_QUOTES);
$postElement->title = htmlspecialchars($postElement->filterTitle($_POST["title"]), ENT_QUOTES);
$postElement->smallImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["smallImgURL"])), ENT_QUOTES);
$postElement->bigImgURL = htmlspecialchars($postElement->filterImgURL(urldecode($_POST["bigImgURL"])), ENT_QUOTES);
$postElement->content = htmlspecialchars(PostElement::filterContent(urldecode(filter_content_script($_POST["content"])), ENT_QUOTES));
$postElement->indexId = $postElement->filterId(urldecode($_POST["indexId"]));
$postElement->reference = htmlspecialchars($postElement->filterReference(urldecode($_POST["ref"]), ENT_QUOTES));
if($_POST["type"]==2 && User::check_user(myUser_id(myip()),$postElement->id)>0){
	$n = $postElement->save(myUser_id(myip()));
}
$editionElement = new Edition();
$editionElement->postId = $postElement->id;
$editionElement->userId = myUser_id(myip());
$editionElement->postTitle = $postElement->title;
$editionElement->postSummary = $postElement->summary;
$editionElement->postContent = $postElement->content;
$editionElement->postSmallImgURL = $postElement->smallImgURL;
$editionElement->postBigImgURL = $postElement->bigImgURL;
$editionElement->index_id = $postElement->indexId;
$editionElement->post_ip = myip();
$editionElement->post_username = myUsername(myip());
$editionElement->reference = $postElement->reference;
if($_POST["type"]==1 && User::check_user(myUser_id(myip()),$postElement->id)>0)
{
	$editionElement->id = $postElement->filterId($_POST["id_edition"]);	
	$editionElement->save();
/*	$content = htmlspecialchars_decode($editionElement->postContent, ENT_QUOTES);
	$content = str_replace("|", "&", $content);
	$content = str_replace('\"', '"', $content);
	$content = str_replace("\'", "'", $content);
	echo "<h2>".$postElement->title. "</h2>";      
	echo $content;
	if($postElement->reference!='')
	{
		echo "<h2 style='color:black; font-size:9pt;'>Reference :</h2>";
		echo HtmlSpecialChars($postElement->reference);
	}*/
}
else if($_POST["type"]==2){
    $u = new User;
    $arr = $u->query_id($editionElement->userId);
    $editionElement->editDateTime = time();
    if(User::check_user(myUser_id(myip()),$postElement->id)>0)
    {
	$editionElement->c_method();
    }
    else{
        $editionElement->add();
    }
        $row2 = Email::query(1);
	$str = 'http://www.vietnamwiki.net'.getPostPermalink($postElement->id);

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

 /*
 	$content = htmlspecialchars_decode($postElement->draft, ENT_QUOTES);
	$content = str_replace("|", "&", $content);
	$content = str_replace('\"', '"', $content);
	$content = str_replace("\'", "'", $content);
        if($n == 1)
	{
		echo "<script>";
		echo "jQuery('#confirm').css('visibility','visible').dialog('open');";
		echo "</script>";
		echo "<h2>". $postElement->title . "</h2>";      
		echo $content;
		if($postElement->reference!='')
		{
                        $postElement->query($postElement->id);
			echo "<h2 style='color:black; font-size:9pt;'>Reference :</h2>";
			echo HtmlSpecialChars($postElement->reference);

                }
	}
	else{
	echo "<h2>". $postElement->title . "</h2>";      
	echo $content;
		if($postElement->reference!='')
		{
			echo "<h2 style='color:black; font-size:9pt;'>Reference :</h2>";
			echo HtmlSpecialChars($postElement->reference);
		} 
	}*/

    
}
?>
