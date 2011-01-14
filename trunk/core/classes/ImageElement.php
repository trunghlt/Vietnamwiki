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
        /*--------------------------------------------------------------
	Get image list with a string
	- $str: compare with tag's string
        - $type :
         * 1 : search has limit
	->return a list of image elements
	-------------------------------------------------------------*/
        public static function searchImage($str,$type=0){
            $q = new db;
            $array = array();
            if($type==1)
                $q->query("Select * from images where tags LIKE '%".$str."%' ORDER BY uploaded_at DESC limit 0,5");
            else
                $q->query("Select * from images where tags LIKE '%".$str."%' ORDER BY uploaded_at DESC");
            if($q->re)
            {
                if($q->n>0){
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
            }
            return 0;
        }
        /*--------------------------------------------------------------
	Get image list in a destination with time
	- $time: compare with uploaded time
	->return a list of image elements
	-------------------------------------------------------------*/
        public static function getByTime($time){
            $q = new db;
            $array = array();
            $q->query("Select distinct dest_id from images where uploaded_at > ".$time." ORDER BY uploaded_at DESC");
            if($q->re)
            {
                if($q->n>1){
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
                else{
                    $q->query("Select distinct dest_id from images ORDER BY uploaded_at DESC limit 0,10");
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
            }
            return 0;
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