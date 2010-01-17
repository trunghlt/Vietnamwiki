<?php
include("core/common.php");
include("core/init.php");
include("core/classes.php");
$clean = array();
$clean["postId"] = PostElement::filterId($_POST["postId"]);

	$q = new db;
	$q->query("	SELECT *
				FROM editions
				WHERE post_id = '{$clean["postId"]}'
				ORDER BY edit_date_time");
	
	$editions = array();	
	while ($r = mysql_fetch_array($q->re)) {
		if($r['checked']==1)
			$editions1[] = $r;
		else
			$editions2[] = $r;	
	}	
	
	$editorCount = 0;
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

	echo "Posted & editted by";
	show($editions1);
if(isset($editions2)){
	echo "<br />Under Admin's review";
	$editorCount = 0;
	show($editions2);
}
		
function show($editions){
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
		<span id="etc">,...</span>
		<span id="middleEditors" style="display: none;">
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
		<a class="link" id="showEditors" onClick="showEditors()" style="font-size: 11px;">(show all)</a>
		<a class="link" id="hideEditors" onClick="hideEditors()" style="font-size: 11px; display: none;">(hide)</a>
		<script language="javascript">
		function showEditors() {
			jQuery("#showEditors").hide();
			jQuery("#etc").hide();
			jQuery('#middleEditors').show();
			jQuery("#hideEditors").show();
		}
		
		function hideEditors() {
			jQuery("#showEditors").show();
			jQuery("#etc").show();
			jQuery('#middleEditors').hide();
			jQuery("#hideEditors").hide();
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