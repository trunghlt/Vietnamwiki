<?php
include_once("core/simple_html_dom.php");
/* ----------------------------------------------------------------
 * This class represents spots on Google Map 
 * ----------------------------------------------------------------*/
class MapSpot {
	public $id;
	public $lat;
	public $long;
	public $cat;
	public $des;
	public $post_id;
	
	/* ----------------------------------------------------------------
	 * Get all map spots belonging to destination with id = $dest_id
	 * @param $destId:	id of the destination
	 * @return List of map spots belonging to the destination
	 * ----------------------------------------------------------------*/
	public static function getMapSpotsByDestId($destId) {
		$q = new Db;
		$q->query(" SELECT *
					FROM map_spots
					WHERE (SELECT dest_id FROM index_menu
						   WHERE id = (	SELECT index_id 
						   				FROM posts 
						   				WHERE post_id = map_spots.post_id)) = {$destId}");
		$mapSpots = array();
		while ($r = mysql_fetch_array($q->re)) {
			$mp = new MapSpot;
			$mp->lat = $r["latCoord"];
			$mp->long = $r["longCoord"];
			$mp->cat = $r["cat"];
			$mp->des = $r["des"];
			$mp->post_id = $r["post_id"];
			$mapSpots[] = $mp;
		}
		return $mapSpots;
	}
	
	/* ----------------------------------------------------------------
	 * Remove all map spots belonging to destination id with id = $dest_id
	 * ----------------------------------------------------------------*/
	public static function removeMapSpotsByPostId($postId) {
		Db::squery(" DELETE FROM map_spots
					WHERE post_id={$postId}");
	}

	/* ----------------------------------------------------------------
	 * Get all map spots in a HTML content
	 * @param $HTMLContent
	 * @return A list of map spots in $HTMLContent
	 * ----------------------------------------------------------------*/
	public static function getMapSpots($HTMLContent) {
		$html = str_get_html($HTMLContent);
		$mapDivList = $html->find("div.map");
		echo count($mapDivList);
		$mapSpotList = array();
		foreach ($mapDivList as $mapDiv) {
			$divContent = $mapDiv->innertext;
			$tokens = preg_split("/:/", $divContent);
			$info = preg_split("/,/", $tokens[1], 5);
			$mp = new MapSpot;
			$mp->lat = Filter::getFilteredFloat($info[0]);
			$mp->long = Filter::getFilteredFloat($info[1]);
			$mp->cat = Filter::getFilteredMapCat($info[2]);
			$mp->des = Filter::getFilteredPostContent($info[4]);
			$mapSpotList[] = $mp;
		}
		return $mapSpotList;
	}
	
	/* ----------------------------------------------------------------
	 * Add all map spots in a HTML content to the database
	 * ----------------------------------------------------------------*/
	public static function addMapSpots($HTMLContent, $post_id) {
		foreach (MapSpot::getMapSpots($HTMLContent) as $mp) {
			$mp->post_id = $post_id;
			$mp->add();
		}
	}
	
	/* ----------------------------------------------------------------
	 * Get MapSpot object with id = $id 
	 * @return True if successful, False if not
	 * ----------------------------------------------------------------*/
	public function queryById($id) {
		$q = new Db;
		$q->query("	SELECT *
					FROM map_spots
					WHERE id={$id}");
		if ($q->n > 0) {
			$r = mysql_fetch_row($q->re);
			$this->id = $r["id"];
			$this->lat = $r["lat"];
			$this->long = $r["long"];
			$this->cat = $r["cat"];
			$this->des = $r["des"];
			$this->post_id = $r["post_id"];
			return True;			
		}
		return False;
	}
	
	/* ----------------------------------------------------------------
	 * Add a map spot to database 
	 * ----------------------------------------------------------------*/
	public function add() {
		$mysql["des"] = htmlspecialchars($this->des);
		Db::squery("INSERT INTO map_spots
					(latCoord, longCoord, cat, des, post_id)
					VALUE ({$this->lat}, {$this->long}, '{$this->cat}', '".$mysql["des"]."', {$this->post_id})");
		$this->id = mysql_insert_id();
	}
	
	/* ----------------------------------------------------------------
	 * Remove from database 
	 * ----------------------------------------------------------------*/
	public function remove() {
		Db::squery("DELETE FROM map_spots
					WHERE id={$this->id}");
	}
	
}
?>