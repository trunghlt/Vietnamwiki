<?
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
?>
<style>
	a{
		text-decoration:none;
	}
	a:visited{
		color:#0000FF;
	}
	a:hover{
		color:#00FF00;
	}
</style>
<script language="javascript" type="text/javascript">
	function confir(id,num_page,start){
		if(window.confirm('Do You want delete this user?'))
		{
			location.href = "manage_user.php?id="+id+"&act=del&s="+start+"&page="+num_page;
		}
	}
</script>
<?
	$u = new user;
//--------delete user---------------//	
	if(isset($_GET['act']) && $_GET['act']=='del')
	{
		$u->del_user($id);
	}
	
//---------ban user-----------------//
	if(isset($_GET['act']) && $_GET['act']=='ban')
	{
		if(Filter::filterInput($_GET['value'],"login.php",1))
		{
			$arr_ban = array(ban_user=>0);
			$u->edit_user($id,$arr_ban);
		}
		else{
			$arr_ban = array(ban_user=>1);
			$u->edit_user($id,$arr_ban);
		}
	}

//----------------------------------//
	$arr = $u->show_user($str);
	
	$row_per_page = 5;
	$count_record = count($arr);

//Pages
		if( $count_record > $row_per_page)
		{
			$num_page = ceil($count_record/$row_per_page);
		}
		else{
			$num_page = 1;
		}

//start position 
	if(isset($_GET['s']))
	{
		$start = Filter::filterInput($_GET['s'],"login.php",1);
	}
	else{
		$start = 0;
	}
	
//show user
	$str = " limit $start,$row_per_page";
	$arr = $u->show_user($str);
	foreach($arr as $key=>$value){
	   if($value!=NULL)
	   {
			echo "<div style='width:400px;'>";
			echo "<a href='edit_user.php?id=$value[id]' target='user' style='width:50px;float:left;'>(Edit)</a>";
			echo "<label style='width:160px;float:left;'>$value[username]</label>";
			if($value[ban_user]==0)
				echo "<a href='manage_user.php?id=$value[id]&act=ban&value=0&page=$num_page&s=$start' style='width:60px;float:left;'>(Allowed)</a>";
			else
				echo "<a href='manage_user.php?id=$value[id]&act=ban&value=1&page=$num_page&s=$start' style='width:60px;float:left;'>(Banned)</a>";
			echo "<a href='#' onclick='confir($value[id],$num_page,$start);' style='width:10px;float:left;'>(x)</a>";
			echo "</div>";
			echo "<br />";
		}
	}
echo "<br/>";	
	//break page
	if($num_page > 1){

		$current_page = ($start/$row_per_page)+1;
		for($i=1 ; $i <= $num_page; $i++)
		{
			if($current_page != $i)
				echo "<a href='manage_user.php?s=".($i-1)*$row_per_page."&page=$num_page'> ".$i." </a>";
			else
				echo " ".$i." ";
		}
	}
ob_end_flush();
?>