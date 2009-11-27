<?php 
require_once("core/common.php");
require_once("core/init.php");
require_once("core/classes.php");
if (logged_in()) { 
	$currentUser = new User;
	$currentUser->query(myUser_id(myip())); ?>
	<?php if ($currentUser->level == 1) { ?>
		<a class='link' onClick='restoreDraft()'>Restore this draft</a>			
	<?php }
}?>