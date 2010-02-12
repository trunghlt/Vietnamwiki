<?php
include('../core/common.php');
include('../core/init.php');
include('../core/classes/Db.php');
include('../core/classes/ActiveRecord.php');
include('../core/classes/User.php');
include('../core/classes/PostElement.php');
include('../core/classes/IndexElement.php');
include('../core/classes/DestinationElement.php');
include('../core/classes/Edition.php');
include('../core/classes/Follow.php');
function get_index_des($value,$id_follow,$numrow,$index_id,$post_id,$subject){
$lasted_index = new IndexElement;
$lasted_des = new DestinationElement;
				$title = $subject;
				echo "<div class='comment'>";	
				if($index_id != NULL){
					$lasted_index->query($index_id);
					$index_name = $lasted_index->name;
					
					$lasted_des->query($lasted_index->destId);
					$dest_name = $lasted_des->engName;
				}
				else
					$dest_name ='';
			?> 
				
			<a href="viewtopic.php?id=<?php echo $post_id?>" class="link" style="margin-left: 5px"><?php echo $title?></a>
			<b><span style="color: gray"><?php echo $dest_name;?></span></b>
				<?php
				echo "<span name=".$id_follow.">";
					if($value==1)
						echo "<a onclick='changevalue($value,$id_follow,$numrow)'class='link'>Follow</a>";
					else
						echo "<a onclick='changevalue($value,$id_follow,$numrow)' class='link'>Not Follow</a>";
				?>
				</span>
				<br/>
				</div>
				<?php
}
$type = $_POST['t'];
$s = isset($_POST['s']);
$s1 = isset($_POST['s1']);
$row_per_page = 5;
if($s)
{
	$start = $_POST['s'];
}
else{
	$start = 0;
}
if($s1)
{
	$start1 = $_POST['s1'];
}
else{
	$start1 = 0;
}
if($type == 1 ){
$e = new Edition;

			$arr = $e->query_string(1,myUser_id(myip()));
			$num = $arr['n'];
			if( $num > $row_per_page)
			{
				$num_page = ceil($num/$row_per_page);
			}
			else{
				$num_page = 1;
			}
			
			$result = $e->query_string(1,myUser_id(myip()),'',$start,$row_per_page);
			echo "<div class='comment_block'>";					
			
			if ($result == 0) {
				echo "You haven't posted any topics yet";
				return;
			}
		array_pop($result);
		$f = new Follow;							
		foreach($result as $key=>$post){
				$r = $f->query_post($post['post_id'],$post['user_id']);
				//GET NUM ROWs
				foreach($r as $row){ 
					$numrow1 = Edition::get_num($row["post_id"],$row['user_id']);
					get_index_des($row['value'],$row['id'],$numrow1,$post["index_id"],$row['post_id'],$post["post_subject"]);
				}
		}
			echo "<br />";
			if($num_page > 1){
				$current_page = ($start/$row_per_page)+1;
				for($i=1 ; $i <= $num_page; $i++)
				{
					if($current_page != $i){
						$k = ($i-1)*$row_per_page;
					?>
						<a href='#' onclick="get_page(<?php echo $k?>,1,'followpost')" ><?php echo $i?></a>
					<?php
					}
					else
						echo " ".$i." ";
				}
			}
			echo "</div>";
}
else if($type == 0){
$e1 = new Edition;
			$arr1 = $e1->query_string(0,myUser_id(myip()),0);
			
			$num = $arr1['n'];
			if( $num > $row_per_page)
			{
				$num_page = ceil($num/$row_per_page);
			}
			else{
				$num_page = 1;
			}
			$result1 = $e1->query_string(0,myUser_id(myip()),0,$start1,$row_per_page);
			echo "<div class='comment_block'>";					
			
			if ($result1 == 0) {
				echo "You haven't posted any topics yet";
				return;
			}
			array_pop($result1);
			$f = new Follow;
			foreach($result1 as $key=>$post1) {
					$r1 = $f->query_post($post1['post_id'],$post1['user_id']);
					//GET NUM ROWs
					foreach($r1 as $row1){
						$numrow1 = Edition::get_num($row1["post_id"],$row1['user_id']);
						get_index_des($row1['value'],$row1['id'],$numrow1,$post1["index_id"],$row1['post_id'],$post1["post_subject"]);
				 }
			}
			echo "<br />";
			if($num_page > 1){
				$current_page = ($start/$row_per_page)+1;
				for($i=1 ; $i <= $num_page; $i++)
				{
					if($current_page != $i){
						$k = ($i-1)*$row_per_page;
					?>
						<a href='#' onclick="get_page(<?php echo $k?>,0,'followedit')" ><?php echo $i?></a>
					<?php
					}
					else
						echo " ".$i." ";
				}
			}
			echo "</div>";
}
?>