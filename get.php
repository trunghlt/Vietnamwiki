<?php
if(isset($_POST['ok'])){
	include('core/common.php');
	include('core/init.php');
	include('core/classes.php');
	include('core/session.php');
	include('core/filters.php');
	$re = mysql_query('select post_username,post_id from posts ');
	while($row = mysql_fetch_assoc($re))
	{
		$str = "insert into follow(username,post_id) values('$row[post_username]',$row[post_id])";
		mysql_query($str);
	}
}	
?>
<form action="get.php" method="post">
<input type="submit" name="ok" value="get"/>
</form>