<?php
include("../core/init.php");
include("../core/classes/Db.php");
include('../core/common.php');
$c_value = isset($_POST['value']);
$c_pw = isset($_POST['vlpw']);
$c_re_pw = isset($_POST['vlre_pw']);

if($c_re_pw && $c_pw){
	$pw = $_POST['vlpw'];
	$re_pw = $_POST['vlre_pw'];
	if(check_password($pw) != 'ok')
		echo check_password($pw);
	else
	{
		if(check_confirm_password($re_pw ,$pw)!='ok')
			echo check_confirm_password($re_pw ,$pw);
		else
			echo '1';
	}
}
if($c_value){
	$value = $_POST['value'];
	$type = $_POST['type'];
	if($type == 'email'){
		if(check_email($value) !='ok')
			echo check_email($value);
		else
			echo '1';
	}
	else{
		if(check_password($value) !='ok')
			echo check_password($value);
		else
			echo '1';	
	}
}
?>