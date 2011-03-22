<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/PostElement.php");
include("../core/classes/Edition.php");

echo $_POST["editionId"];
if(isset($_POST["postId"])){
$clean["postId"] = PostElement::filterId($_POST["postId"]);
$postElement = new PostElement();
$postElement->query($clean["postId"]);
	if (myUsername(myip())) {
		$postElement->remove();
		mysql_query("DELETE FROM editions WHERE post_id = '{$clean['postId']}'");			
	}
}
else if(isset($_POST["editionId"])){
	$clean["editionId"] = Edition::filterId($_POST["editionId"]);
	$edition = new Edition;
	$edition->query($clean["editionId"]);
	if (myUsername(myip())) {
		$edition->remove();
	}
}
 
?>