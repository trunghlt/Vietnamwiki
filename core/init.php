<?php      
include("config.php");

function db_connect()
{
      $db_connection = mysql_connect("localhost", "vnwkroot", "vietnam3004") 
            or die(mysql_error());
      $db_select = mysql_select_db("vnwk") or die(mysql_error());            
}
db_connect();

?>
