<?php
	$sql = "SELECT email
			FROM users
			WHERE id='".$user_info["id"]."'";
	$q->query($sql);
	$row = mysql_fetch_array($q->re);
/*	$ava = $row["avatar"];
	if (isset($ava)) { 
		$fn = $ava;
	}
	else {
		$fn = "unkown.jpg";
	}*/
$pAvatar = new TalkPHP_Gravatar();
$pAvatar->setEmail($row['email'])->setSize(80)->setRatingAsPG();
?>
<img width="100px" height="100px" alt="avatar" src="<?php echo $pAvatar->getAvatar()//echo $fn?>" />