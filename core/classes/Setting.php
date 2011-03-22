<?php
/***************************************************************
Class connect table setting in database
***************************************************************/
class Setting {
//present property_name's value in setting
	public $name;
	
//present property_value's value in setting	
	public $value;
	
//take result in mysql_query	
	public $re;

/***************************************************************

* query to get link in table setting

* @return: return array with key =  property_name and value = property_value

***************************************************************/	
	public function query_get_link(){
		$str = 'select * from setting';
		$this->re = mysql_query($str);
		while($row = mysql_fetch_assoc($this->re)){
			$r[$row['property_name']] = $row['property_value'];
		}
		$this->free();
		return @$r;
	}
/***************************************************************

* update table setting 

* @param $str: which col will be update,
 
* @param $where: statement with where

***************************************************************/	
	public function update($str,$where){
		$s = "update setting set $str where $where";
		mysql_query($s);
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