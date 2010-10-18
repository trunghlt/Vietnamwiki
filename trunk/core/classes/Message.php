<?php
/***************************************************************
Class connect table setting in database
***************************************************************/
class Message {
//present id's value in setting
	public $id;
	
//present s_id's value in setting	
	public $s_id;
	
//present r_id's value in setting	
	public $r_id;
	
//present content's value in setting	
	public $content;

//present post_time's value in setting	
	public $post_time;	
	

/***************************************************************

* query to get value in table Message

* @return: return array with key =  property_name and value = property_value

***************************************************************/	
	public function query_user_id($id){
		$q=new db;
		$q->query("select * from messages where r_id=$id");
		if($q->n > 0){
			while($row = mysql_fetch_assoc($q->re)){
				$r[] = $row; 
			}
		}
		else
			return 0;
		mysql_free_result($q->re);	
		return @$r;
		
	}
/***************************************************************

* insert value in table Message 

* @param $s_id: user who have messaged
 
* @param $r_id: user's message

* @param $mess: content

* @param $post_time: time post message


***************************************************************/	
	public function insert($s_id,$r_id,$mess,$post_time){
		$q=new db;
		$q->query("INSERT INTO messages
				(s_id, r_id, content, post_time)
				VALUE ('".$s_id."','".$r_id."','".$mess."','".$post_time."')");
		mysql_free_result($q->re);
	}

/***************************************************************
Free old result
***************************************************************/
	public function free(){
		if($this->re){
			@mysql_free_result($this->re);
		}
	}
}

?>