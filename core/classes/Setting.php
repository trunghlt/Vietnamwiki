<?php
class Setting {
	public $name;
	public $value;
	public $re;
	
	public function query_get_link(){
		$str = 'select * from setting';
		$this->re = mysql_query($str);
		while($row = mysql_fetch_assoc($this->re)){
			$r[$row['property_name']] = $row['property_value'];
		}
		$this->free();
		return $r;
	}
	
	public function update($str,$where){
		$s = "update setting set $str where $where";
		mysql_query($s);
	}

	public function free(){
		if($this->re){
			@mysql_free_result($this->re);
		}
	}
}

?>