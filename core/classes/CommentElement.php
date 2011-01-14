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
	
	public function query($id) {
		$q = new Db;
		$q->query("	SELECT *
					FROM comments
					WHERE comment_id = ".$id);
		$r = mysql_fetch_array($q->re);
		$this->id = $r["comment_id"];
		$this->postId = $r["post_id"];
		$this->userId = $r["user_id"];
		$this->posterIp = $r["poster_ip"];
		$this->commentTime = $r["comment_time"];
		$this->commentText = $r["comment_text"];
		$this->editionid = $r["edition_id"];		
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
				return @$r;
	}
	public function query_num($str,$s='',$num=''){
		$q = new Db;
		if($s!=NULL && $num !=NULL)
			$limit = "limit $s,$num";
		else
			$limit = '';
		$q->query("SELECT *
					FROM comments
					WHERE $str 
					ORDER BY comment_id DESC $limit");
		if($q->n > 0){
			while($row = mysql_fetch_assoc($q->re))
				$r[] = $row;
		}
		else
			return 0;
			$r['n'] = $q->n;
		return @$r;
		
	}
        /*--------------------------------------------------------------
	Get comment list in a post with time
	- $time: compare comment's time
	->return a list of comment elements
	-------------------------------------------------------------*/
        public static function getByTime($time){
            $q = new db;
            $array = array();
            $q->query("Select distinct post_id from comments where comment_time > ".$time." ORDER BY comment_time DESC");
            if($q->re)
            {
                if($q->n>1){
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
                else{
                    $q->query("Select distinct post_id from comments ORDER BY comment_time DESC limit 0,10");
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
            }
            return 0;
        }
}
?>