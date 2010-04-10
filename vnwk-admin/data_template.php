<?php
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/common.php");
include("../core/classes/Color.php");
include("../core/classes/Filter.php");
include("../requests/getdata.php");
include("session.php");
	process(session_id(), myip());
	if (!logged_in_admin()) header("location: login.php");
if(isset($_GET["idcolor"]) && isset($_GET["page"]) && isset($_GET["position"])){
//$type_view=1: view index.php
//$type_view=2: view viewtopic.php
	$idcolor = (int)$_GET["idcolor"];
	$page = Filter::filterInput($_GET["page"],"login.php",3);
	$position = Filter::filterInput($_GET["position"],"login.php",3);
	$idcolor = Filter::filterInput($idcolor,"login.php",1);

	$position_page = $page."-".$position;
	$change = new Color;
	$change->update($idcolor,'test',$position_page);
	
	if($page=="index"){
		$url = "http://www.vietnamwiki.net/";
	}
	else if($page=="view"){
		$url = "http://www.vietnamwiki.net/index2.php";
	}

		$extra='<META HTTP-EQUIV="Content-Type" Content="text-html; charset=UTF-8">';
		echo getdata($url,$extra);
	$row = $change->query_setting();
	foreach($row as $value){
		$arr = explode('-',$value['page']);
		if($arr[0]==$page){
		if($arr[0] == 'view'){
			for($i = 1 ; $i < count($arr);	$i++)
			{
				if($arr[$i]=='top'){
		echo "<script>";		
		echo "document.getElementById('header').style.background=\"$value[color]\"";
		echo "</script>";
				}
				if($arr[$i]=='body'){
		echo "<script>";		
		echo "document.body.style.background=\"$value[color] url(../css/images/bg/bg.gif) repeat-y scroll center center\"";
		echo "</script>";		
				}
			}	
		}
		else if($arr[0] == 'index'){
			for($i = 1 ; $i < count($arr);	$i++)
			{
				if($arr[$i]=='top'){
					echo "<script>";
					echo "document.getElementById('top').style.background=\"$value[color]\"";
					echo "</script>";				

				}
				if($arr[$i]=='body'){
					echo "<script>";
					echo "document.body.style.background=\"$value[color]\"";
					echo "</script>";
				}
				if($arr[$i]=='bottom'){
					echo "<script>";
					echo "document.getElementById('slideShowWrapper').style.background=\"$value[color]\"";
					echo "</script>";					
				}
			}
		}
		}
	}
}
?>