<?php
include("db.php");
include("common.php");
include("session.php");
include(".././core/classes/filter.php");
session_start();
process(session_id(), myip());

if (!logged_in()) header("location: login.php");

$q = new db;
$post_id = 0;

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	if (isset($_GET["index_id"])) $index_id = Filter::filterInput($_GET['index_id'],"login.php",1);
	if (isset($_GET["post_id"])) $edition_id = Filter::filterInput($_GET['post_id'],"login.php",1);
	
	if (isset($_GET["id"])) {
		$id = Filter::filterInput($_GET["id"],"login.php",1);
		if ($id > 0) {
			$sql = "SELECT *
					FROM index_menu
					WHERE dest_id = ".$id."
					ORDER BY ord";
			
			$q->query($sql);
			if ($q->n > 0) {
				echo "<p><label>Index menu </label>";
				echo "<select name='index".$task."' id='index".$task."' onchange='javascript:update_index(1)'>";
				while ($r = mysql_fetch_array($q->re)) {
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
//Edition	---------------------------------------------------------
	if (!isset($index_id)) $index_id = 0;
	if($index_id > 0)
	{
			$sql = "SELECT pt.post_subject,pt.post_id,p.ord
					FROM posts p, posts_texts pt
					WHERE p.index_id = ".$index_id." and pt.post_id = p.post_id
					ORDER BY p.ord";
			$q->query($sql);
			
			echo "<p><label>Post menu </label>";
			echo "<select name='edition".$task."' id='edition".$task."' onchange='javascript:update_edition(1)'>";

			if($q->n > 0){
				while ($r = mysql_fetch_array($q->re)) {
					if($post_id == 0) $post_id = $r["post_id"];
?>
					<option value="<?php echo $r["post_id"]?>" <?php if ($edition_id == $r["post_id"]) echo "selected"; ?>>
						<?php echo $r["post_subject"]?>
					</option>
					<?php
				}	
			}	
			echo "</select></p>";
	}


//-----------------------------------------------------------------
	if (isset($_GET["post_id"])) $post_id = Filter::filterInput($_GET['post_id'],"login.php",1);	
	//show edition with check = 0 in UNKNOW to review
	if($post_id == 0){
		echo "<ul id='tlist'>";
		$sql = "SELECT *
				FROM editions
				WHERE checked=0
				order by edit_date_time desc";
	}
	else
	{
		echo "<ul id='tlist'>";
		$sql = "SELECT *
				FROM editions
				WHERE post_id = ".$post_id." AND checked=0
				order by edit_date_time desc";
	}
	$q->query($sql);
	
	while ($r = mysql_fetch_array($q->re)) {
		 echo "<li id='item_".$r["id"]."'>";
		 echo $r["post_subject"]. "(" . date("d/m/Y",$r["edit_date_time"]).")";
		 ?>
		<a href="edit_edition.php?id=<?php echo $r["id"];?>" target="edition">View</a>
		<?php
		echo "</li>";
	}
	echo "</ul>";	
	return;
}
?>
<html>
<head>
<script src="projax/js/prototype.js" type="text/javascript"></script>
<script src="projax/js/scriptaculous.js" type="text/javascript"></script>
</head>
<link href="admin.css" rel="stylesheet" type="text/css" />
<body id='edition_manage'>
<h3>Revision Management</h3>

<p><label>Destinations</label>
<select name="dest1" id="dest1" onChange="javascript:update_dest(1)">
	<option value="0">All</option>
	<?php
	$sql= "SELECT * 
		   FROM destinations 
		   ORDER BY ord";
	
	$q->query($sql);
	while ($r = mysql_fetch_array($q->re)) {
		echo "<option value='".$r["id"]."'>".$r["EngName"]."</option>";
	}
	?>
</select></p>
<div id="body1" style="cursor: pointer";>
</div>

<script type="text/javascript">
	update_dest(1);
	function update_dest(x) {
		var e = document.getElementById("dest" + x);
		new Ajax.Updater('body'+x,'revisionmanagement.php?task='+x+'&id=' + e.value,{evalScripts:true})
	}

	function update_index(x) {
		new Ajax.Updater('body'+x,'revisionmanagement.php?task='+x+'&id=' + destvalue(x) +'&index_id=' + indexvalue(x),{evalScripts:true})
	}	
	
	function update_edition(x) {
		new Ajax.Updater('body'+x,'revisionmanagement.php?task='+x+'&id=' + destvalue(x) +'&index_id=' + indexvalue(x)+'&post_id='+editionvalue(x),{evalScripts:true})
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
	
	function editionvalue(x) {
		var e = document.getElementById("edition"+x);
		if (!e) return 0;
		return e.value;
	}
	</script>
</body>
</html>