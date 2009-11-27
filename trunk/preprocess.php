<?php
/*
//for ask & answer
define('IN_PHPBB', true);
$phpbb_root_path = 'forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);*/

//session management
session_start();

$session_id = session_id();
$ip = $_SERVER['REMOTE_ADDR'];
process($session_id, $ip);
$user_info->update();

$photo = 0;
if (strpos(selfURL(), "photo") > 0) {
	$photo = 1;
}

function get_dest() {
	global $numrow;
	global $index_id;
	$id = (isset($_GET["id"]))? $_GET["id"] : 3;
	$URL = selfURL();
	if (strpos($URL, "photo") > 0) {
		return (isset($_GET["dest_id"]))? $_GET["dest_id"] : 0;
	}
	elseif (strpos($URL, "about") > 0) {
		return 18;
	}
	elseif (isset($_GET["id"])) {
		$sql = "SELECT *
				FROM index_menu
				WHERE id = (SELECT index_id
							FROM posts
							WHERE post_id = $id)";
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$index_id = $row["id"];
		return $row["dest_id"];
	}
	else {			
		 return $id;
	}
}
 
if (isset($_GET["index_id"])) $index_id = $_GET["index_id"];

if (!isset($index_id)) {
	$destination = get_dest();
	if (!isset($index_id)) {
		$sql = "SELECT id
				FROM index_menu
				WHERE (dest_id = ".$destination.") AND (ord = 0)
				GROUP BY id";
		$q->query($sql);
		$selected_index = mysql_fetch_array($q->re);
		$index_id = $selected_index["id"];
	}
}
else {
	$sql = "SELECT dest_id
		FROM index_menu
		WHERE id = ".$index_id;
	$q->query($sql);
	$r = mysql_fetch_array($q->re);
	$destination = $r["dest_id"];
}

?>
