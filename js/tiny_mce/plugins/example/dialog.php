<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Insert Link Url</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="js/link_url.js"></script>
	<script src="../../../jquery/jquery-1.2.6.js" type="text/javascript"></script>
</head>
<body id='edition_manage'>
<?php
include("../../../../core/init.php");
include("common.php");
include("../../../../core/classes/filter.php");

$q = new db;

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	if (isset($_GET["index_id"])) $index_id = Filter::filterInput($_GET['index_id'],"/index.php",1);
	if (isset($_GET["post_id"])) $edition_id = Filter::filterInput($_GET['post_id'],"/index.php",1);
	
	if (isset($_GET["id"])) {
		$id = Filter::filterInput($_GET["id"],"index.php",1);
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

	if (!isset($index_id)) $index_id = 0;
	if ($task == "2") return;
	$sql = "SELECT *
			FROM posts
			WHERE index_id = ".$index_id."
			ORDER BY ord";
	
	$q->query($sql);
?>
	<form onsubmit="Link_urlDialog.insert();return false;" action="#">
	<p>Selected Link: <select id="someval" name="someval" class="text" /></p>
<?php
	while ($r = mysql_fetch_array($q->re)) {
		$sql = "SELECT post_subject,post_id
				FROM posts_texts
				WHERE post_id=".$r["post_id"];
		$qq = new db;
		$qq->query($sql);
		$rr = mysql_fetch_array($qq->re);
	echo "<option value='http://www.vietnamwiki.net/viewtopic.php?id=".$rr['post_id']."'>".$rr['post_subject']."</option>";
	}
?>
</select>
	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="insert" name="insert" value="Insert" onclick="ExampleDialog.insert();" />
		</div>

		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
<?php
	return;
}
?>

<p><label>Destinations</label>
<select name="dest1" id="dest1" onChange="javascript:update_dest(1)">
	<option value="0">Unknown</option>
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
</body>
</html>
