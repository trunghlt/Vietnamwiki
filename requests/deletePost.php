<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/PostElement.php");

$clean["postId"] = PostElement::filterId($_POST["postId"]);

$postElement = new PostElement();
$postElement->query($clean["postId"]);
if (myUsername(myip())) {
	$postElement->remove();
	mysql_query("DELETE FROM editions WHERE post_id = '{$clean['postId']}'");			
}

 
?>