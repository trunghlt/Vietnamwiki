<?php

	function process($session_id, $ip) {
		delete_timeout_session();
		renew_session($session_id);
		if (!exist_session($session_id)) {
			if (!exist_session_withip($ip)) {
				insert_session($session_id, $ip);
			}
			else {
				overwrite_session($session_id, $ip);
			}
		}
	}
	
	function update_session_page($ip, $page) {
		db_connect();
		$sql = "UPDATE sessions
				SET page = '".$page."'
				WHERE ip = '".$ip."'";
		mysql_query($sql) or die(mysql_error());				
	}
	
	function get_session_page($ip) {
		db_connect();
		$sql = "SELECT page
				FROM sessions
				WHERE ip = '".$ip."'";				
		$result = mysql_query($sql) or die(mysql_error());				
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		return $row['page'];
	}
	
	
	function exist_session_withip($ip) {
		db_connect();
		$sql = "SELECT id
				FROM sessions
				WHERE ip ='".$ip."'";
		$result = mysql_query($sql) or die(mysql_error());
		return (mysql_num_rows($result) == 0)? FALSE : TRUE;
	}

	function overwrite_session($session_id, $ip) {
		db_connect();
		$sql = "UPDATE sessions
				SET id = '".$session_id."'
				WHERE ip = '".$ip."'";
		mysql_query($sql) or die(mysql_error());	
	}
	
	function renew_session($session_id) {
		$sql = "UPDATE sessions
				SET start = '".time()."'
				WHERE id = '".$session_id."'";
		db_connect();
		mysql_query($sql) or die(mysql_error());
	}	
	
	function exist_session($session_id) {
		$sql = "SElECT * FROM sessions WHERE id = '".$session_id."'";
		$result = mysql_query($sql) or die(mysql_error());
		$n = mysql_num_rows($result);
		if ($n == 0) return FALSE;
		return TRUE;
	}
	
	function delete_timeout_session() {
		db_connect();
		$time = time();
		$timeout = 3600;		
		$sql = "DELETE FROM sessions WHERE start + '". $timeout ."' < '".$time."'";
		$result = mysql_query($sql) or die(mysql_error());		
	}
	
	function insert_session($session_id, $ip) {
		$start = time();
		$id = $session_id;
		$user_id = 0;
		$logged_in = FALSE; 
		$sql = "INSERT INTO sessions (id, user_id, start, logged_in, ip) VALUE('".$id."','".$user_id."','".$start."','".$logged_in."','".$ip."')";
		$result = mysql_query($sql) or die(mysql_error());
	}	
?>