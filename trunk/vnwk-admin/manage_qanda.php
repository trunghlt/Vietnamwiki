<?php
@ob_end_clean();
@ob_start();
session_start();
//include file
        include("db.php");
	include(".././core/classes/ActiveRecord.php");
        include(".././core/classes/Questions.php");
	include("common.php");
	include("session.php");
        include("memcache.php");
	include(".././core/classes/Filter.php");
//check logged user 
	process(session_id(), myip());
	if (!logged_in()) header("location: login.php");
	if(isset($_GET['id']))
		$id = Filter::filterInput($_GET['id'],"login.php",1);
?>
<link href="admin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
	function confir(id,start){
		if(window.confirm('Do You want delete this question?'))
		{
                        location.href = "manage_qanda.php?id="+id+"&act=del&s="+start;
		}
	}
</script>
<body id="man_question">
<h3>Manage Questions And Answers</h3>
<?php
	$q = new Questions;
//--------delete questions---------------//
	if(isset($_GET['act']) && $_GET['act']=='del')
	{
		$q->delete_id($id);
?>
<script language="javascript" type="text/javascript">
	parent.qanda.location.href = 'nofound.html';
</script>
<?php
	}

//----------------------------------//
	$arr1 = array();
        $arr1 = $q->query();
	
	$row_per_page = 5;
        $count_record = 0;
        

//if it has value
if(count($arr1) > 0 && $arr1!=NULL){
        $count_record = count($arr1);
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
	
//show questions
        $arr=array();
        $user = "Unknown";
	$arr = $q->query("", "$start,$row_per_page", "");
 if(count($arr)>0 && $arr1!=NULL){
	foreach($arr as $key=>$value){
	   if($value!=NULL)
	   {
			echo "<div style='width:400px;clear:left;'>";
			if($value['email']!="" && $value['email']!=null)
                             $user = $value['email'];
                        if($value['username']!="" && $value['username']!=null)
                             $user = $value['username'];
                        if($value['user_id']!="" && $value['userid']!=null && $value['userid']!=0){
                            $active = new Active;
                            $arr_user = array();
                            $arr_user = $active->select("", "users", "id=$value[user_id]");
                            $user = $arr_user[0]['username'];
                        }
                        echo "<div><h4>Email: $user</h4></div>";
                        echo "<div style='width:300px;'>$value[content]</div><br />";
			echo "<div float='left'><a href=edit_answers.php?id=$value[id] target='qanda' >(Answers)</a>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='confir($value[id],$start);' >(x)</a></div>";
			echo "</div>";
                        echo "<hr width=300px>";
		}
	}
echo "<br/>";	
	//break page
	if($num_page > 1){

		$current_page = ($start/$row_per_page)+1;
		for($i=1 ; $i <= $num_page; $i++)
		{
			if($current_page != $i)
				echo "<a href='manage_qanda.php?s=".($i-1)*$row_per_page."&page=$num_page'> ".$i." </a>";
			else
				echo " ".$i." ";
		}
	}
 }
}
echo "</body>";
ob_end_flush();
?>