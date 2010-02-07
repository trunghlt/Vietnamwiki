<?php
include('../core/common.php');
include('../core/init.php');
include('../core/classes/Db.php');
include('../core/classes/User.php');
include('../core/classes/PostElement.php');
function get_index_des($value,$id_follow,$numrow,$index_id,$post_id,$subject){
				$title = $subject;
				echo "<div class='comment'>";	
				if($index_id != NULL){
					$sql = "SELECT *
							FROM index_menu
							WHERE id = '".$index_id."'";
					$re = mysql_query($sql) or die(mysql_error());
					$index = mysql_fetch_array($re);
					$index_name = $index["name"];
					
					$sql = "SELECT EngName
							FROM destinations
							WHERE id = '".$index["dest_id"]."'";
					$re = mysql_query($sql) or die(mysql_error());
					$dest = mysql_fetch_array($re);
					$dest_name = $dest["EngName"];
			?> 
				
				<a href="viewtopic.php?id=<?php echo $post_id?>" class="link" style="margin-left: 5px"><?php echo $title?></a>,
				<b><span style="color: gray"><?php echo $dest_name;?></span></b>
				<?php
					}
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
function get_num($post_id,$user_id){
			$str = "SELECT *
					FROM editions
					WHERE post_id = '".$post_id."' and user_id=$user_id";
			$re_row = mysql_query($str) or die(mysql_error());		
			return mysql_num_rows($re_row);
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
			$sql_page = "SELECT *
					FROM editions
					WHERE checked=1 and user_id=".myUser_id(myip())."";
			$res_page = mysql_query($sql_page) or die(mysql_error());
			$num = mysql_num_rows($res_page);
			if( $num > $row_per_page)
			{
				$num_page = ceil($num/$row_per_page);
			}
			else{
				$num_page = 1;
			}

			$sql = "SELECT *
					FROM editions
					WHERE checked=1 and user_id=".myUser_id(myip())." limit $start,$row_per_page";
			$result = mysql_query($sql) or die(mysql_error());
			echo "<div class='comment_block'>";					
			
			$numrow = mysql_num_rows($result);
			if ($numrow == 0) {
				echo "You haven't posted any topics yet";
				return;
			}							
			while ($post = mysql_fetch_array($result)) {
			
				$str = "SELECT *
						FROM follow
						WHERE post_id=$post[post_id] and user_id=$post[user_id]";
				$re = mysql_query($str) or die(mysql_error());
				$row = mysql_fetch_array($re);
				//GET NUM ROWs 
				$numrow1 = get_num($row["post_id"],$row['user_id']);
				
				get_index_des($row['value'],$row['id'],$numrow1,$post["index_id"],$row['post_id'],$post["post_subject"]);
			
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
			$sql_page = "SELECT *
					FROM editions
					WHERE checked=0 and user_id=".myUser_id(myip())." and post_id!=0";
			$res_page = mysql_query($sql_page) or die(mysql_error());
			$num = mysql_num_rows($res_page);
			if( $num > $row_per_page)
			{
				$num_page = ceil($num/$row_per_page);
			}
			else{
				$num_page = 1;
			}
			$sql = "SELECT *
					FROM editions
					WHERE checked=0 and user_id=".myUser_id(myip())." and post_id!=0 limit $start1,$row_per_page";
			$result = mysql_query($sql) or die(mysql_error());
			echo "<div class='comment_block'>";					
			
			$numrow = mysql_num_rows($result);
			if ($numrow == 0) {
				echo "You haven't posted any topics yet";
				return;
			}
					
			while ($post = mysql_fetch_array($result)) {
				
				$str = "SELECT *
						FROM follow
						WHERE post_id=$post[post_id] and user_id=$post[user_id]";
				$re = mysql_query($str) or die(mysql_error());
				$row = mysql_fetch_array($re);
				//GET NUM ROWs 
				$numrow1 = get_num($row["post_id"],$row['user_id']);
									
					get_index_des($row['value'],$row['id'],$numrow1,$post["index_id"],$row['post_id'],$post["post_subject"]);
				 
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

