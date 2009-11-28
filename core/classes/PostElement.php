<?php 
class PostElement {
	public $id;
	public $content;
	public $summary;
	public $title;
	public $authorUsername;
	public $indexId;
	public $locked;
	public $smallImgURL;
	public $bigImgURL;
	
	public static function filterContent($content) {
		return $content;
	}
	
	public static function filterTitle($title){
		return $title;
	}
	
	public static function filterSummary($summary) {
		return $summary;
	}
	
	public static function filterId($id) {
		return filterNumeric($id);
	}	
	
	public static function filterImgURL($url) {
		return $url;
	}
	
	public function query($id) {
		$q = new db;
		$this->id = $id; 
		$q->query("	SELECT *
					FROM posts_texts
					WHERE post_id = ".$this->id);
		$r = mysql_fetch_array($q->re);
		$this->content = $r["post_text"];
		$this->title = $r["post_subject"];
		$this->summary = $r["post_summary"];
		$this->smallImgURL = $r["post_small_img_url"];
		$this->bigImgURL = $r["post_big_img_url"];
		
		$q->query("	SELECT *
					FROM posts
					WHERE post_id = ".$this->id);
		$r = mysql_fetch_array($q->re);
		$this->authorUsername = $r["post_username"];
		$this->indexId = $r["index_id"];
		$this->locked = $r["locked"];		
	}
	
	public function add() {
		$q = new db;
		
		$q->query(" INSERT INTO posts_texts
					(post_subject, post_summary, post_text, post_small_img_url, post_big_img_url)
					VALUE ('".$this->title."','".$this->summary."','".$this->content."','".$this->smallImgURL."','".$this->bigImgURL."') ");
					
		$this->id = mysql_insert_id();	
		$post_time = time();
		$ip = myip();
		$edittime = 1;
		$sql = "SELECT COUNT(*) as n
				FROM posts
				WHERE index_id = ".$this->indexId;
		$q->query($sql);
		$r = mysql_fetch_array($q->re);
		$ord = $r["n"];
		$q->query('	INSERT INTO posts
					(post_id, index_id, post_time, poster_ip, post_username, post_edit_time, ord)
					VALUE ("'.$this->id.'","'.$this->indexId.'","'.$post_time.'","'.$ip.'","'.$this->authorUsername.'","'.$edittime.'","'.$ord.'")');
        $q->query("	INSERT INTO acts
		         	(act_username, type, target, time) 
                    VALUE ('".$this->authorUsername."','create','".$this->id."','".time()."')");
	}
	
	public function remove() {
		$q = new Db;
		$q->query("	DELETE FROM posts_texts
					WHERE post_id = ".$this->id);
		$q->query(" DELETE FROM posts
					WHERE post_id = ".$this->id);	
	}	
	
	public function save() {
		$q = new db;
		
		$mysql["content"] = mysql_real_escape_string($this->content);
		
		$q->query(" UPDATE posts_texts
					SET	post_subject = '".$this->title."',
						post_summary = '".$this->summary."',
						post_text = '".$mysql["content"]."',
						post_small_img_url = '".$this->smallImgURL."',
						post_big_img_url = '".$this->bigImgURL."'
					WHERE post_id = ".$this->id);
		
		$q->query(" UPDATE posts
					SET index_id = ".$this->indexId."
					WHERE post_id = ".$this->id); 					
	}	

}
?>