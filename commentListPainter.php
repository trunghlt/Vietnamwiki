<div id="commentList">
<style>
	#comment_toggle{
		cursor:pointer;
	}
</style>
<?php
include('libraries/TalkPHP_Gravatar.php');
$draft = isset($draf);
if($draft){
	$str_comment = "edition_id = ".$post_id; 
}
else{
	$str_comment = "post_id = ".$post_id; 
}

$com = new CommentElement;
$arr_com = $com->query_num($str_comment);
$n = $arr_com['n'];
$num_row_comment = 10;
if ($n > 0) {
$r_com = $com->query_num($str_comment,'0',$num_row_comment);
$n_com = $r_com['n'];
array_pop($r_com);
	?>
	<div class="comment_head" align="center">
		<?php echo $n?> comment<?php if ($n > 1) echo "s"?>
	</div>
	<?php
	echo "<div class='comment_block'>";
	
		function write_comment($row, $post_id) {
			?><div class='comment'><?php
				$fbId = 0;
				if($row["user_id"]!=0){
					$user_com = new User;
					$x = $user_com->query_id($row["user_id"]);
					if (isset($x["fbId"])) $fbId = (int) $x["fbId"];
					$email = $x["email"];
					$name = $x["username"];
				}
				else {
					$email = $row["email"];
					$name = $row["name"];
				}
				if ($fbId != 0) {
					try {
					    	$fbUserInfo = facebook_client()->api_client->users_getInfo($fbId, "pic_square");
					    	$avatarURL = $fbUserInfo[0]["pic_square"];
						mysql_query("UPDATE users SET avatar='{$avatarURL}' WHERE id={$row["user_id"]}");
					}
					catch (Exception $e) {
						$avatarURL = $x["avatar"];
					}
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
	while ($i < $n_com) {
		write_comment($r_com[$i], $post_id);
		$i++;
	}
	
	if ($n > $num_row_comment) {
		?>
		
		<div id='more_comment' ></div>
		<div id='waiting_load'><!-- --></div> 
		<div align="center" id='state_comment'>
			<a id="comment_toggle" style="font-size: 11px;" onclick="load_comment('<?php echo $str_comment?>',10,10,<?php echo $post_id?>,<?php echo $n?>)">View older comments</a> 
     	</div>
		<script type="text/javascript">
			function load_comment(str_query,numrow_comment,start_comment,post_id,num_query){
					jQuery.post('/requests/loadComment.php',{id : post_id,num : numrow_comment,s : start_comment,str : str_query, n:num_query},function(response){
					if(response != 'false' && response != 'stop'){
					jQuery("#waiting_load").html("<img width='20px' height='20px' alt='loading' src='http://static.manutangroup.com/N_css/WScolor/blue/picts/N_nice/N_main/N_loading.gif' />").slideToggle(2000,function(){ jQuery('#more_comment').html(response);jQuery("#waiting_load").html("<!-- -->").slideToggle(1000);});
							
						}
					}
				);
			}
				function close_comment(){
				
					jQuery("#more_comment").html("<!-- -->");
				
					jQuery("#state_comment").html('<a id="comment_toggle" style="font-size: 11px;" onclick="load_comment('+"'<?php echo $str_comment?>'"+',10,10,<?php echo $post_id?>,<?php echo $n?>);" >View older comments</a>');					
				}
		</script>
		<?php
	}		
	echo "</div>";
	
}
?>
</div>
