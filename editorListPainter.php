<?php
include("core/common.php");
include("core/init.php");
include("core/classes.php");
$clean = array();

if(isset($_POST["postId"])){
	$clean["postId"] = PostElement::filterId($_POST["postId"]);

	$q = new db;
	$q->query("	SELECT *
				FROM editions
				WHERE post_id = '{$clean["postId"]}' and reject=0
				ORDER BY accepted_time");
	
	$editions = array();	
	while ($r = mysql_fetch_array($q->re)) {
		if($r['checked']==1)
			$editions1[] = $r;
		else
			$editions2[] = $r;	
	}	
}
else if(isset($_POST["Index"])){

	$clean["Index"] = PostElement::filterId($_POST["Index"]);
	$q = new db;
	$q->query("	SELECT *
				FROM editions
				WHERE post_id = 0 and index_id=".$clean["Index"]."
				ORDER BY accepted_time");
	while ($r = mysql_fetch_array($q->re)) {
		$index_edition[] = $r;
	}	
	 	
}
	
	//Print information of a specific editor
	function printEditorInfo($currentEdition) {
		global $editorCount;
		$editor = new User();		
		$editor->query($currentEdition->userId);
		$timeLbl = date("d M Y, H:i", $currentEdition->editDateTime);
		?>
		<?php if ($editorCount != 0) echo "," ?><a href="draft.php?id=<?php echo $currentEdition->id?>" title="click to view this draft"><img src="/images/draft.png" width="10px" height="10px"></a>
		<a class='link' href='profile.php?username=<?php echo $editor->username?>' title="Editted at <?php echo $timeLbl?>">
			<span style="font-size: 11px;"><?php echo $editor->username?></span>
		</a>
		<?php
		$editorCount++;
	}
if(isset($index_edition)){
$editorCount = 0;
	echo "<br /><span style='color: #888888'>Under admin's review</span>";
	show($index_edition,"index_not_check");
}
else if(isset($editions2) || isset($editions1)){
$editorCount = 0;
	echo "Posted & editted by";
	show($editions1,"checked");
if(isset($editions2)){
	echo "<br /><span style='color: #888888'>Under admin's review</span>";
	$editorCount = 0;
	show($editions2,"not_checked");
}
}
		
function show($editions,$type){
	if (count($editions) > 3) {
		for ($i = 0; $i <= 1; $i++) {
			$e = $editions[$i];
			$editionElement = new Edition;
			$editionElement->id = $e["id"];
			$editionElement->userId = $e["user_id"];
			$editionElement->editDateTime = $e["edit_date_time"];
			$editionElement->checked = $e["checked"];
			printEditorInfo($editionElement);
		}
		?>
		<span id="etc_<?php echo $type?>">,...</span>
		<span id="middleEditors_<?php echo $type?>" style="display: none;">
			<?php
			for ($i = 2; $i <= count($editions) - 2; $i++) {
				$e = $editions[$i];
				$editionElement = new Edition;
				$editionElement->id = $e["id"];
				$editionElement->userId = $e["user_id"];
				$editionElement->editDateTime = $e["edit_date_time"];
				$editionElement->checked = $e["checked"];
				printEditorInfo($editionElement);
			}
			?>
		</span>		
		<?php
		$e = $editions[count($editions) - 1];
		$editionElement = new Edition;
		$editionElement->id = $e["id"];
		$editionElement->userId = $e["user_id"];
		$editionElement->editDateTime = $e["edit_date_time"];
		$editionElement->checked = $e["checked"];		
		printEditorInfo($editionElement);
		?>
		<a class="link" id="showEditors_<?php echo $type?>" onClick="showEditors('<?php echo $type?>')" style="font-size: 11px;">(show all)</a>
		<a class="link" id="hideEditors_<?php echo $type?>" onClick="hideEditors('<?php echo $type?>')" style="font-size: 11px; display: none;">(hide)</a>
		<script language="javascript">
		function showEditors(type) {
			jQuery("#showEditors_"+type+"").hide();
			jQuery("#etc_"+type+"").hide();
			jQuery("#middleEditors_"+type+"").show();
			jQuery("#hideEditors_"+type+"").show();
		}
		
		function hideEditors(type) {
			jQuery("#showEditors_"+type+"").show();
			jQuery("#etc_"+type+"").show();
			jQuery("#middleEditors_"+type+"").hide();
			jQuery("#hideEditors_"+type+"").hide();
		}
		</script>
		<?php
	}
	else {
		foreach ($editions as $e) {
			$editionElement = new Edition;
			$editionElement->id = $e["id"];
			$editionElement->userId = $e["user_id"];
			$editionElement->editDateTime = $e["edit_date_time"];
			$editionElement->checked = $e["checked"];
			printEditorInfo($editionElement);
		}
	}
}	
?>
