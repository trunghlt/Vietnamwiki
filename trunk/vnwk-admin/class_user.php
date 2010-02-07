<?php
include("db.php");
	class user{
		protected $re = NULL;
		
		//show user
		function show_user($command=""){
			$arr = array();
			if($command == "")
			{
				$sql = "select id,username,password,level,ban_user,email from users";
				$this->re = mysql_query($sql) or die(mysql_error());
				while($row = mysql_fetch_assoc($this->re)){
					$arr[] = $row;
				}
				@mysql_free_result($this->re);
				return $arr;
			}
			else{
				$sql = "select id,username,password,level,ban_user,email from users $command";
				$this->re = mysql_query($sql) or die(mysql_error());
				while($row = mysql_fetch_assoc($this->re)){
					$arr[] = $row;
				}
				@mysql_free_result($this->re);
				return $arr;				
			}
		}
		
		//del user
		function del_user($id){
			$sql = "delete from users where id='".$id."'";
			mysql_query($sql) or die(mysql_error());
			@mysql_free_result($this->re);
		}
		
		//edit user
		function edit_user($id,$level){
				$sql = "update users set level=".$level." where id='".$id."'";
				mysql_query($sql) or die(mysql_error());
				@mysql_free_result($this->re);
		}
		
		//check user exsist
		function check_user($id,$user){
			$row1=array();
			$sql = "select username from users where username='".$user."'and id != $id" ;
			$this->re = mysql_query($sql) or die(mysql_error());
			while($row = mysql_fetch_assoc($this->re)){
				$row1 = $row;
			}
			@mysql_free_result($this->re);
			return count($row1);
		}
	}
?>