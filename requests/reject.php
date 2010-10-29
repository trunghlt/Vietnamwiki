<?php
include("../core/init.php");
include("../core/common.php");
include("../core/classes/Db.php");
include("../core/classes/Edition.php");
include("../core/classes/Filter.php");
include("../core/classes/Email.php");
include("../core/classes/User.php");
include("../libraries/sendmail.php");
$id = isset($_POST['ed_id']);
if($id && isNumeric($_POST['ed_id']) && isNumeric($_POST['user_id'])){
	$id = $_POST['ed_id'];
        $user_id = $_POST['user_id'];
	$row2 = Email::query(6);
	$message = Filter::filterInputText($_POST['mes']);
	$u = new User;
	$r = $u->query_id($user_id);
	$e = new Edition;
	$e->reject($id,$user_id);
	if(sendmail($r['email'],$row2['subject'],$message,0,$row2['from']))
        {
            echo 'Success';
	}
	else
		echo 'false';
}
else
	echo 'false';
?>