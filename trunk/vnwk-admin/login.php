<?php
	include("db.php");
	if (isset($_POST["username"])) {
		$un = $_POST["username"];
		$pw = $_POST["password"];
		if ($id = chk_authentication($un, $pw)) {
			sign_in($id);
			header("location: index.php");
		}
		echo "Invalid username or password";
	}
?>
<h1>Login</h1>
<form action="login.php" method="post">
	<b>User name:</b>
	<input type="text" name="username" id="username">
	<b>Password: </b>
	<input type="password" name="password" id="password">
	<br/><br/>
	<input type="submit" value="Sign in" />
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