<?
session_start();
//include file	
	include("class_user.php");
	include("common.php");
	include("session.php");
//check logged user 
	process(session_id(), myip());
	if (!logged_in()) header("location: login.php");
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
<?
	$str ="where id='".$_GET['id']."'";
	$u = new user;
	$error=array();
	//check edit user 
	if(isset($_GET['act']) && $_GET['act']=='edit')
	{
		$arr = $u->show_user($str);
		if($_POST['user']==NULL){
			$ur = $arr[0][username];
		}
		else{
			if($u->check_user($_GET['id'],$_POST['user'])==0){
				$ur = $_POST['user'];
			}
			else{
				$error[] = "<span>Username exsisted</span><br />";
			}
		}
		
		if($_POST['pass']==NULL){
			$ps = $arr[0][password];
		}
		else{
			if($_POST['pass'] != $_POST['re_pass']){
				$error[] = "<span>Re_password not match </span><br />";
			}
			else{
				$ps = $_POST['pass'];
			}
		}
		$level = $_POST['level'];
		//Update
		if($level && $ur && $ps)
		{
			if($level == 2)
				$level = 0;
			$arr_edit = array('username'=>$ur,'password'=>$ps,'level'=>$level);
			$u->edit_user($_GET['id'],$arr_edit);
		}
	}	
	if(count($error)){
		foreach($error as $value)
		{
			echo "$value";
		}
		reset($error);
	}
	
	//show user
	$arr = $u->show_user($str);
	echo "Infomation User<br />";
	echo "________________________________<br />";
	echo "<br />";
?>

<form method="post" action="edit_user.php?id=<? echo $arr[0][id]?>&act=edit" target="user" name="user" onsubmit="confir(<? echo $arr[0][id]?>);">
<div>
	<label>ID :</label><input type="text" name="id2" value="<? echo $arr[0][id];?>" disabled /><br />
	<label>Username :</label><input type="text" name="user" value="<? echo $arr[0][username];?>" /><br />
	<label>Password :</label><input type="password" name="pass" /><br />
	<label>Re_Password :</label><input type="password" name="re_pass" /><br />
	<label>Level :</label>
	<select name="level">Level:
		<option value="1" <? if($arr[0][level]==1) echo "selected";?>>1</option>
		<option value="2" <? if($arr[0][level]==0) echo "selected";?>>0</option>
	</select>
	<br />
	<input type="submit" name="ok" value="Send" />
</div>
</form>