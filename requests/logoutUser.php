<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/Classes/User.php");
    if(logged_in()){
        User::updateTimeLogin(myUser_id(myip()));
    }
?>