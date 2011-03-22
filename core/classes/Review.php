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
	public $name;
	public $email;
	public static $currentReviewListByPostId;
/*
 * delete Solr
 */
        public static function deleteSolr(){
		$solr = new Solr;
                $solr->delete_all_solr(2);
        }
        
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
		$e->name = $reviewArr["name"];
		$e->email = $reviewArr["email"];
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
		$mysql["reviewText"] = $this->reviewText;
		Db::sQuery("INSERT INTO reviews
					(user_id, post_id, rate_value, review_text, review_date_time,name,email)
					VALUES ({$this->userId}, {$this->postId}, {$this->rateValue}, '{$mysql["reviewText"]}', {$this->reviewDateTime},'{$this->name}','{$this->email}')");		
		$this->id = mysql_insert_id();
                $review = Mem::$memcache->get("review_".$this->postId);
			if($review != NULL){
					Mem::$memcache->delete("review_".$this->postId);
                        }
                self::deleteSolr();
	}
	
	public function save() {
		$mysql["reviewText"] = htmlspecialchars($this->reviewText, ENT_QUOTES);
		Db::sQuery("UPDATE reviews
					SET user_id = {$this->userId},
						post_id = {$this->postId},
						rate_value = '{$mysql["reviewText"]}',
						review_text = {$this->reviewText},
						review_date_time = {$this->reviewDateTime}
					WHERE id = {$this->id}");
		$review = Mem::$memcache->get("review_".$this->postId);
			if($review != NULL)
					Mem::$memcache->delete("review_".$this->postId);
                        self::deleteSolr();
	}
        /*--------------------------------------------------------------
	Check review with a specific id
	- $postId: id of the post
	->return a bool
	-------------------------------------------------------------*/
        public static function checkReviewByPostId($postId){
            $q = new db;
            $array = array();
            $q->query("Select * from reviews WHERE post_id = {$postId}");
            if($q->re){
                if($q->n>0)
                        return true;
            }
            return false;
        }
        /*--------------------------------------------------------------
	Get review list in a post with time
	- $time: compare review's time
	->return a list of review elements
	-------------------------------------------------------------*/
        public static function getByTime($time){
            $q = new db;
            $array = array();
            $q->query("Select distinct post_id from reviews where review_date_time > ".$time." ORDER BY review_date_time DESC");
            if($q->re)
            {
                if($q->n>1){
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
                else{
                    $q->query("Select distinct post_id from reviews ORDER BY review_date_time DESC limit 0,10");
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
            }
            return 0;
        }
}
?>
