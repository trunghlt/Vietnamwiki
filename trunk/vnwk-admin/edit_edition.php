<?php
@ob_end_clean();
ob_start();
session_start();
//include file	
	include("db.php");
	include(".././core/classes/Edition.php");
	include("common.php");
	include("session.php");
	include(".././core/classes/filter.php");
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
<link href="admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src=".././js/tiny_mce/tiny_mce.js"></script>
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
		if(!window.confirm('Do You want edit this edition?'))
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
		if($_POST['PTex']==NULL){
			$e_Text = $arr['PTex'];
		}
		else{
				$e_Text = Filter::filterInputText($_POST['PTex']);
		}
		//Update
		if($e_Text)
		{
			$ed->edit($id,$e_Text,$post_id,$post_sub,$post_sum,$smal_img,$big_url);
?>
<script language="javascript" type="text/javascript">
	parent.edit.location.href = 'revisionmanagement.php';
</script>
<?php
		}
	}	

	//show edition
	$arr = $ed->query($id);
	echo "<body id='e_edition'>";
	echo "Edition<br />";
	echo "___________________________________________________<br />";
	echo "<br />";
?>
<form method="post" action="edit_edition.php?id=<?php echo $arr['id']?>&post_id=<?php echo $arr['post_id'];?>&act=edit" target="edition" name="edition" o>
<div>
	<label>Post Subject :</label><input type="text" name="PostSub" value="<?php echo $arr['post_subject'];?>" /><br />
	<label>Post Summary :</label><input type="text" name="PostSum" value="<?php echo $arr['post_summary'];?>" /><br />
	<label>Post Text:</label><br />
	<textarea name="PTex" id="ptex" rows="20" cols="80"><?php echo $arr['post_text'];?></textarea><br />
	<label>Small Url Img :</label><input type="text" name="SmallUrl" value="<?php echo $arr['post_small_img_url'];?>" /><br />
	<label>Big Url Img :</label><input type="text" name="BigUrl" value="<?php echo $arr['post_big_img_url'];?>" /><br />
	<br />
	<input type="submit" name="ok" value="Update Edition" onclick="return confir(<?php echo $arr['id']?>);"/>
</div>
</form>
</body>
<?php
ob_end_flush();
?>