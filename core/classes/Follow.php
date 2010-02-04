<?php
/***************************************************************
Class connect table setting in database
***************************************************************/
class Follow {
//present property_name's value in setting
	public $post_id;
	
//present property_value's value in setting	
	public $username;
	
//take result in mysql_query	
	public $re;

/***************************************************************

* update table follow 

* @param $value: which col will be update,
 
* @param $where: statement with where

***************************************************************/	
	function update_follow($value,$where){
		$q = new db;
		if(is_array($where))
		{
			foreach($where as $key=>$value)
				$s[] = "$key = $value";
				$where = implode(' and ',$s);
				$where = "where $where";
		} 
		else
			$where = "where $where";
		$q->query("update follow set follow=$value $where");
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