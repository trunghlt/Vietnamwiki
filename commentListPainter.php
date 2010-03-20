<div id="commentList">
<?php
include('libraries/TalkPHP_Gravatar.php');
$draft = isset($draf);
if($draft){
	$str_comment = "edition_id = '".$post_id."'"; 
}
else{
	$str_comment = "post_id = '".$post_id."'"; 
}
$com = new CommentElement;
$r_com = $com->query_num($str_comment);
$n = $r_com['n'];
if ($n > 0) {
array_pop($r_com);
	?>
	<div class="comment_head" align="center">
		<?php echo $n?> comment<?php if ($n > 1) echo "s"?>
	</div>
	<?php
	echo "<div class='comment_block'>";
	
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
	$i=0;
	while (($i < 5)&&($i < $n)) {
		write_comment($r_com[$i], $post_id);
		$i++;
	}
	
	//elastic comments
	if ($i < $n) {
		?> 
		<div id="elastic_comments">
			<?php while ($i < $n) {
					write_comment($r_com[$i], $post_id);
				$i++;
			} ?>
		</div>
		<div align="center">
			<span id="comment_arrow" ><img src="css/images/down_arrow.gif" /></span><a id="comment_toggle" href="#" style="font-size: 11px;">Show more</a> 
     	</div>
		<script type="text/javascript">
			jQuery(document).ready(function(){ 
				commentSlide = new Fx.Slide('elastic_comments'); 
				window.addEvent('domready', function(){
					commentSlide.hide();
				});
				$('comment_toggle').addEvent('click', function(e){
					e = new Event(e);
					if ($('comment_toggle').get("html") == "Show more") {
						$('comment_toggle').set("html", "Slide up");
						$('comment_arrow').set("html", "<img src='css/images/up_arrow.gif' />");
				  	}
					else {
						$('comment_toggle').set("html", "Show more");
						$('comment_arrow').set("html", "<img src='css/images/down_arrow.gif' />");
					}	
				  	commentSlide.toggle();
				  	e.stop();
				});
			});
		</script>
		<?php
	}		
	echo "</div>";
	
}
?>
</div>