<?php
class MapImage {
	public $dest_id, $URL;
	
	public static function getAllMapImages() {
		$result = Mem::$memcache->get("allMapImages");
		if ($result == NULL) {
			$result = array();
			$q = new Db;
			$q->query("SELECT * FROM map_images");
			while ($img = mysql_fetch_object($q->re, "MapImage")) {
				$result[] = $img;
			}
			Mem::$memcache->set("allMapImages", $result);
		}
		return $result;
	}
}
?>
