<?php 
class Db {
	public $re;
	public $sql;
	public $n = 0;

	//static query
	public static function sQuery($sql) {
		mysql_query($sql) or die(mysql_error());
	}
	
	function query($s) {
		$this->sql = $s;
		$this->re = mysql_query($s) or die(mysql_error());
		error_reporting(0);
		$this->n = mysql_num_rows($this->re);
		error_reporting(E_ALL);
	}
}
$q = new Db;
?>