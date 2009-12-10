<?php
	include ("projax/projax.php");
	include("db.php");
	include("common.php");
	$q = new db;
	$id = $_GET["id"];
	$add = $_POST["Add"];
	$URL = $_POST["URL"];
	$del_id = $_GET["del_id"];
	
	if (isset($id)) {
		if ($add) {
			$sql = "INSERT INTO map_images
					(dest_id, URL)
					VALUE($id, '$URL')";
			
			$q->query($sql);			
		}
		
		if ($del_id) {
			$sql = "DELETE
					FROM map_images
					WHERE id=$del_id";
			
			$q->query($sql);
		}
		
		$sql = "SELECT *
				FROM map_images
				WHERE dest_id = ($id)";
		
		$q->query($sql);
?>
<!--Confirm-->
<script type="text/javascript" language="javascript">
	function confir(id,del_id){
		if(window.confirm('Do You want delete this destination?'))
		{
			location.href = "map.php?id="+id+"&del_id="+del_id;
		}
	}
</script>

<?		
		While ($r = mysql_fetch_array($q->re)) {
			echo "<a href='#' onclick='confir(".$id.",".$r["id"].");'>(x)</a>";
			echo "<img src='".$r["URL"] . "'/></br>";
		}
		?>
		<form action="map.php?id=<?php echo $id?>" method="post">
			URL: <input id="URL" style="float:left" type="input" name="URL" />
			<input id="Add" name="Add" style="float:left" type="submit" value="Add" />
		</form>
		<?php
		return;
	}
?>
<html>
<head>
<script src="projax/js/prototype.js" type="text/javascript"></script>
<script src="projax/js/scriptaculous.js" type="text/javascript"></script>
</head>
<body>

<?php
	$sql = "SELECT * 
			FROM destinations
			ORDER BY ord";
			
	$q->query($sql);	
?>
<div id="dest">
<h3>Destinations</h3>
<ul id="list">
	<?php 
	$i = 0;
	while ($r = mysql_fetch_array($q->re)) {	?>
		<li id="item_<?php echo $i?>">
			<?php echo $r["ord"]?>
			<?php echo $r["EngName"]?>
			<a href="map.php?id=<?php echo $r['id']?>" target="mostright">(i)</a>
		</li>
		<?php 
		$i++;
	} ?>
</ul>

</body>
</html>
