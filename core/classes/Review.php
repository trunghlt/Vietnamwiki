<?php
class ReviewListByPostId {
	public $reviewList;
	public $postId;
}

class Review {
	public $id;
	public $userId;
	public $postId;
	public $rateValue;
	public $reviewText;
	public $reviewDateTime;
	public static $currentReviewListByPostId;
	
	public function query($id) {
		$this->id = $id;
		$q = new db;
		$mysql["id"] = mysql_real_escape_string($id);
		$q->query("	SELECT *
					FROM reviews
					WHERE id = {$mysql["id"]} ");
		$r = mysql_fetch_array($q->re);
		$this->userId = $r["user_id"];
		$this->postId = $r["post_id"];
		$this->rateValue = $r["rate_value"];
		$this->reviewText = $r["review_text"];
		$this->reviewDateTime = $r["review_date_time"];		
	}

	/*--------------------------------------------------------------
	Parse from array into object
	- $reviewArr: the array contain info of a review object
	->a respective Review object
	--------------------------------------------------------------*/
	public static function parseArr($reviewArr) {
		$e = new Review;
		$e->id = $reviewArr["id"];
		$e->userId = $reviewArr["user_id"];
		$e->postId = $reviewArr["post_id"];
		$e->reviewText = $reviewArr["review_text"];
		$e->rateValue = $reviewArr["rate_value"];
		$e->reviewDateTime = $reviewArr["review_date_time"];
		return $e;
	}
	
	
	/*--------------------------------------------------------------
	Get review list in a post with a specific id
	- $postId: id of the post
	->return a list of review elements in the post with id $postId
	-------------------------------------------------------------*/
	public static function getReviewListByPostId($postId) {
		if (isset(self::$currentReviewListByPostId)&&(self::$currentReviewListByPostId->postId == $postId)) 
			return self::$currentReviewListByPostId->reviewList	;
		else {
			$q = new db;
			$q->query("	SELECT *
						FROM reviews
						WHERE post_id = {$postId}
						ORDER BY review_date_time DESC");
			if (!isset(self::$currentReviewListByPostId)) self::$currentReviewListByPostId = new reviewListByPostId;
			self::$currentReviewListByPostId->reviewList = array();
			self::$currentReviewListByPostId->postId = $postId;
			while ($r = mysql_fetch_array($q->re)) self::$currentReviewListByPostId->reviewList[] = self::parseArr($r);
			return self::$currentReviewListByPostId->reviewList;
		}
	}	
	
	public function add() {
		$mysql["reviewText"] = htmlspecialchars($this->reviewText, ENT_QUOTES);
		Db::sQuery("INSERT INTO reviews
					(user_id, post_id, rate_value, review_text, review_date_time)
					VALUES ({$this->userId}, {$this->postId}, {$this->rateValue}, '{$mysql["reviewText"]}', {$this->reviewDateTime})");		
		$this->id = mysql_insert_id();
	}
	
	public function save() {
		$mysql["reviewText"] = htmlspecialchars($this->reviewText, ENT_QUOTES);
		Db::sQuery("UPDATE reviews
					SET user_id = {$this->userId},
						post_id = {$this->posstId},
						rate_value = '{$mysql["reviewText"]}',
						review_text = {$this->reviewText},
						review_date_time = {$this->reviewDateTime}
					WHERE id = {$this->id}");					
	}
}
?>