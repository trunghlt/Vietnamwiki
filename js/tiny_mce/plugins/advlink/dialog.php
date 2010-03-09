<script type="text/javascript" src="../../../jquery/jquery-1.2.6.js"></script>
<?php
include("../../../../core/init.php");
include("../../../../core/classes/Filter.php");
include("../../../../core/classes/ActiveRecord.php");

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	if (isset($_GET["index_id"])) $index_id = Filter::filterInput($_GET['index_id'],"/index.php",1);
	$q = new Active;
	if (isset($_GET["id"])) {
		$id = Filter::filterInput($_GET["id"],"index.php",1);
		if ($id > 0) {
			$q->orderby('ord');
			$row = $q->select('','index_menu',"dest_id = $id");	
			if ($q->get_num() > 0) {
				echo "<p><label>Index menu </label>";
				echo "<select name='index".$task."' id='index".$task."' onchange='javascript:update_index(1)'>";
				foreach($row as $r) {
					if (!isset($index_id)) {
						$index_id = $r["id"];
					}
					?>
					<option value="<?php echo $r["id"]?>" <?php if ($index_id == $r["id"]) echo "selected"; ?>>
						<?php echo $r["name"]?>
					</option>
					<?php
				}				
				echo "</select></p>";
			}
		}
	}
	if (!isset($index_id)) $index_id = 0;

	$q->orderby('ord');
	$row = $q->select('','posts',"index_id = $index_id");
	if($q->get_num()=='') return;
?>
<p>Selected Link: </p>
<?php
	$row_per_page = 2;
	$count_record = count($row);
	if( $count_record > $row_per_page)
	{
			$num_page = ceil($count_record/$row_per_page);
	}
	else{
			$num_page = 1;
	}
	if(isset($_GET['s']))
	{
		$start = Filter::filterInput($_GET['s'],"login.php",1);
	}
	else{
		$start = 0;
	}
	$q->free();
	$q->limit($start,$row_per_page);
	$row = $q->select('','posts',"index_id = $index_id");		
	
	$arr = array('post_subject','post_id');
	$qq = new Active;
	foreach($row as $r) {
		$rr = $qq->select($arr,'posts_texts',"post_id=$r[post_id]");
	echo "<div>";
	echo "<font style='font-size:14px;font-weight:bold;color:#336699;'>".$rr[0]['post_subject'].'</font><br />';
	echo "<font style='color:#33CC66;'>http://www.vietnamwiki.net/viewtopic.php?id=".$rr[0]['post_id']."</font><br />";
	echo "</div>";
	}
	echo "<div style='margin-top:5px; margin-bottom:5px; color:RED; font-size:12px;'>";
	if($num_page > 1){

		$current_page = ($start/$row_per_page)+1;
		for($i=1 ; $i <= $num_page; $i++)
		{
			if($current_page != $i)
				echo "<a onclick='changepage(".($i-1)*$row_per_page.",$num_page,$task)'> ".$i." </a>";
			else
				echo " ".$i." ";
		}
	}	
	echo "</div>";
	
	return;
}
?>
<div id='link_dialog' title='Get Link' style="margin-bottom:5px; border:#FF9900 2px solid; ">
<p><label>Destinations</label>
<select name="dest1" id="dest1" onChange="javascript:update_dest(1)">
	<option value="0">Unknown</option>
	<?php
	$q = new Active;
	$q->orderby('ord');
	$row = $q->select('','destinations','');	
	foreach($row as $r) {
		echo "<option value='".$r["id"]."'>".$r["EngName"]."</option>";
	}
	?>
</select></p>
<div id="body1" style="cursor: pointer";>
</div>
</div>
<script type="text/javascript">
	function update_dest(x) {
		var e = document.getElementById("dest" + x);
		jQuery.get('dialog.php',{task:x, id:e.value},
					function(data){
						document.getElementById('body'+x).innerHTML = data;
					});
	}

	function update_index(x) {
		jQuery.get('dialog.php',{task:x, id:destvalue(x), index_id:indexvalue(x)},
					function(data){
						document.getElementById('body'+x).innerHTML = data;
				  });
	}	
	function changepage(start,pagenum,x){
		jQuery.get('dialog.php',{task:x, id:destvalue(x), index_id:indexvalue(x), s:start, page:pagenum},
					function(data){
						document.getElementById('body'+x).innerHTML = data;
				  });	
	}
	function destvalue(x) {
		var e = document.getElementById("dest"+x);
		if (!e) return 0;
		return e.value;
	}
	
	function indexvalue(x) {
		var e = document.getElementById("index"+x);
		if (!e) return 0;
		return e.value;
	}
	
</script>