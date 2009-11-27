<?php
include('core/common.php');
die_to_index();
include('core/init.php');
include('core/classes/Db.php');
include('core/classes/User.php');
include('core/classes/PostElement.php');
include('core/session.php');
include("ajax_header.php");	
include("header.php");
include('topRibbon.php');
?>


<!--User info-->
<?php
	if (!isset($_REQUEST["username"])) die_to_index();
	$username = htmlspecialchars($_REQUEST["username"], ENT_QUOTES);
	$sql = "SELECT * 
			FROM users
			WHERE username = '".$username."'";
	$result = mysql_query($sql) or die(mysql_error());
	if (!($row = mysql_fetch_array($result))) die_to_index();
	$user_info = $row;
?>

<?php
	if (check_logged_in(myip())&&isset($_POST["message"])) {
		$mess = htmlspecialchars($_POST["message"], ENT_QUOTES);
		$s_id = myUser_id(myip());
		$r_id = $user_info["id"];
		$post_time = time();	
		$sql = "INSERT INTO messages
				(s_id, r_id, content, post_time)
				VALUE ('".$s_id."','".$r_id."','".$mess."','".$post_time."')";
		mysql_query($sql) or die(mysql_error());
	}
?>

<table class="contentTable">
<tbody>
<tr>
<td width="20%" valign="top" bgcolor="#e7e6e6" style="margin-left: 5px">
	<?php if (!check_logged_in(myip())) die_to_index();?>

	<div>
	<table width="100%">
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
		<span class="style3"><?php echo $user_info["firstname"] . " " . $user_info["familyname"];?></span>
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
		<span class="style3"><?php echo $user_info["location"];?></span>
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
			$timelabel = date("d M, Y", $user_info["regdate"]);
			echo $timelabel;			
		?>
		</span>
		</td>
	</tr>	
	</tbody>
	</table>
		<p><button onclick="update_click()">Update</button></p>
	</div>
</td>
	<td>
	<table width=100%>
	<tbody>
	<!--Latest post-->
	<tr>
		<h1>Latest posts</h1>
		<?php
			$sql = "SELECT * FROM posts
					WHERE post_username = '".$username."'";
			$result = mysql_query($sql) or die(mysql_error());
			echo "<div class='comment_block'>";					
			
			$numrow = mysql_num_rows($result);
			if ($numrow == 0) {
				echo "You haven't posted any topics yet";
			}
						
			while ($row = mysql_fetch_array($result)) {
				$sql = "SELECT post_subject
						FROM posts_texts
						WHERE post_id = '".$row["post_id"]."'";
				$re = mysql_query($sql) or die(mysql_error());
				$post = mysql_fetch_array($re);
				$title = $post["post_subject"];
				
				$sql = "SELECT *
						FROM index_menu
						WHERE id = '".$row["index_id"]."'";
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
				<div class='comment'>
				<a href="viewtopic.php?id=<?php echo $row["post_id"]?>" class="link" style="margin-left: 5px"><?php echo $title?></a>,
				<b><span style="color: gray"><?php echo $dest_name;?></span></b>
				<br/>
				</div>
				<?php
			}
			echo "</div>";
		?>
	</tr>
	<!--Latest comments-->
	<tr>
		<h1>Latest comments</h1>
		<?php
			$sql = "SELECT *
					FROM `comments`
					WHERE (SELECT post_username
						   FROM posts
						   WHERE post_id = `comments`.post_id) = '".$username."'
					ORDER BY comment_id DESC";
			$result = mysql_query($sql) or die(mysql_error());
			
				//write comments
				function write_comment($row) {
					$post_id = $row["post_id"];
					echo "<div class='comment'>";
					//echo entry
					$sql = "SELECT *
						FROM posts_texts
						WHERE post_id = '".$post_id."'";
					$r1 = mysql_query($sql) or die(mysql_error());
					$x = mysql_fetch_array($r1);
					echo "<span class='style5'>Entry: " . $x["post_subject"] . "</span>";
					
					//comment content
					echo "<div class='content'>";
					echo htmlspecialchars($row["comment_text"]);
					echo "</div>";
					
					//poster detail					
					$sql = "SELECT *
						FROM users
						WHERE id='".$row["user_id"]."'";
					$r1 = mysql_query($sql) or die(mysql_error());
					$x = mysql_fetch_array($r1);
					$posttime = $row['comment_time'];
					$timelabel = date("d M, Y H:i", $posttime);
					echo "<span class='style4'>posted by " . $x["firstname"] . " " . $x["familyname"] ." at " . $timelabel . "</span>";
					
					echo "</div>";				
				}
				
			echo "<div class='comment_block'>";		
			
			$numrow = mysql_num_rows($result);
			if ($numrow == 0) {
				echo "There is no comment on your topics till now";
			}
			
			while ($row = mysql_fetch_array($result)) {
				write_comment($row);
			}
			echo "</div>";				
		?>
	</tr>
	<!--Private messages-->
	<tr>
		<h1>Private messages</h1>
		<?php
			echo "<div class='comment_block'>";	
			$sql = "SELECT *
					FROM messages
					WHERE r_id = '".$user_info["id"]."'";
			$result = mysql_query($sql) or die(mysql_error());
			
			$numrow = mysql_num_rows($result);
			if ($numrow == 0) {
				echo "There is no message till now";
			}
			
			while ($row = mysql_fetch_array($result)) {
				echo "<div class='comment'>";
				
				echo "<div class='content'>";
				echo $row["content"];
				echo "</div>";
				
				//poster detail					
				$sql = "SELECT *
					FROM users
					WHERE id='".$row["s_id"]."'";
				$r1 = mysql_query($sql) or die(mysql_error());
				$x = mysql_fetch_array($r1);				
				$posttime = $row['post_time'];
				$timelabel = date("d M, Y H:i", $posttime);
				echo "<span class='style4'>posted by " . $x["firstname"] . " " . $x["familyname"] ." at " . $timelabel . "</span>";
				echo "</div>";
			}
			
			echo "</div>";
			echo "<br/><br/>";				
			if (check_logged_in(myip())) {
				?>
				<span class="style1">Posting new messages for <?php echo $user_info["firstname"] ?></span>
				<div style="border: 1px solid black; background-color:#FFFF66" >
				<form  id="message" method="post" action="profile.php?username=<?php echo $username;?>">
					<textarea name="message" id="message" rows="3" style="width: 98%"></textarea>					
					<p align="center"><input type="submit" value="submit"/></p>
				</form>
				</div>
				<?php
			}
		?>
	</tr>
	</tbody>
	</table>
	</td>
</tr>
</tbody>
</table>

<?php
include("footer.php");
?>

<?php
	include("private_form.php");
?>

<script type="text/javascript">
	function update_click() {
		popup_show('private_popup', 'private_popup_drag', 'private_popup_exit', 'long-form', 10, 10);
	}
</script>

</body>
</html>
