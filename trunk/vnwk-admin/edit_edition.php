<?php
ob_end_clean();
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
		$id = Filter::filterInput($_GET['id'],"login.php",1);

?>
<style>
	label{
		float:left;
		width:200px;
	}
</style>
<!--Confirm-->
<script language="javascript" type="text/javascript">
	function confir(id){
		if(!window.confirm('Do You want edit this edition?'))
		{
			location.href = "edit_edition.php?id="+id;
			alert('Cancel!');
		}
	}
</script>
 
<?php
	$ed = new Edition;

	//check edit edition 
	if(isset($_GET['act']) && $_GET['act']=='edit')
	{
		if($_POST['PTex']==NULL){
			$e_Text = $arr[PTex];
		}
		else{
				$e_Text = Filter::filterInputText($_POST['PTex']);
		}
		//Update
		if($e_Text)
		{
			$ed->edit($id,$e_Text);
		}
	}	

	//show edition
	$arr = $ed->query($id);
	echo "Edition<br />";
	echo "___________________________________________________<br />";
	echo "<br />";
?>
<script type="text/javascript" src="projax/js/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { 
	new nicEditor({fullPanel : true}).panelInstance('ptex'); 
	
	});
</script>
<form method="post" action="edit_edition.php?id=<?php echo $arr[id]?>&act=edit" target="edition" name="user" onsubmit="confir(<?php echo $arr[id]?>);">
<div>
	<label>Post Subject :</label><input type="text" name="PSub" value="<?php echo $arr[post_subject];?>" disabled /><br />
	<label>Post Summary :</label><input type="text" name="PSum" value="<?php echo $arr[post_summary];?>" disabled /><br />
	<label>Post Text</label><br />
	<textarea name="PTex" id="ptex" rows="20" cols="80"><?php echo $arr[post_text];?></textarea><br />
	<label>Small Url Img :</label><input type="text" name="SUrl" value="<?php echo $arr[post_small_img_url];?>" disabled/><br />
	<label>Big Url Img :</label><input type="text" name="BUrl" value="<?php echo $arr[post_big_img_url];?>" disabled/><br />
	<br />
	<input type="submit" name="ok" value="Send" />
</div>
</form>
<?php
ob_end_flush();
?>