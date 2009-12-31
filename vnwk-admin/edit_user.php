<?php
ob_end_clean();
ob_start();
session_start();
//include file	
	include("class_user.php");
	include("common.php");
	include("session.php");
	include(".././core/classes/filter.php");
//check logged user 
	process(session_id(), myip());
	if (!logged_in()) header("location: login.php");
	if(isset($_GET['id']))
		$id = Filter::filterInput($_GET['id'],"login.php",1);
	$local = "edit_user.php?id=".$id;
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
		if(!window.confirm('Do You want edit this user?'))
		{
			location.href = "edit_user.php?id="+id;
			alert('Cancel!');
		}
	}
</script>
<?php
	$str ="where id='".$id."'";
	$u = new user;

	//check edit user 
	if(isset($_GET['act']) && $_GET['act']=='edit')
	{
		$arr = $u->show_user($str);
		if($_POST['user']==NULL){
			$ur = $arr[0][username];
		}
		else{
				$filter_user = Filter::filterInput($_POST['user'],$local,2);
				if($u->check_user($id,$filter_user)==0){
					$ur = $filter_user;
		}
	}
		
		if($_POST['pass']==NULL){
			$ps = $arr[0][password];
		}
		else{
			$filter_pass = Filter::filterInput($_POST['pass'],$local,2);
			$filter_repass = Filter::filterInput($_POST['re_pass'],$local,2);
			
			if($filter_pass == $filter_repass){
				$ps = $filter_pass;
			}
		}
		
		$level = Filter::filterInput($_POST['level'],$local,1);
		//Update
		if($level && $ur && $ps)
		{
			if($level == 2)
				$level = 0;
			$arr_edit = array('username'=>$ur,'password'=>$ps,'level'=>$level);
			$u->edit_user($_GET['id'],$arr_edit);
		}
	}	

	//show user
	$arr = $u->show_user($str);
	echo "Infomation User<br />";
	echo "________________________________<br />";
	echo "<br />";
?>

<form method="post" action="edit_user.php?id=<?php echo $arr[0][id]?>&act=edit" target="user" name="user" onsubmit="confir(<? echo $arr[0][id]?>);">
<div>
	<label>ID :</label><input type="text" name="id2" value="<?php echo $arr[0][id];?>" disabled /><br />
	<label>Username :</label><input type="text" name="user" value="<?php echo $arr[0][username];?>" /><br />
	<label>Password (>=5 characters):</label><input type="password" name="pass" /><br />
	<label>Re_Password :</label><input type="password" name="re_pass" /><br />
	<label>Level :</label>
	<select name="level">Level:
		<option value="1" <?php if($arr[0][level]==1) echo "selected";?>>1</option>
		<option value="2" <?php if($arr[0][level]==0) echo "selected";?>>0</option>
	</select>
	<br />
	<input type="submit" name="ok" value="Send" />
</div>
</form>
<?
ob_end_flush();
?>