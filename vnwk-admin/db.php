<?php

	function db_connect()
	{
      $db_connection = mysql_connect("localhost", "vietnamw_vietnam", "vietnam3004") 
            or die(mysql_error());
      $db_select = mysql_select_db("vietnamw_vietnamwk") or die(mysql_error());            
	}
	
	db_connect();

?>