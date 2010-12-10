<?php
	session_start();
	
	$session_id = session_id();
	include('core/common.php');
	include('core/init.php');
	include('core/classes.php');
	include('core/classes/CommentElement.php');
	include('core/session.php');
	$currentEdition = new Edition;
	$editionId = $currentEdition->filterId($_GET["id"]); 
	if($currentEdition->query($editionId) == 0)
		header("location:index.php");
	$ip = $_SERVER['REMOTE_ADDR'];
	process($session_id, $ip);
	include("core/classes/Color.php");
	include('core/filters.php');
	include('header.php');
	$draf = $currentEdition->filterId($_GET["id"]);
	$post_id = $draf;
	if (isset($_GET["page"])) $page = $_GET["page"];
	if (!isset($page)) $page = 1;

	$page = pagePostFilter($page);
	$title = $currentEdition->postTitle;
	$content = $currentEdition->postContent;
	if($currentEdition->reference!='')
	$reference = $currentEdition->reference;
	else $reference='';
	if(isset($currentEdition->index_id) && $currentEdition->index_id!=''){
		$des_index_id = new IndexElement;
		$des_index_id->query($currentEdition->index_id);
		$destination = $des_index_id->destId;
		$index_id = $currentEdition->index_id;
	}
	else{
	
		$get_index_id = new PostElement;
		$get_index_id->query($currentEdition->postId);
		$index_id = $get_index_id->indexId;
		$des_index_id = new IndexElement;
		$des_index_id->query($index_id);
		$destination = $des_index_id->destId;
	}	
	include('destination.php');
	require_once("ajaxLoad.php");
//change_template();
?>
<td class="center">

<div style = "background: #EDEFF4; height: 28px;">
	<div id="menuWrapper">
		<div id="toolbar"></div>
	</div>
</div>

<div id="contentTable">
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
		<h2 style="padding-left: 40px;"><?=$title?> (draft)</h2>
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
	<a class='link' href='profile.php?username=<?php echo $a["username"]?>'>
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

<input type="hidden" id='type' name="type" />
<div id="restoreConfirmDialog" title="Alert">
	Restoring this draft will make all later drafts deleted. Are you sure you still want to restore this draft ?
</div>
<div id='rej_confirm' title='Confirm' style="font-size:12px; font-weight:bold;">
	Do you want reject this edition?
</div>
<div id='sendmail' title='Message'>
	<label> Message:</label><br />
		<textarea cols="80" rows="10" name="s_mail" id="s_mail"></textarea>
</div>
<script language="javascript">

jQuery(document).ready(function(){
	loadToolbar("toolbar");
	loadDraftRibbon(<?php echo $editionId?>,"ribbon");
		mail = jQuery('#sendmail').dialog({
					autoOpen: false,
					width: '450',
					minHeight:'400',
					height:'auto',
					modal: true,
					resizable:true,
					overlay: {
						backgroundColor: '#000',
						opacity: 0.5
					},
					buttons:{
						'Send Email':function(){
							jQuery.post('../requests/reject.php',{ed_id:<?php echo $draf?>,mes:jQuery('#s_mail').val(),user_id:<?php echo $editorId?>},
									function(data){
										if(data!='false'){
											location.href = 'index2.php';
											alert('Success');
										}
										else
											alert(data);
									});
							rej.dialog('close');
							mail.dialog('close');
						},
						'Cancel':function(){
							jQuery(this).dialog('close');
							rej.dialog('close');
						}
					}
				});
		rej = jQuery('#rej_confirm').dialog({
			autoOpen: false,
			height: 'auto',
			width: '300',
			modal: true,
			resizable:false,
			overlay: {
				backgroundColor: '#000',
				opacity: 0.5
			},
			buttons:{
				'Submit': function() {
					mail.dialog('open');
				},
				Cancel: function() {
					jQuery(this).dialog('close');
				}				
			}
		});
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

function restoreDraft(c_type) {
	restoreConfirmDialog.dialog("open");
	document.getElementById('type').value = c_type;
}

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
					loadToolbar("toolbar");
                                        loadNotification();
					loadDraftRibbon(<?php echo $editionId?>,"ribbon");
					jQuery('#field_not_login_comment').html("Email :<br /><input class='field' name='fill_email_comment' id='fill_email_comment' type='text' style='width:250px' value=''/><br />Name :<br /><input class='field' name='fill_name_comment' id='fill_name_comment' type='text' style='width:250px' value=''/><br /><input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='1'/>");
				});
}

function submitLogin(dom,check) {	
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(), 
			function(response){
				if(response==-2)
					alert("This user has been banned");
				else if(response == 'false'){
                                    alert("Login's fail");
                                }
				else
				{
					if(response != '' && response != 'success'){						
						document.getElementById('id_user').value = response;

                                                var str = jQuery("#"+dom).serialize().split("&");
                                                var name = str[0].split("=");
                                                jQuery("#name_user").val(name[1]);
                                                
						document.getElementById('editpost').value = 'draft';
                                                
						jQuery('#FillEmailDialog').css('visibility','visible').dialog('open');
					}
					else if(response == 'success'){	
						loginDialog.dialog("close");
						set_value();
											
					}
					else
					{
						//if(check==2)
						//	edit_login.dialog('close');
					}
                                        
				}
	});
}
//Set value when user register successfully email
function set_value(){
    loadDraftRibbon(<?php echo $editionId?>, "ribbon");
    jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");
    loadNotification();
    loadToolbar("toolbar");
}
//end
function submitRestoreDraft(){
	jQuery.post("/requests/restoreDraft.php", 
				{editionId: <?php echo $editionId?>,type:document.getElementById('type').value},
				function(response) {
						window.location = "<?php 
							if($currentEdition->postId!=0)
								echo getPostPermaLink($currentEdition->postId);
							else
								echo "index2.php";?>";
				}, 
				"html");
}
function editClick() {
	editDialog.dialog('open');
}
</script>
<?php include("commentListPainter.php"); ?>
</td>
<td classs="right">
    <?php include('listquestion.php');?>
</td>
</tr>
<tr>
<td colspan=3>
    <?php include("footLinks.php");?>
</td>
</tr>
</tbody></table>

<?php
include("forms/loginForm.php");
include("forms/composeForm.php");
include("forms/editForm.php");
include("forms/commentForm.php");
include("forms/deleteConfirmForm.php");
include("forms/register_email.php");
include("footer.php");
?>

