<?php
/***************************************************************
Class connect table setting in database
***************************************************************/
class Follow {
//present property_name's value in setting
	public $post_id;
	
//present property_value's value in setting	
	public $user_id;
	
//take result in mysql_query	
	public $re;

/***************************************************************

* update table follow 

* @param $value: which col will be update,
 
* @param $where: statement with where

***************************************************************/	
	public function update_follow($value,$id){
		$q = new db;
		$q->query(" UPDATE follow
			
					SET value=1
			
					WHERE id={$id}");
	}
/***************************************************************

* query table follow 

* @param $user_id: check user_id,
 
* @param $post_id: and check post_id

* return num row

***************************************************************/	
	static public function exist($user_id,$post_id){
			$q = new Db;
			$q->query("SELECT * FROM follow
			
			WHERE user_id={$user_id} AND post_id={$post_id}");
			
			return ($q->n);
	}
/***************************************************************

* set value=1 in table follow 

* @param $user_id: check user_id,
 
* @param $post_id: and check post_id

***************************************************************/	
	static function set($user_id, $post_id) {
			$q = new Db;
		if (self::exist($user_id, $post_id) == 0) {
		
			Db::squery("INSERT INTO follow
			
			(user_id, post_id, value)
			
			VALUES({$user_id}, {$post_id}, 1)");
		
		}
		
		else {
		
			Db::squery(" UPDATE follow
			
			SET value=1
			
			WHERE user_id={$user_id} AND post_id={$post_id}");
		}
	}
/***************************************************************

* set value=1 in table follow 

* @param $user_id: check user_id,
 
* @param $post_id: and check post_id

***************************************************************/	
	static function un_set($user_id, $post_id) {
			$q = new Db;
		if (self::exist($user_id, $post_id) == 0) {
		
			Db::squery("INSERT INTO follow
			
			(user_id, post_id, value)
			
			VALUES({$user_id}, {$post_id}, 0)");
		
		}
		
		else {
		
			Db::squery(" UPDATE follow
			
			SET value=0
			
			WHERE user_id={$user_id} AND post_id={$post_id}");
		}
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