<?php 
class DestinationElement {
	public $id;
	public $engName;
	public $vnName;
	public $forecaCode;

	public function query($id) {
		$this->id = $id;
		$this->queryById();
	}
	
	public function queryById() {
		$q = new Db();
		$q->query("	SELECT *
					FROM destinations
					WHERE id=".$this->id);
		$r = mysql_fetch_array($q->re);
		$this->engName = $r["EngName"];
		$this->vnName = $r["VnName"];
		$this->forecaCode = $r["forecaCode"];
	}
}
?>