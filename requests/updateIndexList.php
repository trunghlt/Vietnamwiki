<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");

function filterLocId($locId) {
	return $locId; 
}

$locId = filterLocId($_POST["locId"]);
$q = new Db;
$q->query("	SELECT * 
			FROM index_menu
			WHERE dest_id = ".$locId);
$re = $q->re;
?>
<select name="index" id="index">
<?php 
while ($r = mysql_fetch_array($q->re)) {
	?>
	<option value="<?php echo $r["id"]?>">
	<?php echo $r["name"]?>
	</option>
	<?php 
}
?>
</select>
<?php 
?>