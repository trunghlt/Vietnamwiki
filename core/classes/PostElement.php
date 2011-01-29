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
	public $draft;
	public $post_edit_time;
	public $reference;
	
	
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

	public static function filterReference($reference) {
		return $reference;
	}
		
	public function query($id='',$index='') {
		$q = new db;
                if($id!='' && $index==''){
                    $this->id = $id;
                    $q->query("	SELECT *
                                            FROM posts_texts
                                            WHERE post_id = ".$this->id);
                    if(!$q->re)
                            return 0;
                    if($q->n ==0)
                            return 0;
                    $r = mysql_fetch_array($q->re);

                    $this->content = $r["post_text"];
                    $this->title = $r["post_subject"];
                    $this->summary = $r["post_summary"];
                    $this->smallImgURL = $r["post_small_img_url"];
                    $this->bigImgURL = $r["post_big_img_url"];
                    $this->reference = $r['reference'];

                    $q->query("	SELECT *
                                            FROM posts
                                            WHERE post_id = ".$this->id);
                    $r = mysql_fetch_array($q->re);
                    $this->authorUsername = $r["post_username"];
                    $this->indexId = $r["index_id"];
                    $this->locked = $r["locked"];
                    return 1;
                }
                elseif($id=='' && $index!=''){
                     $q->query("SELECT * FROM posts where index_id=$index");
                    if($q->re){
                        if($q->n){
                            while($r = mysql_fetch_assoc($q->re))
                                $row[] = $r;
                            return @$row;
                        }
                    }
                    return 0;                    
                }
	}
	
	public function add($user_id="") {
		//Add map spots in the post to the database
		MapSpot::addMapSpots(htmlspecialchars_decode($this->content, ENT_QUOTES), $this->id);
		$q = new db;
		if($user_id!=""){
			$q->query("select property_value
						from setting
						where property_name = 'ALLOW_DIRECT_UPDATE'");
						
			$row = mysql_fetch_assoc($q->re);
			
			if($row['property_value']==0){
				$q->query("select level
							from users
							where id='".$user_id."'");
							
				$row2 = mysql_fetch_assoc($q->re);	
				//Not allow for user has level = 0			
				if($row2['level']==1){		
					$q->query(" INSERT INTO posts_texts
								(post_subject, post_summary, post_text, post_small_img_url, post_big_img_url,reference)
								VALUE ('".$this->title."','".$this->summary."','".$this->content."','".$this->smallImgURL."','".$this->bigImgURL."','".$this->reference."')");
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
					$index = Mem::$memcache->get("index_".$this->indexId);
					if($index != NULL)
						Mem::$memcache->delete("index_".$this->indexId);
					Follow::set($user_id,$this->indexId);
                                        self::deleteSolr();
				}
				else{
					$this->id = 0;					
				}
			}
			//Allow for anyone
			else{
					$q->query(" INSERT INTO posts_texts
								(post_subject, post_summary, post_text, post_small_img_url, post_big_img_url,reference)
								VALUE ('".$this->title."','".$this->summary."','".$this->content."','".$this->smallImgURL."','".$this->bigImgURL."','".$this->reference."')");
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
						Follow::set($user_id,$this->id);
					$index = Mem::$memcache->get("index_".$this->indexId);
					if($index != NULL)
						Mem::$memcache->delete("index_".$this->indexId);
                                        self::deleteSolr();
			}
		}
	}
	
	public function remove() {
		$q = new Db;
		$q->query("	DELETE FROM posts_texts
					WHERE post_id = ".$this->id);
		$q->query(" DELETE FROM posts
					WHERE post_id = ".$this->id);
		$q->query(" DELETE FROM follow
					WHERE post_id = ".$this->id);
                self::deleteSolr();
	}	
        public 	function method_update($user_id = ''){
            $q = new db;
		$mysql["content"] = mysql_real_escape_string($this->content);
		$mysql["reference"] = mysql_real_escape_string($this->reference);
                $q->query(" UPDATE posts_texts
                                        SET	post_subject = '".$this->title."',
                                                post_summary = '".$this->summary."',
                                                post_text = '".$mysql["content"]."',
                                                post_small_img_url = '".$this->smallImgURL."',
                                                post_big_img_url = '".$this->bigImgURL."',
                                                reference = '".$mysql["reference"]."'
                                        WHERE post_id = ".$this->id);

                $q->query(" UPDATE posts
                                        SET index_id = ".$this->indexId."
                                        WHERE post_id = ".$this->id);
                $this->draft = $this->content;
                self::deleteSolr();
                return 0;
        }
	public function save($user_id="") {
		//Remove all map spots from the current post
		MapSpot::removeMapSpotsByPostId($this->id);
		
		//Ad map spots from new content
		MapSpot::addMapSpots(htmlspecialchars_decode($this->content, ENT_QUOTES), $this->id);
		
		$q = new db;
		if($user_id != ""){
                    $bool = User::check_user($user_id, $this->id);
                    if($bool == TRUE){
                        return $this->method_update($user_id);
                    }
                    else{
                            $q->query("select *
                                                    from posts_texts
                                                    where post_id = '".$this->id."'");
                            $r = mysql_fetch_assoc($q->re);
                            $this->draft = $r['post_text'];
                            return 1;
                    }
		}
	}
	public function query_username($username) {
		$q = new db;
		$q->query("	SELECT *
					FROM posts
					WHERE post_username = '$username'");
		if($q->n > 0){
			while($r = mysql_fetch_assoc($q->re))
			{
				$row[] =$r;
			}
			return @$row;
		}
		else
			return 0;	
	}
	public function query_id($id) {
		$q = new db;
		$q->query("	SELECT *
					FROM posts_texts
					WHERE post_id = '$id'");
		if($q->n > 0)
			return mysql_fetch_assoc($q->re);
	}	
	public function query_rowByIndex($index='') {
		$q = new db;
		if($index!=NULL)
			$q->query("	SELECT *
						FROM posts
						WHERE index_id = $index");
		else
			$q->query("	SELECT *
						FROM posts");			
		if($q->n > 0)
			return $q->n;
		return 0;
	}
        /*--------------------------------------------------------------
	Get posts list with time
	- $time: compare post's time
	->return a list of post elements
	-------------------------------------------------------------*/
        public static function getByTime($time){
            $q = new db;
            $array = array();
            $q->query("Select * from posts where post_time > ".$time." ORDER BY post_time DESC");
            if($q->re)
            {
                if($q->n>1){
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
                else{
                    $q->query("Select * from posts ORDER BY post_time DESC limit 0,10");
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
            }
            return 0;
        }
        /*--------------------------------------------------------------
	Get post list with a string
	- $str: search string in post_subject
        - $type :
         * 1 : search has limit
	->return a list of post
	-------------------------------------------------------------*/
        public static function searchPost($str,$type=0){
            $q = new db;
            $array = array();
            if($type==1)
                $q->query("Select distinct post_subject from posts_texts where post_subject LIKE '%$str%' || post_summary LIKE '%$str%' || post_text LIKE '%$str%' limit 0,5");
            else
                $q->query("Select * from posts_texts where post_subject LIKE '%$str%' || post_summary LIKE '%$str%' || post_text LIKE '%$str%'");
            if($q->re)
            {
                if($q->n>0){
                    while($r = mysql_fetch_assoc($q->re))
                        $array[] = $r;
                    return $array;
                }
            }
            return 0;
        }
}
?>
