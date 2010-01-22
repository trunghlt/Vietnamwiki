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
	$id = Edition::filterId($_REQUEST["Id"]);
	if($id != ''){
		$draf_edition = new Edition;
		$row = $draf_edition->query($id);
		if($row['checked']==0 && $row['user_id']==myUser_id(myip())){
	?>
			<a class='link' id='edit_link' onClick='editClick()'> Edit </a>
	<?php
			echo "|<a class='link' onClick='deleteConfirmDialog.dialog(\"open\")'> Delete </a>";
		}
	}
}?>