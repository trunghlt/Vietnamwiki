<?php 
require_once("core/common.php");
require_once("core/init.php");
require_once("core/classes.php");
?>
<?php
if (logged_in() || chkFbLoggedIn()) { 
	$currentUser = new User;
	$currentUser->query(myUser_id(myip()));
	$q->query("select property_value from setting where property_name='ALLOW_RESTORE_DRAFT'");
	$r = mysql_fetch_array($q->re);	
?>
	<a class='link' onClick="commentDialog.dialog('open')"> Comment </a>
<?php
	$id = Edition::filterId($_REQUEST["Id"]);
	if($id != ''){
		$draf_edition = new Edition;
		$row = $draf_edition->query($id);
		if($row['checked']==0 && $row['user_id']==myUser_id(myip())){
	?>
		| <a class='link' id='edit_link' onClick='editClick()'> Edit </a>
	<?php
			echo "|<a class='link' onClick='deleteConfirmDialog.dialog(\"open\")'> Delete </a>";
		}
		if ($currentUser->level == 1 || ($r['property_value'] == 1 && $row['checked']==1)) {
?>
		| <a class='link' onClick='restoreDraft()'>Restore this draft</a>
<?php
		}
	}
}
else{
?>
	<a class='link'  onClick="comment_login.dialog('open')"> Comment </a>
<?php
}
?>