<?php      
include("config.php");

function db_connect()
{
      $db_connection = mysql_connect("localhost", "root", "") 
            or die(mysql_error());
      $db_select = mysql_select_db("vietnamwiki") or die(mysql_error());            
}
db_connect();

?>
