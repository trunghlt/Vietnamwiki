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
	}
?>