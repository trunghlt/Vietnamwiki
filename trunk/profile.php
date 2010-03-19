<?php
include('core/common.php');
if (!isset($_REQUEST["username"])) die_to_index();
include('core/init.php');
include('core/classes/Db.php');
include('core/classes/User.php');
include('core/classes/PostElement.php');
include('core/classes/Message.php');
include('core/classes/IndexElement.php');
include('core/classes/DestinationElement.php');
include('core/classes/CommentElement.php');
include('core/session.php');
include("ajax_header.php");	
include("header.php");
include('topRibbon.php');
?>


<!--User info-->
<?php
	$user = new User;
	$username = htmlspecialchars($_REQUEST["username"], ENT_QUOTES);
	$user_info = $user->query_username($username);
	if ($user_info==0) die_to_index();
?>

<?php
$message = new Message;
	if (check_logged_in(myip())&&isset($_POST["message"])) {
		$mess = htmlspecialchars($_POST["message"], ENT_QUOTES);
		$s_id = myUser_id(myip());
		$r_id = $user_info["id"];
		$post_time = time();
		$message->insert($s_id,$r_id,$mess,$post_time);
	}
?>
<style>
	#contentTable{
	background-color: white;
	clear:both;
	width:1200;
	margin:auto;
}
</style>
<script>
	function follow(type,dom,id)
	{
		jQuery.post('requests/profile.php',{t:type,d:dom,user_id:id},function(data){
												document.getElementById(dom).innerHTML = data;
											});
	}
</script>
<table id="contentTable" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td width="20%" valign="top" bgcolor="#e7e6e6" >
	
	<table>
	<tbody>
	<tr>
		<td colspan=2 align="center">
		<h1>Profile</h1>
		<!--Avatar-->
		<?php include("avatar.php"); ?>
		</td>
	</tr>
	<!--Full name-->
	<tr>
		<td>
		<span class="style2">Full Name :</span> 
		</td>
		<td>
		<span class="style3"><?php echo $user_info["firstName"] . " " . $user_info["lastName"];?></span>
		</td>
	</tr>
	<!--DOB-->
	<tr>
		<td>
		<span class="style2">DOB : </span> 
		</td>
		<td>
		<span class="style1">
		<?php 
			$timelabel = date("d M, Y", $user_info["dob"]);
			echo $timelabel;
		?>
		</span>
		</td>
	</tr>
	<!--Location-->
	<tr>
		<td>
		<span class="style2">Location : </span>
		</td>
		<td>
		<span class="style3"><?php echo $user_info["locationCode"];?></span>
		</td>
	</tr>
	<!--Member from-->
	<tr>
		<td>
		<span class="style2">Member from : </span> 
		</td>
		<td>
		<span class="style1">
		<?php 
			$timelabel = date("d M, Y", $user_info["regDateTime"]);
			echo $timelabel;			
		?>
		</span>
		</td>
	</tr>	
	</tbody>
	</table>
	<?php if (check_logged_in(myip()) && $username==myUsername(myip())) {?>
		<p><button onclick="update_click()">Update</button></p>
		<?php }?>
	</div>
</td>
	<td>
	<table width=100%>
	<tbody>
	<!--Latest post-->
	<tr>
		<h1>Latest posts</h1>
		<?php
			$lasted_post = new PostElement;
			$result = $lasted_post->query_username($username);
			echo "<div class='comment_block'>";					
			
			if ($result == 0) {
				echo "You haven't posted any topics yet";
			}
			else{			
			foreach($result as $row) {
				$lasted_post->query($row["post_id"]);
				$title = $lasted_post->title;
				
				 $lasted_index = new IndexElement;
				 $lasted_index->query($row["index_id"]);
				 $index_name = $lasted_index->name;
				
				 $lasted_des = new DestinationElement;
				 $lasted_des->query($lasted_index->destId);
				 $dest_name = $lasted_des->engName;
					?> 
				<div class='comment'>
				<a href="viewtopic.php?id=<?php echo $row["post_id"]?>" class="link" style="margin-left: 5px"><?php echo $title?></a>,
				<b><span style="color: gray"><?php echo $dest_name;?></span></b>
				<br/>
				</div>
				<?php
				}
			}
			echo "</div>";
		?>
	</tr>
	<?php if((check_logged_in(myip()) && $username==myUsername(myip()))) {?>
	<!--Follow post-->
	<tr>
		<h1>Follow's posts</h1>
		<div id='followpost'></div>
		<script language="javascript">
			follow(1,'followpost',<?php echo $user_info['id']?>);
		</script>
	</tr>
	<!--Lastest post-->
	<tr>
		<h1>Last edit</h1>
		<div id='followedit'></div>
		<script>
			follow(0,'followedit',<?php echo $user_info['id']?>);
		</script>
	</tr>
<?php }?>
	<!--Latest comments-->
	<tr>
		<h1>Latest comments</h1>
		<?php
				$last_comment = new CommentElement;
					$result = $last_comment->query_get_posts($username);
			
				//write comments
				function write_comment($row) {
					$post_id = $row["post_id"];
					echo "<div class='comment'>";
					//echo entry
					$x = new PostElement;
					$x->query($post_id);
					echo "<span class='style5'>Entry: " . $x->title . "</span>";
					
					//comment content
					echo "<div class='content'>";
					echo htmlspecialchars($row["comment_text"]);
					echo "</div>";
					
					//poster detail					
					$r1 = new User;
					$x = $r1->query_id($row["user_id"]);
					$posttime = $row['comment_time'];
					$timelabel = date("d M, Y H:i", $posttime);
					if($x != 0 && $x["firstName"]!='' && $x["lastName"]!='')
					echo "<span class='style4'>posted by " . $x["firstName"] . " " . $x["lastName"] ." at " . $timelabel . "</span>";
					else
					echo "<span class='style4'>posted by guess at " . $timelabel . "</span>";						
					echo "</div>";				
				}
				
			echo "<div class='comment_block'>";		
			
			if ($result == 0) {
				echo "There is no comment on your topics till now";
			}
			else{
				foreach($result as $row) {
					write_comment($row);
				}
			}
			echo "</div>";				
		?>
	</tr>
	<!--End Latest comments-->
	<!--Private messages-->
	<tr>
		<h1>Private messages</h1>
		<?php
			echo "<div class='comment_block'>";	
			$result = $message->query_user_id($user_info["id"]);
			if ($result == 0) {
				echo "There is no message till now";
			}
			else{
				foreach($result as $row) {
					echo "<div class='comment'>";
					
					echo "<div class='content'>";
					echo $row["content"];
					echo "</div>";
					
					//poster detail
					$mes_user = new User;	
					$x = $mes_user->query_id($row["s_id"]);

						$posttime = $row['post_time'];
						$timelabel = date("d M, Y H:i", $posttime);
						echo "<span class='style4'>posted by " . $x["firstName"] . " " . $x["lastName"] ." at " . $timelabel . "</span>";
						echo "</div>";					
				}
			}
			echo "</div>";
			echo "<br/><br/>";				
			if (check_logged_in(myip())) {
				?>
				<span class="style1">Posting new messages for <?php echo $user_info["firstName"] ?></span>
				<div style="border: 1px solid black; background-color:#FFFF66" >
				<form  id="message" method="post" action="profile.php?username=<?php echo $username;?>">
					<textarea name="message" id="message" rows="3" style="width: 98%"></textarea>					
					<p align="center"><input type="submit" value="submit"/></p>
				</form>
				</div>
				<?php
			}
		?>
	<!--End Private messages-->
	</tr>
	</tbody>
	</table>
	</td>
</tr>
</tbody>
</table>
<?php
include("private_form.php");
include("footer.php");
?>
<script type="text/javascript">
	function update_click() {
		private_Dialog.dialog('open');
	}
	function changevalue(value,followid,numrow){
		jQuery.post('requests/changevalue.php',{vl:value,id:followid},function(data){
			if(data=='0'){
			if(numrow==1){
				document.getElementsByName(followid)[0].innerHTML = "<a onclick='changevalue(0,"+followid+","+numrow+")' class='link'>Not Follow</a>";
				}
				else{
					for(i=0;i < numrow;i++){
						document.getElementsByName(followid)[i].innerHTML = "<a onclick='changevalue(0,"+followid+","+numrow+")' class='link'>Not Follow</a>";					
					}
				}
			}
			else if(data=='1')
			{
			if(numrow==1){
				document.getElementsByName(followid)[0].innerHTML = "<a onclick='changevalue(1,"+followid+","+numrow+")' class='link'>Follow</a>";
				}
				else{
					for(i=0;i < numrow;i++){
						document.getElementsByName(followid)[i].innerHTML = "<a onclick='changevalue(1,"+followid+","+numrow+")' class='link'>Follow</a>";					
					}
				}
			}
			else
				alert(data);
		});
	}
	function get_page(start,type,dom,id){
		if(type==1){
			jQuery.post('requests/profile.php',{s:start,t:type,d:dom,user_id:id},function(data){
													document.getElementById(dom).innerHTML = data;
												});
		}
		else if(type==0){
			jQuery.post('requests/profile.php',{s1:start,t:type,d:dom},function(data){
													document.getElementById(dom).innerHTML = data;
												});
		}		
	}
</script>

</body>
</html>
