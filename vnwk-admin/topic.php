<?php
include ("projax/projax.php");
include("init.php");

$projax = new Projax();

if (isset($_GET["action"])) {
	$action = $_GET["action"];
	
	//reorder topic
	if ($action == "order") {
		$order = $_POST["tlist"];
		for ($i = 0; $i < sizeof($order); $i++) {
			$sql = "UPDATE posts
					SET ord = ".$i."
					WHERE post_id = ".$order[$i];
			$q->query($sql);
		}
		return;
	}
	
	//delete topic
	if ($action == "delete"){
		$id = $_GET["id"];
		$sql = "DELETE FROM posts
				WHERE post_id = '$id'";
		$q->query($sql);
		$sql = "DELETE FROM posts_texts
				WHERE post_id = '$id'";
		$q->query($sql);
		return;
	}			
	
	//toggle lock/unlock topic
	if ($action == "tlock") {
		$id = $_GET["id"];
		$sql = "UPDATE posts
				SET locked = 1 - locked
				WHERE post_id = ".$id;
		$q->query($sql);
		return;
	}
}

if (isset($_GET["moving"])) {
	$ids = $_POST["id"]; 
	$id = substr($ids,strpos($ids,'_')+1);
	$to = $_GET["to"];
	
	$sql = "SELECT *
			FROM posts
			WHERE post_id = ".$id;
	$q->query($sql);
	$topic = mysql_fetch_array($q->re);
	
	$sql = "UPDATE posts
			SET ord = ord - 1
			WHERE (index_id = ".$topic["index_id"].") AND (ord > ".$topic["ord"].")";
	$q->query($sql);
	
	$sql = "SELECT MAX(ord) as 'maxord'
			FROM posts
			WHERE index_id = ".$to;
	$q->query($sql);
	$ord = 0;
	if ($q->n > 0) {
		$r = mysql_fetch_array($q->re);
		$ord = $r["maxord"] + 1;
	}
	
	$sql = "UPDATE posts
			SET index_id = ".$to.", ord = ".$ord."
			WHERE post_id = ".$id;
	$q->query($sql);
}

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	if (isset($_GET["index_id"])) $index_id = $_GET["index_id"];
	
	if (isset($_GET["id"])) {
		$id = $_GET["id"];
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
	echo "<ul id='tlist'>";
	$sql = "SELECT *
			FROM posts
			WHERE index_id = ".$index_id."
			ORDER BY ord";
	$q->query($sql);
	while ($r = mysql_fetch_array($q->re)) {
		$sql = "SELECT post_subject
				FROM posts_texts
				WHERE post_id=".$r["post_id"];
		$qq = new db;
		$qq->query($sql);
		$rr = mysql_fetch_array($qq->re);
		echo "<li id='item_".$r["post_id"]."'>";
		?>			
		<a href="#" onClick="javascript:delete_topic(<?php echo $r["post_id"]?>)">x</a>
		<?php echo $rr["post_subject"];?>
		<a href="#" onClick="javascript:tlock(<?php echo $r["post_id"]?>)">
		(<?php echo $r["locked"]? "unlock":"lock"?>)
		</a>
		<?
		echo "</li>";
		echo $projax->dragable_element('item_'.$r["post_id"],array('revert'=>'true'));
	}
	echo "</ul>";	
	echo "<div id='test'></div>";
	echo $projax->sortabe_element('tlist',array('update'=>'test',
												'complete'=>$projax->visual_effect('highlight','tlist') , 
												'url'=>'topic.php?action=order',
												'constraint' => 0));		
	return;
}
?>
<html>
<head>
<script src="projax/js/prototype.js" type="text/javascript"></script>
<script src="projax/js/scriptaculous.js" type="text/javascript"></script>
</head>
<body>
<h3>Topics</h3>
<table border=1>
<tbody>
<tr>
<td valign="top">
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
</td>
<td valign="top">
	<p><label>Destinations</label>
	<select name="dest2" id="dest2" onChange="javascript:update_dest(2)">
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
	<div id="body2">
	</div>
	<p><label><b>Drop here to move topic to another destination or index</b></label>
	<div id="drop_bag" style="width:100%; height:100px; background:lightgrey; border:1px solid black;">
	</div></p>
	<script type="text/javascript">
		Droppables.add('drop_bag',{onDrop:function(element){new Ajax.Updater('body1','topic.php?task=1&moving=1&id='+destvalue(1)+'&index_id='+indexvalue(1)+'&to='+indexvalue(2),{evalScripts:true, parameters:'id=' + encodeURIComponent(element.id)})}})
	</script>
</td>
</tr>
</tbody>
</table>
<script type="text/javascript">
	update_dest(1);
	update_dest(2);
	function update_dest(x) {
		var e = document.getElementById("dest" + x);
		new Ajax.Updater('body'+x,'topic.php?task='+x+'&id=' + e.value,{evalScripts:true})
	}

	function update_index(x) {
		new Ajax.Updater('body'+x,'topic.php?task='+x+'&id=' + destvalue(x) +'&index_id=' + indexvalue(x),{evalScripts:true})
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

	//delete topic
	function delete_topic(x) {
		//call Ajax delete function
		new Ajax.Updater(	'test',
							'topic.php?action=delete&id='+x,
							{evalScripts:true});
		//update index menu
		update_index(1);
		return true;
	}
	
	//toggle lock/unlock topic
	function tlock(x) {
		//call Ajax delete function
		new Ajax.Updater(	'test',
							'topic.php?action=tlock&id='+x,
							{evalScripts:true});
		//update index menu
		update_index(1);
		return true;	
	}
	</script>
</body>
</html>