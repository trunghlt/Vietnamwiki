<?php 
class CommentElement{
	public $id;
	public $postId;
	public $userId;
	public $posterIp;
	public $commentTime;
	public $commentText;
	
	public function query() {
		$q = new Db;
		$q->query("	SELECT *
					FROM comments
					WHERE comment_id = ".$this->id);
		$r = mysql_fetch_array($q->re);
		$id = $r["comment_id"];
		$postId = $r["post_id"];
		$userId = $r["user_id"];
		$posterIp = $r["poster_ip"];
		$commentTime = $r["comment_time"];
		$commentText = $r["comment_text"];
	}
	
	public function add() {
		$q = new Db;
		$q->query(" INSERT INTO comments
					(post_id, user_id, poster_ip, comment_time, comment_text)
					VALUES ('".$this->postId."','".$this->userId."','".$this->posterIp."','".$this->commentTime."','".$this->commentText."')");
		$this->id = mysql_insert_id();
	}
	
	public function remove() {
		$q = new Db;
		$q->query(" DELETE FROM comments
					WHERE comment_id = ".$this->id);
	}
	
}