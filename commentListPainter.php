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
		<?php echo $n . " "?>Comments
	</div>
	<?php
	echo "<div class='comment_block'>";
	
		function write_comment($row, $post_id) {
			echo "<div class='comment'>";
			//comment content
			echo "<div class='commentContent'>";
			echo htmlspecialchars($row["comment_text"]);
			echo "</div>";
			
			//Comment detail entry			
			?>
			<div class="commentInfo">
			<?php
			if($row["user_id"]!=0){
				$user_com = new User;
				$x = $user_com->query_id($row["user_id"]);
				$posttime = $row['comment_time'];
				$timelabel = date("d M, Y H:i", $posttime);			
				$username = $x["username"];			
				echo "Posted by ". $username. " at ". $timelabel;			

			}
			else{
				if(($row['name']!='' && $row['email']!='')||($row['name']=='' && $row['email']!=''))
				{
					$pAvatar = new TalkPHP_Gravatar();
					$pAvatar->setEmail($row['email'])->setSize(80)->setRatingAsPG();
						$posttime = $row['comment_time'];
						$timelabel = date("d M, Y H:i", $posttime);			
						echo "Posted by <img class='img_guess' src='".$pAvatar->getAvatar()."' height='20' width='20'/> at ". $timelabel;					
				}
				elseif($row['name']!='' && $row['email']==''){
						$posttime = $row['comment_time'];
						$timelabel = date("d M, Y H:i", $posttime);			
						echo "Posted by $row[name] at ". $timelabel;					 
				}
				elseif($row['name']=='' && $row['email']==''){
						$posttime = $row['comment_time'];
						$timelabel = date("d M, Y H:i", $posttime);			
						echo "Posted by guess at ". $timelabel;
				}
			}
		?>
		</div>
		</div>
		<?php
		}
	$i=0;
	while ($i < 5) {
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