<?php
include('../core/common.php');
include('../core/init.php');
include("../core/classes/Db.php");
	if (!logged_in()) {
		echo '1';
		return;
	}
	if (chk_sql_injection($_POST['user_id'])) $id = $_POST['user_id'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['pw'])) $pw = $_POST['pw'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['email'])) $email = $_POST['email'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['firstname'])) $first = $_POST['firstname'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['lastname'])) $last = $_POST['lastname'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['DayDOB'])) $dob = $_POST['DayDOB'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['MonthDOB'])) $mob = $_POST['MonthDOB'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['YearDOB'])) $yob = $_POST['YearDOB'];
	else{
		 echo '1';
		 return;
	}
	if (chk_sql_injection($_POST['loc'])) $loc = $_POST['loc'];
	else{
		 echo '1';
		 return;
	}
	//if (chk_sql_injection($_POST['tmp_file_name'])) $tmp = $_POST['tmp_file_name'];
	//else{
		 //echo '1';
		// return;
	//}

	update_user($id,$pw,$email,$first,$last,$dob,$mob,$yob,$loc);//,$tmp);

function update_user($id,$pw,$email,$fn,$ln,$dd,$mm,$yyyy,$country){//,$ftmp){	
	global $q;
	if (checkdate($mm, $dd, $yyyy))
		$dob = mktime(0,0,0,$mm,$dd,$yyyy);
	else{
		echo '1';
		return;
	}
	$sql = "SELECT username
			FROM users
			WHERE id='".$id."'";
	$q->query($sql);
	$row1 = mysql_fetch_array($q->re);
/*	require_once("../upload2/class.upload.php");
	require_once("../upload2/class.img.php");	

		

	if($row1['avatar']!=$ftmp || $row1['avatar']!=NULL || $row1['avatar']!='')
	{	
		$pftmp = "../upload/upload/". $ftmp;
		$handle = new Upload($pftmp);
		//if uploaded file is presented on server
		if ($handle->uploaded) {
		
		$original_path = "../images/avatars/original";
		$medium_path = "../images/avatars/medium";
		$tiny_path = "../images/avatars/tiny";
		
		//move to upload folder
		$handle->process($original_path);		
		
			if ($handle->processed) {
				
				$file_name = $handle->file_dst_name;
				
				//delete old avatar
				$sql = "SELECT avatar
						FROM users
						WHERE id='".$id."'";
				$q->query($sql);
				$row = mysql_fetch_array($q->re);
				
				$old_name = $row["avatar"];
				if($row["avatar"]!=NULL || $row["avatar"]!=''){
					unlink($original_path."/".$old_name);			
					unlink($medium_path."/".$old_name);			
					unlink($tiny_path."/".$old_name);			
				}
				//create medium size
				$img = new img($original_path."/".$file_name);
				$img->resize(100, 100, true);
				$img->store($medium_path."/".$file_name);
	
				//create tiny size
				$img = new img($original_path."/".$file_name);
				$img->resize(25, 25, true);
				$img->store($tiny_path."/".$file_name);
			
				$handle->clean();
			}	
		}
		else {
			echo '1';
			return;
		}
	}
	else{
		$file_name = $ftmp;
	}*/
if($pw==''){	
			//update database
			$sql = "UPDATE users 
					SET email = '".$email."',
						firstName='".$fn."', 
						lastName='".$ln."', 
						dob='".$dob."',
						locationCode='".$country."'						
					WHERE id='".$id."'";
			$q->query($sql);
}
else{
			$sql = "UPDATE users 
					SET password = '".$pw."',
						email = '".$email."',
						firstName='".$fn."', 
						lastName='".$ln."', 
						dob='".$dob."',
						locationCode='".$country."'
					WHERE id='".$id."'";
			$q->query($sql);	
}
echo $row1['username'];
}
?>