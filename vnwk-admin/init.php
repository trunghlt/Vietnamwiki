<?php
	include("db.php");
	include("common.php");
	if (!logged_in()) header("location: login.php");
	$admin_info = get_info();
	$q = new db;

	function get_info() {
		$sql= "SELECT * 
				FROM `admin_users`
				WHERE `admin_users`.id = (SELECT admin_id
											FROM admin_sessions
											WHERE ip= '".myip()."')";
		$result = dbquery($sql);
		$row = mysql_fetch_array($result);
		return $row;
	}
?>