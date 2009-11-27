<?php
	class db {
		var $re;
		var $sql;
		var $n = 0;
		
		function query($s) {
			$this->sql = $s;
			$this->re = dbquery($s);	
			error_reporting(0);
			$this->n = mysql_num_rows($this->re);
			error_reporting(3);
		}
	}
	
	function dbquery($sql) {
		$re = mysql_query($sql) or die(mysql_error());
		return $re;
	}
	
	function logged_in() {
		$ip = myip();
		$sql = "SELECT *
				FROM admin_sessions
				WHERE ip = '".$ip."'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		return $row["logged_in"];
	}

	function myip() {
		return $_SERVER['REMOTE_ADDR'];
	}
	

?>