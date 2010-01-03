<?php
	include("db.php");
		$error = array();
	if (isset($_POST["login"])) {
		if($_POST["username"]==NULL)
		{
			$error[] = "<span class=error>Please enter username</span><br />";
		}
		else{
			if(eregi("^[a-zA-Z0-9._]+$",$_POST["username"]))
				$un = $_POST["username"];
			else
			$error[] = "<span class=error>Please enter username</span><br />";
		}
		if($_POST["password"]==NULL)
		{
			$error[] = "<span class=error>Please enter password</span>";
		}
		else{
			if(eregi("^[a-zA-Z0-9]+$",$_POST["password"]))
				$pw = $_POST["password"];
			else
				$error[] = "<span class=error>Please enter password</span>";
		}
		if($pw && $un)
		{
			if ($id = chk_authentication($un, $pw)) {
				sign_in($id);
				header("location: index.php");
				exit();
			}
			else{
				$error[] = "<span class=error>Invalid username or password</span>";
			}
		}
	}
?>
<?
	if(count($error)){
		foreach($error as $value)
		{
			echo "$value";
		}
		reset($error);
	}
?>
<h1>Login</h1>
<form action="login.php" method="post">
	<b>User name:</b>
	<input type="text" name="username" id="username">
	<b>Password: </b>
	<input type="password" name="password" id="password">
	<br/><br/>
	<input type="submit" value="Sign in" name='login' />
</form> 
<?php
	function chk_authentication($un, $pw) {
		//chk SQL injection
		if (strpos($un, "'")) return false;
		if (strpos($pw, "'")) return false;
		
		$sql = "SELECT *
				FROM admin_users
				WHERE (username='".$un."') AND (password ='".$pw."') ";
		$result = mysql_query($sql) or die(mysql_error());
		$numrow = mysql_num_rows($result);
		if ($numrow == 0) return false;
		$row = mysql_fetch_array($result);
		return $row["id"];
	}
	
	function sign_in($id) {
		db_connect();
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql = "UPDATE admin_sessions
				SET admin_id = '".$id."' , logged_in = '1'
				WHERE ip = '".$ip."'";
		mysql_query($sql) or die(mysql_error());					
	}

?>