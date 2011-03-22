<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
if (logged_in())echo 1;
else echo 0;
?>