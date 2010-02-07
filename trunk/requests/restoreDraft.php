<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/Edition.php");
include("../core/classes/PostElement.php");
include("../core/classes/Email.php");
include("../core/classes/Follow.php");
include("libraries/sendmail.php");
if(isset($_REQUEST["editionId"]) && $_POST["type"]==1){
	$clean["editionId"] = Edition::filterId($_REQUEST["editionId"]);
	$currentEdition = new Edition;
	$currentEdition->query($clean["editionId"]);
	$currentEdition->restore();
	
		$row2 = Email::query(2);
		$str = 'http://www.vietnamwiki.net/viewtopic.php?id='.$currentEdition->postId;
		$message = str_replace('here',$str,$row2['message']);
		$str = $q->query('select email from users where id='.$currentEdition->userId);
		
		while($row = mysql_fetch_assoc($q->re))
		{
			if($row['email']!='')
				sendmail($row['email'],$row2['subject'],$message,0,$row2['from']);
		}	
}
else if(isset($_REQUEST["editionId"]) && $_POST["type"]==2){
	$clean["editionId"] = Edition::filterId($_REQUEST["editionId"]);
	$currentEdition = new Edition;
	$currentEdition->query($clean["editionId"]);
	$currentEdition->restore(2);
}
?>