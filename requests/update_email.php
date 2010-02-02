<?php
include("../core/classes/Db.php");
include("../core/init.php");
include("../core/classes/User.php");
include("../core/classes/Filter.php");
include("../core/common.php");
$id = isset($_POST['id_user']);
$email = isset($_POST['email']);
if($id && $email){
	$id = Filter::number($_POST['id_user']);
	$email = $_POST['email'];
	if(check_email($email)=='ok' && $id!=''){
			$user_info->update_email($id,$email);
			echo 'ok';
	}
	else echo check_email($email);
}
else
	echo 'null';
?>