<?php
include("../core/init.php");
include("../core/common.php");
include("../core/classes/Db.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/Notification.php");
    $notification = new Notification;
    $notification->ip_address = myip();
    $notification->add();
?>
