<?php
class Edition {
	public $id;
	public $userId;
	public $postId;
	public $postContent;
	public $postSummary;
	public $postTitle;
	public $postSmallImgURL;
	public $postBigImgURL;
	public $editDateTime;

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
		return $id;
	}	
	
	public static function filterImgURL($url) {
		return $url;
	}
	
	public static function filterUserId($userId) {
		return $userId;
	}
	
	public static function filterPostId($postId) {
		return $postId;
	}
	
	public static function filterDateTime($dateTime) {
		return $dateTime;
	}

	public function query($id) {	
		$q = new db;
		$this->id = $id;
		$q->query("	SELECT *
					FROM editions
					WHERE id = $id");
		$r = mysql_fetch_array($q->re);
		$this->userId = $r["user_id"];
		$this->postId = $r["post_id"];
		$this->postTitle = $r["post_subject"];
		$this->postSummary = $r["post_summary"];
		$this->postContent = $r["post_text"];
		$this->postSmallImgURL = $r["post_small_img_url"];
		$this->postBigImgURL = $r["post_big_img_url"];
		$this->editDateTime = $r["edit_date_time"];
	}
	
	public function add() {
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		db::sQuery("INSERT INTO editions
					(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url)
					VALUE ('".$this->userId."', '".$this->postId."', '".$this->postTitle."', '".$this->postSummary."', '".$mysql["postContent"]."', 
							'".$this->editDateTime."',
							'".$this->postSmallImgURL."', '".$this->postBigImgURL."') ");
		$this->id = mysql_insert_id();		
	}
	
	public function save() {	
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		db::sQuery("UPDATE editions
					SET user_id = ".$this->userId.",
						post_id = ".$this->postId.",
						post_subject = '".$this->postTitle."',
						post_summary = '".$this->postSummary."',
						post_text = '".$mysql["postContent"]."',
						edit_date_time = '".$this->editDateTime."',
						post_small_img_url = '".$this->postSmallImgURL."',
						post_big_img_url = '".$this->postBigImgURL."'
					WHERE id = ".$this->id);		
	}
	
	
	//Restore a draft
	public function restore() {		
		//Update posts_texts	
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		db::sQuery("UPDATE posts_texts
					SET post_subject = '{$this->postTitle}',
						post_summary = '{$this->postSummary}',
						post_text = '{$mysql["postContent"]}',
						post_small_img_url = '{$this->postSmallImgURL}',
						post_big_img_url = '{$this->postBigImgURL}'
					WHERE post_id = {$this->postId}");
		
		//Delete all later editions
		db::sQuery("DELETE FROM editions
					WHERE (post_id = {$this->postId}) AND (edit_date_time > {$this->editDateTime})");
	}
	
}
?>
