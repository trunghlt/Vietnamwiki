<?php
/***************************************************************
Class connect email  in database
***************************************************************/
	class Email{
//present id's value in setting
		public $id;

//present subject's value in setting
		public $subject;

//present message's value in setting
		public $message;

//present from's value in setting
		public $from;

/***************************************************************

* query table email follow Id

* $id : col id of email

* @return: return array value in email

***************************************************************/		
		static public function query($id=''){
			if($id!=''){
				$where = "where id=$id"; 
			}
			else
				$where='';
			$str = "select * from email $where";
			$re = mysql_query($str);
			while($row = mysql_fetch_assoc($re)){
				$row2 = $row;
			}
			return $row2;
		}

/***************************************************************

* query table email follow post_id

* $post_id : col post_id of email

* @return: return array value in email

***************************************************************/
	static public function query_post($post_id){
		$q=new Db;
		$str = $q->query("select email,id from users where id IN (select user_id from follow where post_id=$post_id)");
		while($r = mysql_fetch_assoc($q->re)){
			if($r['email']!='')
				$row[] = $r;
		}
		return $row;
	}
}
?>