<?php 
class ImageElement{
	public $id;
	public $destId;
	public $description;
	public $tags;
	public $uploadedAt;
	public $userId;
	public $fileName;

	
	public function query(){
		$q = new Db;
		$q->query("	SELECT *
					FROM images
					WHERE id = ".$this->id);
		$r = mysql_fetch_array($q->re);
		$this->destId = $r["dest_id"];
		$this->description = $r["des"];
		$this->tags = $r["tags"];
		$this->uploadedAt = $r["uploaded_at"];
		$this->userId = $r["user_id"];
		$this->fileName = $r["filename"]; 
	}
	
	public function save() {
		$q = new Db;
		$q->query("	UPDATE images
					SET	dest_id 	= '".$this->destId."',
						des			= '".$this->description."',
						tags		= '".$this->tags."',
						uploaded_at	= '".$this->uploadedAt."',
						user_id		= '".$this->userId."',
						filename	= '".$this->fileName."'
					WHERE id = ".$this->id);
	}
	
	public static function filterId($id) {
		return $id;
	}
	
	public static function filterDescription($description){
		return $description;
	}
	
	public static function filterTags($tags){
		return $tags;
	}
	
	public static function filterDestId($destId){
		return $destId;
	}
}
?>