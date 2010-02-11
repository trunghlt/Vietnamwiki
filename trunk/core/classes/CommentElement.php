<?php 
class CommentElement{
	public $id;
	public $postId;
	public $userId;
	public $posterIp;
	public $commentTime;
	public $commentText;
	public $editionid;
	public $email;
	public $name;
	
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
					(post_id, user_id, poster_ip, comment_time, comment_text,edition_id,name,email)
					VALUES ('".$this->postId."','".$this->userId."','".$this->posterIp."','".$this->commentTime."','".$this->commentText."','".$this->editionid."','".$this->name."','".$this->email."')");
		$this->id = mysql_insert_id();
	}
	
	public function remove() {
		$q = new Db;
		$q->query(" DELETE FROM comments
					WHERE comment_id = ".$this->id);
	}
	public function query_get_posts($username){
			$q = new Db;
			$q->query("SELECT *
						FROM `comments`
						WHERE (SELECT post_username
						 		FROM posts
						   		WHERE post_id = `comments`.post_id) = '".$username."'
					ORDER BY comment_id DESC");
			if($q->n > 0){
				while($row = mysql_fetch_assoc($q->re))
					$r[] = $row;
			}
			else 
				return 0;
			@mysql_free_result($q->re);
				return $r;
	}
}
?>