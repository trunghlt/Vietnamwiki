<?php
include('../libraries/TalkPHP_Gravatar.php');
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/User.php");
include("../core/classes/CommentElement.php");
include("../core/common.php");

function write_comment($row, $post_id) {
?><div class='comment'><?php
	$fbId = NULL;
	if($row["user_id"]!=0){
		$user_com = new User;
		$x = $user_com->query_id($row["user_id"]);
		if (isset($x["fbId"])) $fbId = $x["fbId"];
		$email = $x["email"];
		$name = $x["username"];
	}
	else {
		$email = $row["email"];
		$name = $row["name"];
	}
	if (isset($fbId)) {
		$fbUserInfo = facebook_client()->api_client->users_getInfo($fbId, "pic_square");
		$avatarURL = $fbUserInfo[0]["pic_square"];
	}
	else {
		$pAvatar = new TalkPHP_Gravatar();
		$pAvatar->setEmail($email)->setSize(80)->setRatingAsPG();
		$avatarURL = $pAvatar->getAvatar();
	}
	$posttime = $row['comment_time'];
	$timelabel = date("d M, Y H:i", $posttime);
	?>
	<img class="avatar" src='<?php echo $avatarURL?>' height=50 width=50/>
	<div class="commentText">
		<span class="author"><?php echo $name?></span>
		<?php echo htmlspecialchars($row["comment_text"]);?>
		<br/>
		<span class="timeLbl"><?php echo $timelabel;?></span>
	</div>						 
</div><?php
}

if(isset($_POST['id']) && isset($_POST['num']) && isset($_POST['s']) && isset($_POST['n'])){
	if(isNumeric($_POST['id']) && isNumeric($_POST['num']) && isNumeric($_POST['s'])){
			$id = $_POST['id'];
			$num = $_POST['num'];
			$s = $_POST['s'];
			$str_comment = $_POST['str'];
			$n = $_POST['n'];
			$com = new CommentElement;
			
			$r_com = $com->query_num($str_comment,$s,$num);
			$n_com = $r_com['n'];
			array_pop($r_com);
			
			if($s+$n_com  <=  $n){
				
					$i=0;
					while ($i < $n_com) {
						$str = write_comment($r_com[$i], $id);
						$i++;
					}
				if($s+$n_com  ==  $n){?>
					<script>
					jQuery("#state_comment").html("<a id='comment_toggle' href='#' style='font-size: 11px;' onclick='close_comment();'>Close comments</a>");
					</script>
			<?php } else{ $s_com = $s+$n_com; ?>
					<script>
					jQuery("#state_comment").html('<a id="comment_toggle" href="#" style="font-size: 11px;" onclick="load_comment('+"'<?php echo $str_comment?>'"+',<?php echo $s_com?>,10,<?php echo $id?>,<?php echo $n?>);" >View older comments</a>');
					</script>			
			<?php }
				echo $str;
			}
	}
	else
		echo 'false';
}
?>