<?php 
class DestinationElement {
	public $id;
	public $engName;
	public $vnName;
	public $forecaCode;
	public $lat;
	public $long;
	public $zoomlevel; 

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
		$this->lat = $r["lat"];
		$this->long = $r["long"];
		$this->zoomlevel = $r["zoomlevel"];
	}

    public static function getAllDestinations() {
        $destinations = Mem::$memcache->get("allDestinationElements");
        if ($destinations == NULL) {    
            $q = new Db;
            $q->query("	SELECT * 
			            FROM destinations
			            ORDER BY ord");
            $destinations = array();
            while ($d = mysql_fetch_object($q->re, "DestinationElement")) {
                $destinations[] = $d;
            }
            Mem::$memcache->set("allDestinationElements", $destinations);
        }
        return $destinations;
    }
    
    public static function getAllMapImages() {
    	$result = Mem::$memcache->get("DestMapImageHash");
    	if ($result == NULL) {
			$mapImages = MapImage::getAllMapImages();
			$result = array();
			foreach ($mapImages as $img) {
				if (!array_key_exists($img->dest_id, $result)) { $result[$img->dest_id] = array();}
				$result[$img->dest_id][] = $img;
			}
			Mem::$memcache->set("DestMapImageHash", $result);
		}
		return $result;
    }
	
	public function setMapDetail($lat, $long, $zoomlevel) {
		$clean["lat"] = htmlspecialchars($lat, ENT_QUOTES);
		$clean["long"] = htmlspecialchars($long, ENT_QUOTES);
		$clean["zoomlevel"] = htmlspecialchars($zoomlevel, ENT_QUOTES);
		$q = new Db;
		$q->query(" UPDATE `destinations`
					SET `lat`=".$clean["lat"].",
						`long`=".$clean["long"].",
						`zoomlevel`=".$clean["zoomlevel"]."
					WHERE id=".$this->id);
	}
	public function query_country() {
		$q = new Db();
		$q->query('SELECT * FROM countries');
		while($row = mysql_fetch_assoc($q->re))
			$r[] = $row;
		return $r;
	}	
}
?>
