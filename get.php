<?php
if(isset($_POST['ok'])){
	include('core/common.php');
	include('core/init.php');
	include('core/classes.php');
	include('core/session.php');
	include('core/filters.php');
	//$re = mysql_query('select user_id,post_id from editions where checked=1 and post_id!=0'); //lan 1
	//$re = mysql_query('select user_id,post_id from editions where checked=0 and post_id!=0'); //lan 2
	while($row = mysql_fetch_assoc($re))
	{
		
		$re2 = mysql_query("select user_id,post_id from follow where user_id=$row[user_id] and post_id=$row[post_id]");
		if(mysql_num_rows($re2) == 0){
				//if(mysql_num_rows($re2) == 0){
					$str = "insert into follow(user_id,post_id,value) values($row[user_id],$row[post_id],1)";
					mysql_query($str);
				//}
			}
	}
	
	//$re = mysql_query('select id from users where level=1');//toan bo code duoi la lan thu 3,dong cai doan code while tren lai
	//while($row = mysql_fetch_assoc($re))
	//{
				//$re2 = mysql_query("select post_id from posts");

				//while($row2 = mysql_fetch_array($re2)){
					//$str = "insert into follow(user_id,post_id,value) values($row[id],$row2[post_id],1)";
					//mysql_query($str);
				//}
	//}
}	
?>
<form action="get.php" method="post">
<input type="submit" name="ok" value="get"/>
</form>