<?php
@ob_start();
session_start();
//include file	
	include("class_user.php");
	include("common.php");
	include("session.php");
	include("../core/classes/Filter.php");
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
<link href="admin.css" rel="stylesheet" type="text/css" />
<!--Confirm-->
<script language="javascript" type="text/javascript">
	function confir(id){
		if(!window.confirm('Do you want edit this user?'))
		{
			location.href = "edit_user.php?id="+id;
			return false;
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
		$level = Filter::number($_POST['level']);
		//Update
		if($level)
		{
			if($level == 2)
				$level = 0;
			$arr_edit = $level;
			$u->edit_user($_GET['id'],$arr_edit);
		}
	}	

	//show user
	$arr = $u->show_user($str);
echo "<body id='e_user'>";
	echo "<h3>Infomation User</h3><br />";
	echo "________________________________<br />";
	echo "<br />";
?>

<form method="post" action="edit_user.php?id=<?php echo $arr[0]['id']?>&act=edit" target="user" name="user" >
<div>
	<label>ID :</label><input type="text" name="id2" value="<?php echo $arr[0]['id'];?>" disabled /><br />
	<label>Username :</label><input type="text" name="user" value="<?php echo $arr[0]['username']; ?>" disabled/><br />
	<label>Level :</label>
	<select name="level">Level:
		<option value="1" <?php if($arr[0]["level"]==1) echo "selected";?>>1</option>
		<option value="2" <?php if($arr[0]["level"]==0) echo "selected";?>>0</option>
	</select>
	<br />
	<input type="submit" name="ok" value="Edit User" onclick="return confir(<?php echo $arr[0]['id']?>);"/>
</div>
</form>
</body>
<?php
@ob_end_flush();
?>