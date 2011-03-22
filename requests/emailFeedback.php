<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/Feedback.php");
$fbContent = $_REQUEST["fbContent"];
$clean["fbContent"] = htmlentities($fbContent, ENT_QUOTES);
$fb = new Feedback();
$fb->content = $clean["fbContent"];
$fb->add();
?>


<?php
/*
//define the receiver of the email
$to = 'vietnamwiki@googlemail.com';
//define the subject of the email
$subject = 'Feedback';
//define the message to be sent. Each line should be separated with \n
$message = $_GET["fbContent"];
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: vietnamwiki@googlemail.com\r\nReply-To: vietnamwiki@googlemail.com";
//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
echo $mail_sent ? "Mail sent" : "Mail failed";
*/
?>

<?php
/*
require_once("../libraries/gmail/class.phpgmailer.php");
$mail = new PHPGmailer();
$mail->Username = "vietnamwiki@googlemail.com";
$mail->Password = "vietnam3004";
$mail->From = "vietnamwiki@googlemail.com";
$mail->FromName = "Feedback sender";
$mail->Subject ="Feedback";
$fbContent = $_GET["fbContent"];
$mail->Body = $fbContent;
$mail->Send();*/
?>