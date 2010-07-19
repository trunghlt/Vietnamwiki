<?php
class IndexElement{
	public $id;
	public $destId;
	public $name;
	public $locked;
	
	public function query($id) {
		$this->id = $id;
		$q = new Db;
		$q->query("	SELECT *
					FROM index_menu
					WHERE id = ".$this->id);
		$r = mysql_fetch_array($q->re);
		if($q->n==0)
			return 0;
		$this->destId = $r["dest_id"];
		$this->name = $r["name"];
		$this->locked = $r["locked"];
	}	 
	
	public static function filterId($id) {
		return filterNumeric($id);
	}
}
?>