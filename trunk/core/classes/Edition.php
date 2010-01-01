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
			$this->id = $id;
			$r = mysql_query("	SELECT *
								FROM editions
								WHERE id = $id");
			$row = mysql_fetch_array($r);
			$this->userId = $row["user_id"];
			$this->postId = $row["post_id"];
			$this->postTitle = $row["post_subject"];
			$this->postSummary = $row["post_summary"];
			$this->postContent = $row["post_text"];
			$this->postSmallImgURL = $row["post_small_img_url"];
			$this->postBigImgURL = $row["post_big_img_url"];
			$this->editDateTime = $row["edit_date_time"];
			return $row;
	}
	
	public function add() {
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		mysql_query("INSERT INTO editions
					(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url)
					VALUE ('".$this->userId."', '".$this->postId."', '".$this->postTitle."', '".$this->postSummary."', '".$mysql["postContent"]."', 
							'".$this->editDateTime."',
							'".$this->postSmallImgURL."', '".$this->postBigImgURL."') ") or die(mysql_error());
		$this->id = mysql_insert_id();		
	}
	
	public function save() {	
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		mysql_query("UPDATE editions
					SET user_id = ".$this->userId.",
						post_id = ".$this->postId.",
						post_subject = '".$this->postTitle."',
						post_summary = '".$this->postSummary."',
						post_text = '".$mysql["postContent"]."',
						edit_date_time = '".$this->editDateTime."',
						post_small_img_url = '".$this->postSmallImgURL."',
						post_big_img_url = '".$this->postBigImgURL."'
					WHERE id = ".$this->id) or die(mysql_error());		
	}
	
		
	//Restore a draft
	public function restore() {		
		//Update posts_texts	
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		mysql_query("UPDATE posts_texts
					SET post_subject = '{$this->postTitle}',
						post_summary = '{$this->postSummary}',
						post_text = '{$mysql["postContent"]}',
						post_small_img_url = '{$this->postSmallImgURL}',
						post_big_img_url = '{$this->postBigImgURL}'
					WHERE post_id = {$this->postId}") or die(mysql_error());
		
		//Delete all later editions
		mysql_query("DELETE FROM editions
					WHERE (post_id = {$this->postId}) AND (edit_date_time > {$this->editDateTime})") or die(mysql_error());
	}
	
	//edit
	public function edit($id,$edition){
		$mysql["postContent"] = mysql_real_escape_string($edition);
				mysql_query("UPDATE editions
					SET post_text = '".$mysql["postContent"]."'
					WHERE id = ".$id) or die(mysql_error());	
	}
}
?>
