<?php
	$sql = "SELECT *
			FROM users
			WHERE id='".$user_info["id"]."'";
	$q->query($sql);
	$row = mysql_fetch_array($q->re);
	$ava = $row["avatar"];

	if (isset($ava) && $ava!='') { 
		if (isset($row["fbId"])) $avaUrl = $ava;
		else $avaUrl = "images/avatars/$ava";
	}
	else {
		$pAvatar = new TalkPHP_Gravatar();
		$pAvatar->setEmail($row['email'])->setSize(100)->setRatingAsPG();
		$avaUrl = $pAvatar->getAvatar();
	}
?>
<img width="100px" height="100px" alt="avatar" src="<?=$avaUrl?>" />
