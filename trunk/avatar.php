<?php
	$sql = "SELECT avatar ,email
			FROM users
			WHERE id='".$user_info["id"]."'";
	$q->query($sql);
	$row = mysql_fetch_array($q->re);
	$ava = $row["avatar"];
	if (isset($ava) && $ava!='') { 
		$fn = $ava;
	}
	else {
		$fn = "unknown.jpg";
	}
//$pAvatar = new TalkPHP_Gravatar();
//$pAvatar->setEmail($row['email'])->setSize(80)->setRatingAsPG();
?>
<img width="100px" height="100px" alt="avatar" src="images/avatars/<?php echo $fn//$pAvatar->getAvatar()?>" />