<div id="commentList">
<?php
$draft = isset($draf);
if($draft){
	$str_comment = "edition_id = '".$post_id."'"; 
}
else{
	$str_comment = "post_id = '".$post_id."'"; 
}
$sql = "SELECT *
		FROM comments
		WHERE $str_comment
        ORDER BY comment_id DESC";
$result = mysql_query($sql) or die(mysql_error());
$n = mysql_num_rows($result);
if ($n > 0) {
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
			$sql = "SELECT *
				FROM users
				WHERE id='".$row["user_id"]."'";
			$r1 = mysql_query($sql) or die(mysql_error());
			$x = mysql_fetch_array($r1);
			$posttime = $row['comment_time'];
			$timelabel = date("d M, Y H:i", $posttime);			
			$username = $x["username"];			
			echo "Posted by ". $username. " at ". $timelabel;			
			?>
			</div>
			</div>
			<?php
		}
	
	$i = 0;	
	while (($i < 5)&&($row = mysql_fetch_array($result))) {
		$i++;
		write_comment($row, $post_id);
	}
	
	//elastic comments
	if ($i < $n) {
		?> 
		<div id="elastic_comments">
			<?php while ($row = mysql_fetch_array($result)) {
				write_comment($row, $post_id);
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
	
	mysql_free_result($result);
	echo "</div>";
	
}
?>
</div>