<?php
include('core/common.php');
include('core/init.php');
include('core/classes/Db.php');
include('core/classes/User.php');
include('core/classes/PostElement.php');
include('core/classes/DestinationElement.php');
include('core/session.php');
include("ajax_header.php");
	

db_connect();

//get session_id
$session_id = get_session_id();

//get code            
$right_code = image_code();
		
$username               = isset($_POST['username'])? $_POST['username'] : null;
$password               = isset($_POST['password'])? $_POST['password'] : "";
$confirm_password       = isset($_POST['confirm_password'])? $_POST['confirm_password'] : "";
$confirm_code           = isset($_POST['confirm_code'])? $_POST['confirm_code'] : "";
$email           		= isset($_POST['email'])? $_POST['email'] : "";
$x = 0;
		
function error_alert($s){
	$x = htmlspecialchars($s);
	echo '<h3> * '.$x.'</h3>';
}

if (isset($username)) {								
    $s = array();
	$username = strtolower($username);
	$i = 0; $s[$i] = check_username($username);
	$i++; $s[$i] = check_password($password);
	$i++; $s[$i] = check_confirm_password($confirm_password, $password);
	$i++; $s[$i] = check_confirm_image($confirm_code, $right_code);
	$i++; $s[$i] = check_email($email);
	$x = 1;
	foreach ($s as $error) {
		if ($error !== 'ok') $x = 0;
	}				
	if ($x) { //all information is valid
	    db_connect();	
		$regdate = time();
		$sql = "INSERT INTO users (username, password, email, regDateTime) VALUE ('".
		$username."','".$password."','".$email."','".$regdate."')";
		$result = mysql_query($sql) or die(mysql_error());
		login(mysql_insert_id());
		$page = get_current_page(myip());
		header("Location: ".$page);
	}
	else { //if information is invalid
		include('header.php');
		//include("top.php");
		?>		
		<table width="800px" align="center" cellspacing="0" cellpadding="0" class="main"><tbody>
			<tr>
		 		<td class="top_search">
					<?php include("top.php"); ?>
				</td>
		 	</tr>
			<tr>
				<td><div id="banner"><?php include("pic_banner.php");?></div></td>
			</tr>
		<?php
        foreach ($s as $error) {
			echo "<tr><td>";
			if ($error !== 'ok') error_alert($error);
			echo "</td></tr>";
		}
		set_new_confirm_id($session_id);
		echo "<tr>";
			echo "<td>";
				include('registration_form.php');
			echo "</td>";  					
		echo "</tr>";
		echo "<tr>";
			echo "<td>";include('footer.php');echo "</td>";
		echo "</tr>";
		echo '</tbody></table>';
	}				
}
else {
	include('header.php');
	//include("top.php");
    ?>
	<table width="800px" align="center" cellspacing="0" cellpadding="0" class="main"><tbody>
	<tr>
		<td class="top_search">
			<?php include("top.php"); ?>
		</td>
	</tr>
	<tr>
		<td><div id="banner"><?php include("pic_banner.php");?></div></td>
	</tr>
<!--	<tr><td class="left"><?php //include("search.php");?></td></tr> -->
	<?php
	set_new_confirm_id($session_id);
		echo "<tr>";
			echo "<td>";
				include('registration_form.php');
			echo "</td>";  					
		echo "</tr>";
		echo "<tr>";
			echo "<td>";include('footer.php');echo "</td>";
		echo "</tr>";
		echo '</tbody></table>';
}		
 ?>	
</html>
