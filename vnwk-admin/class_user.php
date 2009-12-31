<?
include("db.php");
	class user{
		protected $re = NULL;
		
		//show user
		function show_user($command=""){
			if($command == "")
			{
				$sql = "select id,username,password,level,ban_user from users";
				$this->re = mysql_query($sql) or die(mysql_error());
				while($row = mysql_fetch_assoc($this->re)){
					$arr[] = $row;
				}
				@mysql_free_result($this->re);
				return $arr;
			}
			else{
				$sql = "select id,username,password,level,ban_user from users $command";
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
		function edit_user($id,$arr){
			$str;
			if(count($arr)!= 0){
				foreach($arr as $key=>$value)
				{
						$str .= " $key='".$value."',";
				}
			}
			$str = substr($str,0, -1)." ";
			$sql = "update users set".$str."where id='".$id."'";
			mysql_query($sql) or die(mysql_error());
			@mysql_free_result($this->re);
		}
		
		//check user exsist
		function check_user($id,$user){
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