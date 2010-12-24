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
				if($subject!='')
					$title = $subject;
				else
					$title = 'view';
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
$user_id_info = $_POST['user_id'];
$row_per_page = 10;
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
$f = new Follow;
if($type==1){
			$arr = $f->query_string($user_id_info);
                        if(is_array($arr)){
                            $num = $arr['n'];
                        }
                        else
                            $num = 0;
			
			if ($num == 0) {
				echo "You haven't posted any topics yet";
				return;
			}
						
			if( $num > $row_per_page)
			{
				$num_page = ceil($num/$row_per_page);
			}
			else{
				$num_page = 1;
			}
			
			$result = $f->query_string($user_id_info,"",$start,$row_per_page);
			echo "<div class='comment_block'>";					
                if(is_array($result)){
                    array_pop($result);
                    $e = new Edition;

                    foreach($result as $key=>$post){
                                    $r = $e->query_post($post['post_id'],$post['user_id'],$type);
                                    if(isset($r) && $r!='')
                                            foreach($r as $row){
                                                    get_index_des($post['value'],$post['id'],1,$row['index_id'],$post['post_id'],$row['post_subject']);
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
                                                    <a href='#' onclick="get_page(<?php echo $k?>,1,'followpost',<?php echo $user_id_info?>)" ><?php echo $i?></a>
                                            <?php
                                            }
                                            else
                                                    echo " ".$i." ";
                                    }
                            }
                            echo "</div>";
                }
                else{
                    echo "You haven't posted any topics yet";
                    return;
                }
}
else if($type == 0){
$f = new Follow;
			$num1 = Edition::get_num($user_id_info);
			
			if($num1['n'] == 0)
			{
				echo "You haven't posted any topics yet";
				return;					
			}
			
			$num = $num1['n'];
			if( $num > $row_per_page)
			{
				$num_page = ceil($num/$row_per_page);
			}
			else{
				$num_page = 1;
			}
			array_pop($num1);
			
			foreach($num1 as $value){
				
				$arr = $f->query_string($user_id_info,$value['post_id'],$start1,$row_per_page);
                                if(!is_array($arr))
                                {
                                    echo "You haven't posted any topics yet";
                                    return;
                                }
				array_pop($arr);
				
					foreach($arr as $value2)
						$result1[] = $value2;
									
			}
			
			echo "<div class='comment_block'>";	
			$e = new Edition;
			foreach($result1 as $key=>$post1) {
					$r1 = $e->query_post($post1['post_id'],$post1['user_id'],0);
					//GET NUM ROWs
					if(isset($r1) && $r1!='')
					foreach($r1 as $row1){
						get_index_des($post1['value'],$post1['id'],$num,$row1["index_id"],$post1['post_id'],$row1["post_subject"]);
				 }
			}
			echo "<br />";
			if($num_page > 1){
				$current_page = ($start1/$row_per_page)+1;
				for($i=1 ; $i <= $num_page; $i++)
				{
					if($current_page != $i){
						$k = ($i-1)*$row_per_page;
					?>
						<a href='#' onclick="get_page(<?php echo $k?>,0,'followedit',<?php echo $user_id_info?>)" ><?php echo $i?></a>
					<?php
					}
					else
						echo " ".$i." ";
				}
			}
			echo "</div>";
}
?>