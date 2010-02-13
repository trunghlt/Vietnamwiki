<?php
@ob_end_clean();
ob_start();
session_start();
//include file	
	include("db.php");
	include("common.php");
	include("session.php");
	include("../core/classes/Edition.php");
	include("../core/classes/Filter.php");
	include("../core/classes/Email.php");
	include("../core/classes/Follow.php");
	include("../libraries/sendmail.php");
//check logged user 
	process(session_id(), myip());
	if (!logged_in()) header("location: login.php");
	if(isset($_GET['id']))
	{
		$id = Filter::filterInput($_GET['id'],"login.php",1);
	}

?>
<style>
	label{
		float:left;
		width:200px;
	}
</style>
<link href="../css/themes/default/ui.all.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src=".././js/tiny_mce/tiny_mce.js"></script>
<script src="../js/jquery/jquery-1.3.2.min.js"></script>
<script>
	jQuery.noConflict();
</script>
<script src="../js/integrated.js"></script>
<link rel="stylesheet" type="text/css" href="../css/integrated.css" />
<script language="javascript">
	tinyMCE.init({
				// General options
				mode : "exact",
				elements: "ptex",
				theme : "advanced",
				plugins : "googlemaps,safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
			
				// Theme options
				theme_advanced_buttons1 : "insertlayer,pagebreak,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,googlemaps, googlemapsdel",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,|,print,preview",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_resizing : false,
			
				// Drop lists for link/image/media/template dialogs
				template_external_list_url : ".././js/template_list.js",
				external_link_list_url : ".././js/link_list.js",
				external_image_list_url : ".././js/image_list.js",
				media_external_list_url : ".././js/media_list.js",

				template_replace_values : {
					username : "Some User",
					staffid : "991234"
				}			
			});
</script>	
<!--Confirm-->
<script language="javascript" type="text/javascript">
	function confir(id){
		if(!window.confirm('Are you sure you want this edition to become the formal content on the main website ?'))
		{
			location.href = "edit_edition.php?id="+id;
			return false;
		}
	}
</script>
 
<?php
	$ed = new Edition;

	//check edit edition 
	if(isset($_GET['act']) && $_GET['act']=='edit')
	{
		$post_id = Filter::filterInput($_GET['post_id'],"login.php",1);
		$post_sub = Filter::filterInputText($_POST['PostSub']);
		$post_sum = Filter::filterInputText($_POST['PostSum']);
		$smal_img = Filter::filterInputText($_POST['SmallUrl']);
		$big_url = Filter::filterInputText($_POST['BigUrl']);
		$user_id = Filter::filterInputText($_POST['User_id']);
		$reference = htmlspecialchars(urldecode($_POST['reference']),ENT_QUOTES);

		if($_POST['PTex']==NULL){
			$arr = $ed->query($id);
			$e_Text = $arr['PTex'];
		}
		else{
				$e_Text = htmlspecialchars(urldecode($_POST['PTex']),ENT_QUOTES);
		}
		//Update
		if($e_Text)
		{
			$ed->edit($id,$e_Text,$post_id,$post_sub,$post_sum,$smal_img,$big_url,$reference);
?>
<script language="javascript" type="text/javascript">
	parent.edit.location.href = 'revisionmanagement.php';
</script>
<?php
			$row2 = Email::query(2);
			$str = 'http://www.vietnamwiki.net/viewtopic.php?id='.$post_id;
			$q = new db;
			$message = str_replace('here',$str,$row2['message']);
			$str = $q->query('select email from users where id='.$user_id);
			
			while($row = mysql_fetch_assoc($q->re))
			{
				if($row['email']!='')
					sendmail($row['email'],$row2['subject'],$message,0,$row2['from']);
			}	
		}
	}	

	//show edition
	$arr = $ed->query($id);
	echo "<body id='e_edition'>";
	echo "Edition<br />";
	echo "___________________________________________________<br />";
	echo "<br />";
	

?>
<form method="post" action="edit_edition.php?id=<?php echo $arr['id']?>&post_id=<?php echo $arr['post_id'];?>&act=edit" target="edition" name="edition" >
<div>
	<input type="hidden" name='id' id="id" value="<?php echo $arr['id']?>" />
	<label>Post Subject :</label><input type="text" name="PostSub" value="<?php echo $arr['post_subject'];?>" /><br />
	<input type="hidden" name="User_id" id='User_id' value="<?php echo $arr['user_id'];?>" /><br />
	<label>Post Summary :</label><input type="text" name="PostSum" value="<?php echo $arr['post_summary'];?>" /><br />
	<label>Post Text:</label><br />
	<textarea name="PTex" id="ptex" rows="20" cols="80"><?php
		$content = htmlspecialchars_decode($arr['post_text'],ENT_QUOTES);
		$content = str_replace("|", "&", $content);		 
		$content = str_replace('\"', '"', $content);
		$content = str_replace("\'", "'", $content);
		echo $content;	
	 ?></textarea><br />
	<label>Small Url Img :</label><input type="text" name="SmallUrl" value="<?php echo $arr['post_small_img_url'];?>" /><br />
	<label>Big Url Img :</label><input type="text" name="BigUrl" value="<?php echo $arr['post_big_img_url'];?>" /><br />
	<br />
	<p>
	<b><label>Reference:</label></b>
    <textarea name="reference" id="reference" rows="3" style="width:100%"><?php 
		echo $arr['reference'];
    ?></textarea><br/>	
	</p>
	<input type="submit" name="ok" value="Accept edition" onclick="return confir(<?php echo $arr['id']?>);"/>
	<input type="button" name="reject" value="Reject edition" onclick="rej.dialog('open')"/>
</div>
</form>
<div id='rej_confirm' title='Confirm' style="font-size:12px; font-weight:bold;">
	Do you want reject this edition?
</div>
<div id='sendmail' title='Message'>
	<label> Message:</label><br />
		<textarea cols="80" rows="10" name="s_mail" id="s_mail"></textarea>
</div>

<script language="javascript">
	jQuery(document).ready(function(){
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
							jQuery.post('../requests/reject.php',{ed_id:jQuery('#id').val(),mes:jQuery('#s_mail').val(),user_id:jQuery('#User_id').val()},
									function(data){
										if(data!='false')
										//remove frame
											window.open('edition_frame.php','_parent');
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
	});
</script>
</body>
<?php
ob_end_flush();
?>