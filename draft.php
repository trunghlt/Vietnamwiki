<?php
	include('core/common.php');
	include('core/init.php');
	include('core/classes.php');
	include('core/session.php');
	include('core/filters.php');
	include('header.php'); 
	include('destination.php');
	require_once("ajaxLoad.php");
?>
<td class="center">	

<div style = "background: #EDEFF4; height: 28px;">
	<div id="toolbar"></div>
</div>

<div id="contentTable">
	<?php 
	
	$currentEdition = new Edition;
	$editionId = $currentEdition->filterId($_GET["id"]); 
	$draf = $currentEdition->filterId($_GET["id"]);
	$post_id = $draf;
	

	
	if (isset($_GET["page"])) $page = $_GET["page"];
	if (!isset($page)) $page = 1;

	$page = pagePostFilter($page);		

	$currentEdition->query($editionId);

	$title = $currentEdition->postTitle;
	$content = $currentEdition->postContent;
	if($currentEdition->reference!='')
	$reference = $currentEdition->reference;
	else $reference='';
	?>

	<div id="postContent">
		<?php 
		//get number of pages
		$content = htmlspecialchars_decode($content, ENT_QUOTES);
		$content = str_replace("|", "&", $content);		 
		$content = str_replace('\"', '"', $content);
		$content = str_replace("\'", "'", $content);
		$ps = '|';

		$page_break = '<hr />';
		$content = str_replace($page_break, $ps, $content);

		$page_break = '<hr style="width: 100%; height: 2px;" />';
		$content = str_replace($page_break, $ps, $content);

		$pnum = substr_count($content, $ps) + 1;

		//content
				function strpos_n($x, $c, $n) {
					$p = strpos($x, $c);
					for ($i = 2; $i <= $n; $i++) {
						$p = strpos($x, $c, $p + 1);
					}
					return $p;
				}
		$start = ($page == 1)? 0 : strpos_n($content, $ps, $page - 1) + 1;
		$end = ($page == $pnum)? strlen($content) - 1 : strpos_n($content, $ps, $page) - 1;
		$len = $end - $start + 1;
		$s = substr($content, $start, $len);

		//title
		?>
		<div class="largeDraftIcon"></div>
		<h2 style="padding-left: 40px;"><?php echo HtmlSpecialChars($title)?> (draft)</h2>
		<?php echo $s;
		if($reference!='')
		{
			echo "<h2 style='color:black; font-size:9pt;'>Reference :</h2>";
			echo HtmlSpecialChars($reference);
		} 	
		?>	
	</div>

	<?php if ($page < $pnum) { ?>
		 <br/><span class="style1">(Continue next page...)</span><br/>
	<?php } ?>

	<br /> 

	<?php
	//page division
	function writelink($s, $id, $p) {
		echo '<a class="link" href="draft.php?id='.$id.'&page='.$p.'" >'.$s.'</a>';									
	}
	if ($pnum > 1) {
		writelink("<< ", $editionId, 1);
		for ($i = 1; $i <= $pnum; $i++) {

			if ($i - $page !== 0) { writelink($i, $editionId, $i); }
			else  { echo '<b>'.$i.'</b>'; }
				
			if ($i < $pnum) echo " | ";
		}
		writelink(" >>", $editionId, $pnum);
	}

	$editorId = $currentEdition->userId;
	// username & post time information
	$sql = "SELECT *
			FROM users
			WHERE id=$editorId";
	$re4 = mysql_query($sql);
	$a = mysql_fetch_array($re4);
	$posttime = $currentEdition->editDateTime;
	$timelabel = date("d M Y, H:i", $posttime);
	?>

	<div id="ribbon" align="right" style="width: 100%; background: #E6E6E6"></div>

	<div class="editorInfo">
	Editted by
	<a class='link' href='profile.php?username=<?php echo $a["username"]?>'">
		<?php if (isset($a["avatar"])) {
			$ava = "images/avatars/tiny/" . $a["avatar"];		
			?>
			<img style='border: 0px' src='<?php echo $ava?>'/>
		<?php } 
		?><span style="font-size: 11px;"><?php echo $a["username"];?></span> 	
	</a>
	at <?php echo $timelabel;?>
	</div>
	
	<br/><br/><br/>

</div><!--contentTable-->

<div id="restoreConfirmDialog" title="Alert">
	Restoring this draft will make all later drafts deleted. Are you sure you still want to restore this draft ?
</div>

<script language="javascript">
jQuery(document).ready(function(){
	loadToolbar("toolbar");
	loadDraftRibbon(<?php echo $editionId?>,"ribbon");
	restoreConfirmDialog = jQuery("#restoreConfirmDialog").dialog({
		autoOpen: false,
		height: 'auto',
		resizable: false,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},		
		buttons: {
			'Ok': function() {
				submitRestoreDraft();
				jQuery(this).dialog("close");
			},
			Cancel: function(){ 
				jQuery(this).dialog("close");
			}
		}
	});
});

function restoreDraft() {
	restoreConfirmDialog.dialog("open");
}

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
					loadToolbar("toolbar");
					loadDraftRibbon(<?php echo $editionId?>,"ribbon");
				});
}

function submitLogin(dom) {	
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(), 
			function(response){
				if(response==-2)
					alert("This user has been banned");
				else
				{
					loadToolbar("toolbar");
					loadDraftRibbon(<?php echo $editionId?>, "ribbon");
				}
	});
}

function submitRestoreDraft(){
	jQuery.post("/requests/restoreDraft.php", 
				{editionId: <?php echo $editionId?>},
				function(response) {
					window.location = "<?php echo getPostPermaLink($currentEdition->postId)?>";
				}, 
				"html");
}
function editClick() {

	editDialog.dialog('open');
}
</script>
<?php
include("commentListPainter.php");
include("forms/loginForm.php");
include("forms/composeForm.php");
include("forms/editForm.php");
include("forms/commentForm.php");
include("forms/comment_login.php");
include("forms/deleteConfirmForm.php");
include("footer.php");
?>
