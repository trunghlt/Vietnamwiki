<?php
include("../core/init.php");
include("../core/common.php");
include("../core/classes/Db.php");
include("../core/classes/User.php");
include("../core/classes/Email.php");
include('../core/classes/Filter.php');
include("../libraries/sendmail.php");


if(c_email($_POST['user_email'])){
    $em = $_POST['user_email'];
    $email = Email::query(7);
    if(is_array($email)){
        $u = new User;
        $arr = $u->query_username("",$em);
        if(is_array($arr)){
            $message = str_replace('{user}',$arr['username'],$email['message']);
            $message = str_replace('{pass}',$arr['password'],$message);
            sendmail($em,$email['subject'],$message,0,$email['from']);
            echo 'true';
        }
        else
            echo 'wrongemail';
    }
}
else
    echo 'format';
?>
