<?php 
require_once("core/common.php");
require_once("core/init.php");
require_once("core/classes.php");
if (logged_in() || chkFbLoggedIn()) { 
	$clean["postId"] = PostElement::filterId($_REQUEST["postId"]);
	$postElement = new PostElement;
	$postElement->query($clean["postId"]);
	$currentUser = new User;
	$currentUser->query(myUser_id(myip())); ?>
	<a class='link' onClick="commentDialog.dialog('open')"> Comment </a>			
	<?php if ((!$postElement->locked)||($currentUser->level == 1)) { ?>
		|<a class='link' id='edit_link' onClick='editClick()'> Edit </a>			
		<?php if ($currentUser->username == $postElement->authorUsername ) {
			echo "|<a class='link' onClick='deleteConfirmDialog.dialog(\"open\")'> Delete </a>";
		}		
	}
}?>