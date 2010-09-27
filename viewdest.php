<div style="padding: 5px;">
<?php
echo "<h1>Latest Headlines</h1>";

db_connect();
$dest_id = isset($_GET["id"])? $_GET["id"] : 1;
$dest_id = htmlspecialchars($dest_id , ENT_QUOTES);
$c_post = new PostElement;
$query = new Active;
$r1 = $query->select("d.EngName,i.dest_id","index_menu i, destinations d", "i.id=$index_id and d.id = i.dest_id");
foreach($r1 as $arr1)
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
$numrow = $c_post->query_rowByIndex();
if (($page - 1) * $num_per_page > $numrow) die_to_index();
$start = ($page - 1) * $num_per_page;
$end = $page * $num_per_page;
$query->orderby($sort_query,"DESC");
$query->limit("$start,$end");
$r = $query->select('',"posts","index_id = '".$index_id."'");
//$result = mysql_query("SELECT * FROM posts ".$request." ORDER BY ".$sort_query." DESC LIMIT ".$start.",".$end) or die(mysql_error());
$numrow2 = $c_post->query_rowByIndex($index_id);
if($r != NULL)
foreach ($r as $row) {
        $post = $c_post->query_id($row['post_id']);
	$title = $post['post_subject'];
	$content = $post['post_summary'];
	
	if (isset($post["post_small_img_url"])) 
		$smallImgURL = htmlspecialchars_decode($post["post_small_img_url"], ENT_QUOTES);
	
	if (isset($post["post_big_img_url"]))
		$bigImgURL = htmlspecialchars_decode($post["post_big_img_url"], ENT_QUOTES);
		
	?>
	<div <?php if (isset($smallImgURL)) echo 'style="margin-right: 10px; padding-bottom: 10px; "'?>>
		<?php if ( isset($smallImgURL) && isset($bigImgURL) ) { ?>
			<a rel="lightbox" href="<?php echo $bigImgURL?>">
				<img class="postSmallImg" src="<?php echo $smallImgURL?>" align="left" style="margin-right:10px;"/>
			</a>
		<?php } 
			else if (isset($smallImgURL)) { ?>
				<img class="postSmallImg" src="<?php echo $smallImgURL?>" align="left" style="margin-right:10px;"/>
		<?php }?>

				

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
	echo $s ; 
	?>
                                <div class="clear"></div>
	</div>
<div style="border-bottom: 1px dotted gray; margin-bottom: 10px; padding-top:10px;"></div>
	<?php
}

?>

<?php
if($numrow2 <= 5){
	if($numrow2 == 0){
		$str = 'is currently no topic';
	}
	else if ($numrow2 == 1) {$str = "is currently only 1 topic";}
	else $str = "are currently only ". $numrow2 . " topics";
?>
<div class="note1">There <?php echo $str?> in this index. Is <?php echo $dest_name?> your hometown? Or do you just simply love this place? You know you can add a new topic here to recommend <?php echo $dest_name?> to worldwide travellers by clicking on the below button.</div>

<div class='button' style="margin-top:20px;">
<ul style="float:left;">
<li id="link_add" value='<?php if(!logged_in()) echo 0; else echo 1; ?>'>
<?php if(!logged_in()){?>
		<a onClick="jQuery('#loginDialog').css('visibility','visible').dialog('open')" >+ Add new topic</a>

<?php }else{ ?>
	<a onClick="jQuery('#composeDialog').css('visibility','visible').dialog('open')">+ Add new topic</a>
<?php }?>
</li>
</ul>
</div>
</div><br />
<div id="editorList" class="editorInfo" style="clear:left"></div>
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

