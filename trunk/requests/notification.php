<?php
include("../core/init.php");
include("../core/common.php");
include("../core/classes/Db.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/Notification.php");
if(!logged_in() && Notification::Checkip(myip())==0){
?>
   <div>
     Do you know that VietnamWiki is an open encyclopedia, meaning that you can create and edit articles yourself ? <span class="click_notification" onclick="agree_notification();">Yes, I know</span>
   </div>
<style>
    #image_text #text_notification {
	position:absolute;
	top:20px; /* in conjunction with left property, decides the text position */
	left:100px;
	width:799px; /* optional, though better have one */
        height:64px;
        text-align: center;
        line-height: 64px;
        text-decoration: none;
        color: BLACK;
        background: #FFC90E;
        border-radius:50px;
	-moz-border-radius-bottomleft:50px;
	-moz-border-radius-bottomright:50px;
	-moz-border-radius-topleft:50px;
	-moz-border-radius-topright:50px;
        -webkit-border-radius:50px;
    }
    .click_notification{
        cursor: pointer;
        text-decoration: underline;
        color: blue;
    }
</style>
<?php
}
?>
