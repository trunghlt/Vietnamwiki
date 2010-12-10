<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
function chkInject($s) {
	return (htmlSpecialChars($s, ENT_QUOTES) != $s);
}
	  
function userid($username, $password) {
	if (trim($password) == "") return -1;	
	$result = mysql_query('SELECT * FROM users WHERE username="'.$username.'" AND password="'.$password.'"');
	$n = mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	if ($n == 0) return -1;
	if($row["ban_user"]==0)
		return $row["id"];
	else
		return -2;
 }
  
$username = $_POST["username"];
$password = $_POST["password"];

function chkLogin() {
	global $username, $password;
	if (chkInject($username)||chkInject($password)) {
		echo 'false';
	}
	else {
	  	$id = userid($username, $password);
	  	if ($id !== -1 && $id !== -2) {
			
			$result = mysql_query('SELECT * FROM users WHERE id='.$id);
			$row = mysql_fetch_array($result);
			if($row['email']=='' && $row['fbId']==NULL)	
			{
				echo $id;
			}
			else{
                                login($id);
				echo 'success';                                
                        }
	  	}
		else 
			if($id === -2){
		 		echo $id;
			}
		 	else 
				echo 'false';
	}
}
chkLogin();
?>