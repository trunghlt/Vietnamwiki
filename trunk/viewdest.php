<div style="padding: 5px;">
<?php
echo "<h1>Latest Headlines</h1>";

db_connect();
$dest_id = isset($_GET["id"])? $_GET["id"] : 1;
$dest_id = htmlspecialchars($dest_id , ENT_QUOTES);


	$r1 = mysql_query("SELECT d.EngName,i.dest_id FROM index_menu i, destinations d where i.id=$index_id and d.id = i.dest_id");
	$arr1 = mysql_fetch_array($r1);
	$dest_name = $arr1['EngName'];


//$request = ($dest_id == 1)? "" : "WHERE index_id = '".$index_id."'";
$request = "WHERE index_id = '".$index_id."'";
//sort
if (isset($_GET["sort"])) {
	$sort_type = $_GET["sort"];
}
else {
	$sort_type = "post_id";
}

ltrim($sort_type);
rtrim($sort_type);		
$style1 = "font-size: 11px;";
$style2 = "font-size: 11px;";
$style3 = "font-size: 11px;";
if ($sort_type == "rate") { $sort_query = "rate"; $style2 = " font-weight :bold";}
elseif ($sort_type == "view") { $sort_query = "page_view"; $style3 = "font-size: 11px; font-weight :bold"; }
else { $sort_query = "post_id"; $style1 = "font-size: 11px; font-weight :bold"; }

$href = getIndexPermalink($index_id);
?>

<div> 
<span class="style2">
Sorted by: <a style="<?php echo $style1 ?>" href= "<?php echo $href?>">posted time</a>
| <a style="<?php echo $style2 ?>" href= "<?php echo $href . "-Srate"?>">rates</a>
| <a style="<?php echo $style3 ?>" href= "<?php echo $href . "-Sview"?>">views</a>
</span>
<div>
<br />
<?php
		
//content		
$num_per_page = 20;
$page = isset($_GET["page"])? $_GET["page"] : 1;
$result = mysql_query("SELECT * FROM posts") or die(mysql_error()); 
$numrow = mysql_num_rows($result);
if (($page - 1) * $num_per_page > $numrow) die_to_index();
$start = ($page - 1) * $num_per_page;
$end = $page * $num_per_page;
$result = mysql_query("SELECT * FROM posts ".$request." ORDER BY ".$sort_query." DESC LIMIT ".$start.",".$end) or die(mysql_error());
$result2 = mysql_query("SELECT * FROM posts ".$request) or die(mysql_error()); 
$numrow2 = mysql_num_rows($result2);
While ($row = mysql_fetch_array($result)) {       
	$sql = "SELECT *
			FROM posts_texts
			WHERE post_id='".$row['post_id']."'";
	$re2 = mysql_query($sql) or die(mysql_error());
	$post = mysql_fetch_array($re2);
	$title = $post['post_subject'];
	$content = $post['post_summary'];
	
	if (isset($post["post_small_img_url"])) 
		$smallImgURL = htmlspecialchars_decode($post["post_small_img_url"], ENT_QUOTES);
	
	if (isset($post["post_big_img_url"]))
		$bigImgURL = htmlspecialchars_decode($post["post_big_img_url"], ENT_QUOTES);
		
	?>
	<div <?php if (isset($smallImgURL)) echo 'style="height: 110px;"'?>>
	<div style="float: left; margin-right: 10px">				
		<?php if ( isset($smallImgURL) && isset($bigImgURL) ) { ?>
			<a rel="lightbox" href="<?php echo $bigImgURL?>">
				<img class="postSmallImg" src="<?php echo $smallImgURL?>"/>
			</a>
		<?php } 
			else if (isset($smallImgURL)) { ?>
				<img class="postSmallImg" src="<?php echo $smallImgURL?>"/>
		<?php }?>
	</div>			
	<div>
	<?php
	//location 
	/*
	$sql = "SELECT EngName
			  FROM destinations
			  WHERE id=(SELECT dest_id
						FROM posts
						WHERE post_id = '".$row["post_id"]."')";
	$re3 = mysql_query($sql);
	$a = mysql_fetch_array($re3);
	$dest = $a['EngName'];
	echo '<b>'.$dest.'</b><br>'; 	*/						
	//title
	$href = getPostPermalink($row["post_id"]);
	echo "<a href='{$href}' class=\"head2\">". HtmlSpecialChars($title) . "</a><br>";      
	// post time information
	/*
	$posttime = $row['post_time'];
	$timelabel = date("d M, Y H:i", $posttime);
	echo "<span class='style2'>". $timelabel . "</span><p>";
	*/
	//content
	$s = $content;      
	$s = MakeTextViewable($s);      
	echo $s . "<p>"; 
	?>
	</div>
	</div>
	<div style="border-bottom: 1px dotted gray; margin-bottom: 10px"></div>
	<?php
	mysql_free_result($re2);
} mysql_free_result($result);

?>

<?php
if($numrow2 <= 5){
	if($numrow2 == 0){
		$str = 'is currently no topic';
	}
	else if ($numrow2 == 1) {$str = "is currently only 1 topic";}
	else $str = "are currently only ". $numrow2 . " topics";
?>
<p class="note1">There <?php echo $str?> in this index. Is <?php echo $dest_name?> your hometown? Or do you just simply love this place? You know you can add a new topic here to recommend <?php echo $dest_name?> to worldwide travellers by clicking on the below button.</p>

<div id="mainMenu">
<ul>
	<li id='link_add' value="0">
<?php if(!logged_in()){?>
		<a onClick="loginDialog.dialog('open')" >+ Add new topic</a>

<?php }else{ ?>
	<a onClick="composeDialog.dialog('open')">+ Add new topic</a>
<?php }?>
	</li>
</ul>
</div>
</div><br />
<div id="editorList" class="editorInfo"></div>
<script language="javascript">
jQuery(document).ready(function(){
loadEditorList("", "editorList",<?php echo $index_id?>);
});
</script>
<?php
}

/*
function write_link_dest($i , $c) {
		global $dest_id;
		echo '<a class="link" href="index2.php?id='.$dest_id.'&page='.$i.'">'.$c.'</a>';
}

if ($numrow > $num_per_page) {
	write_link_dest(1, "<<");
	$numpage = intval($numrow / $num_per_page);
	echo " ";
	for ($i = 1; $i <= $numpage; $i++) {
		if ($i > 1) echo " | ";
		if ($i == $page) {
			echo "<b>";
		}								
		write_link_dest($i, $i);
		if ($i == $page) {
			echo "</b>";
		}
	}
	echo " ";
	write_link_dest($numpage, ">>");
}
*/
//include("forms/composeForm.php");
?>
