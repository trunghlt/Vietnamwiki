<?php
class Edition {
	public $id;
	public $yy;
	public $postId;
	public $postContent;
	public $postSummary;
	public $postTitle;
	public $postSmallImgURL;
	public $postBigImgURL;
	public $editDateTime;
	public $post_username;
	public $post_ip;
	public $index_id;
	public $checked;
	public $reference;
	public $reject;

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

	public static function filterReference($reference) {
		return $reference;
	}
	
	public function query($id) {	
			$this->id = $id;
			$r = mysql_query("	SELECT *
								FROM editions
								WHERE id = $id");
			if(mysql_num_rows($r)==0)
				return 0;
			$row = mysql_fetch_array($r);
			$this->userId = $row["user_id"];
			$this->postId = $row["post_id"];
			$this->postTitle = $row["post_subject"];
			$this->postSummary = $row["post_summary"];
			$this->postContent = $row["post_text"];
			$this->postSmallImgURL = $row["post_small_img_url"];
			$this->postBigImgURL = $row["post_big_img_url"];
			$this->editDateTime = $row["edit_date_time"];
			$this->reference = $row["reference"];
			$this->reject = $row["reject"];
			$this->index_id = $row["index_id"];
			$this->post_ip = $row["post_ip"];
			$this->post_username = $row["post_username"];
			return $row;
	}
	
	public function add() {
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		$mysql["reference"] = mysql_real_escape_string($this->reference);
		$this->post_ip = myip();
		//follow
		if($this->postId != 0){
			Follow::set($this->userId,$this->postId);
		}
		$re = mysql_query("select property_value
						   from setting
						   where property_name = 'ALLOW_DIRECT_UPDATE'");
		$row = mysql_fetch_assoc($re);
		//Check if not allow will be change check'value
		if($row['property_value']==0){
		$re1 = mysql_query("select level 
							from users
							where id='".$this->userId."'");
		$row2 = mysql_fetch_assoc($re1);	
			if($row2['level']==0){			
				mysql_query("INSERT INTO editions
							(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url,index_id,post_ip,post_username,checked,reference,accepted_time)
							VALUE ('".$this->userId."', 
									'".$this->postId."', 
									'".$this->postTitle."', 
									'".$this->postSummary."', 
									'".$mysql["postContent"]."', 
									'".$this->editDateTime."',
									'".$this->postSmallImgURL."', 
									'".$this->postBigImgURL."',							
									'".$this->index_id."',
									'".$this->post_ip."',
									'".$this->post_username."','0',
									'".$mysql["reference"]."',0)") or die(mysql_error());
			}

			else{
				mysql_query("INSERT INTO editions
							(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url,index_id,post_ip,post_username,checked,reference,accepted_time)
							VALUE ('".$this->userId."',
								   '".$this->postId."',
								   '".$this->postTitle."', 
								   '".$this->postSummary."', 
								   '".$mysql["postContent"]."', 
								   '".$this->editDateTime."',
								   '".$this->postSmallImgURL."', 
								   '".$this->postBigImgURL."',							
								   '".$this->index_id."',
								   '".$this->post_ip."',
								   '".$this->post_username."','1',
								   '".$mysql["reference"]."',
								   '".$this->editDateTime."')") or die(mysql_error());			
			}
		}
		else{
		mysql_query("INSERT INTO editions
					(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url,index_id,post_ip,post_username,checked,reference,accepted_time)
					VALUE ('".$this->userId."', '".$this->postId."', '".$this->postTitle."', '".$this->postSummary."', '".$mysql["postContent"]."', 
							'".$this->editDateTime."',
							'".$this->postSmallImgURL."',
							'".$this->postBigImgURL."',							
							'".$this->index_id."','".$this->post_ip."',
							'".$this->post_username."','1',
							'".$mysql["reference"]."',
							'".$this->editDateTime."')") or die(mysql_error());
		}
		$this->id = mysql_insert_id();		
	}
	
	public function save_edition() {
		$this->post_ip = myip();
                $mysql["postContent"] = mysql_real_escape_string($this->postContent);
                $mysql["reference"] = mysql_real_escape_string($this->reference);
		$re = mysql_query("select property_value
						   from setting
						   where property_name = 'ALLOW_DIRECT_UPDATE'");
		$row = mysql_fetch_assoc($re);
		//Check if not allow will be change check'value
		if($row['property_value']==0){
                        $re1 = mysql_query("select level
							from users
							where id='".$this->userId."'");
                        $row2 = mysql_fetch_assoc($re1);
			if($row2['level']==0){	
                            mysql_query("UPDATE editions
                                                    SET user_id = ".$this->userId.",
                                                            post_id = ".$this->postId.",
                                                            post_subject = '".$this->postTitle."',
                                                            post_summary = '".$this->postSummary."',
                                                            post_text = '".$mysql["postContent"]."',
                                                            post_small_img_url = '".$this->postSmallImgURL."',
                                                            post_big_img_url = '".$this->postBigImgURL."',
                                                            index_id = '".$this->index_id."',
                                                            post_ip= '".$this->post_ip."',
                                                            post_username = '".$this->post_username."',
                                                            checked = 0,
                                                            accepted_time = 0,
                                                            reference = '".$mysql["reference"]."'
                                                    WHERE id = ".$this->id) or die(mysql_error());
                        }
                        else{
                             mysql_query("UPDATE editions
                                                    SET user_id = ".$this->userId.",
                                                            post_id = ".$this->postId.",
                                                            post_subject = '".$this->postTitle."',
                                                            post_summary = '".$this->postSummary."',
                                                            post_text = '".$mysql["postContent"]."',
                                                            post_small_img_url = '".$this->postSmallImgURL."',
                                                            post_big_img_url = '".$this->postBigImgURL."',
                                                            index_id = '".$this->index_id."',
                                                            post_ip= '".$this->post_ip."',
                                                            post_username = '".$this->post_username."',
                                                            checked = 1,
                                                            accepted_time = $this->editDateTime,
                                                            reference = '".$mysql["reference"]."'
                                                    WHERE id = ".$this->id) or die(mysql_error());
                        }
                }
                else{
                     mysql_query("UPDATE editions
                                            SET user_id = ".$this->userId.",
                                                    post_id = ".$this->postId.",
                                                    post_subject = '".$this->postTitle."',
                                                    post_summary = '".$this->postSummary."',
                                                    post_text = '".$mysql["postContent"]."',
                                                    post_small_img_url = '".$this->postSmallImgURL."',
                                                    post_big_img_url = '".$this->postBigImgURL."',
                                                    index_id = '".$this->index_id."',
                                                    post_ip= '".$this->post_ip."',
                                                    post_username = '".$this->post_username."',
                                                    checked = 1,
                                                    accepted_time = $this->editDateTime,
                                                    reference = '".$mysql["reference"]."'
                                            WHERE id = ".$this->id) or die(mysql_error());
                }
	}
	//save_draf
        public function  save(){
              $mysql["postContent"] = mysql_real_escape_string($this->postContent);
              $mysql["reference"] = mysql_real_escape_string($this->reference);
              mysql_query("UPDATE editions
                                    SET user_id = ".$this->userId.",
                                            post_id = ".$this->postId.",
                                            post_subject = '".$this->postTitle."',
                                            post_summary = '".$this->postSummary."',
                                            post_text = '".$mysql["postContent"]."',
                                            post_small_img_url = '".$this->postSmallImgURL."',
                                            post_big_img_url = '".$this->postBigImgURL."',
                                            index_id = '".$this->index_id."',
                                            post_ip= '".$this->post_ip."',
                                            post_username = '".$this->post_username."',
                                            reference = '".$mysql["reference"]."'
                                    WHERE id = ".$this->id) or die(mysql_error());
        }

        //Restore a draft
	public function restore($type='') {		
		//Update posts_texts
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		$mysql["reference"] = mysql_real_escape_string($this->reference);
		if($this->postId != 0){
			Follow::set($this->userId,$this->postId);
		}
		if($type==''){
		mysql_query("UPDATE posts_texts
					SET post_subject = '{$this->postTitle}',
						post_summary = '{$this->postSummary}',
						post_text = '{$mysql["postContent"]}',
						post_small_img_url = '{$this->postSmallImgURL}',
						post_big_img_url = '{$this->postBigImgURL}',
						reference = '".$mysql["reference"]."'
					WHERE post_id = {$this->postId}") or die(mysql_error());
					$post = Mem::$memcache->get("post_".$this->id);
					if($post != NULL)
						Mem::$memcache->delete("post_".$this->id);
		//Delete all later editions
		mysql_query("DELETE FROM editions
					WHERE (post_id = {$this->postId}) AND (edit_date_time > {$this->editDateTime}) AND checked=1") or die(mysql_error());
		}
		else if($type==2){
			if($this->postId != 0){
					mysql_query("UPDATE posts_texts
								SET post_subject = '{$this->postTitle}',
									post_summary = '{$this->postSummary}',
									post_text = '{$mysql["postContent"]}',
									post_small_img_url = '{$this->postSmallImgURL}',
									post_big_img_url = '{$this->postBigImgURL}',
									reference = '".$mysql["reference"]."'
								WHERE post_id = {$this->postId}") or die(mysql_error());
			
					mysql_query("UPDATE editions
								SET checked = 1
								WHERE id = {$this->id}") or die(mysql_error());
					$post = Mem::$memcache->get("post_".$this->id);
					if($post != NULL)
						Mem::$memcache->delete("post_".$this->id);
			}
			else{
					mysql_query("INSERT INTO posts_texts
								(post_subject, post_summary, post_text, post_small_img_url, post_big_img_url,reference)
								VALUE ('".$this->postTitle."',
									   '".$this->postSummary."',
									   '".$mysql["postContent"]."',
									   '".$this->postSmallImgURL."',
									   '".$this->postBigImgURL."',
									   '".$mysql["reference"]."')") or die(mysql_error());
					$id = mysql_insert_id();
					$sql = "SELECT COUNT(*) as n
							FROM posts
							WHERE index_id = ".$this->index_id;
					$re = mysql_query($sql);
					$row2 = mysql_fetch_array($re);
					$ord = $row2["n"];	
					$edittime = 1;			   
					mysql_query('INSERT INTO posts
								(post_id, index_id, post_time, poster_ip, post_username, post_edit_time, ord)
								VALUE ('.$id.',
									   "'.$this->index_id.'",
									   "'.$this->editDateTime.'",
									   "'.$this->post_ip.'",
									   "'.$this->post_username.'",
									   "'.$edittime.'",
									   "'.$ord.'")');
										
					
					mysql_query("UPDATE editions
								SET checked = 1, post_id = $id
								WHERE id = {$this->id}") or die(mysql_error());
					Follow::set($this->userId,$id);			
			}
		}
	
	
	}
	
	//edit
	public function edit($id,$edition,$post_id,$post_sub,$post_sum,$smal_img,$big_url,$reference){
		$mysql["postContent"] = mysql_real_escape_string($edition);
		$mysql["reference"] = mysql_real_escape_string($reference);
		@mysql_free_result($re);
		$re = mysql_query("SELECT *
					 FROM posts
					 where post_id = '$post_id'") or die(mysql_error());
		$row = mysql_fetch_assoc($re);
		//Post has been exsited yet?
		if(is_array($row))
		{
				mysql_query("UPDATE editions
					SET post_text = '".$mysql["postContent"]."',checked = 1,reference='".$mysql["reference"]."',accepted_time=".time()." 
					WHERE id = ".$id) or die(mysql_error());
				mysql_query("UPDATE posts_texts
					SET post_text = '".$mysql["postContent"]."',reference='".$mysql["reference"]."' 
					WHERE post_id = ".$post_id) or die(mysql_error());
					$post = Mem::$memcache->get("post_".$this->id);
					if($post != NULL)
						Mem::$memcache->delete("post_".$this->id);
		}
		else{				
				mysql_query("UPDATE editions
							 SET post_text = '".$mysql["postContent"]."',
							 checked = 1,
							 reference='".$mysql["reference"]."',
							 accepted_time=".time()."
							 WHERE id = ".$id) or die(mysql_error());
					
				$re = mysql_query("	SELECT *
								FROM editions
								WHERE id = '".$id."'
								ORDER BY edit_date_time desc
								");
				$r = mysql_fetch_assoc($re);
				//Post_id = 0 to check edit_date_time is new edition?If no,it will only update edition.									
				if($r['post_id']==0)
				{	
					mysql_query(" INSERT INTO posts_texts
								(post_subject, post_summary, post_text, post_small_img_url, post_big_img_url,reference)
								VALUE ('".$post_sub."',
									   '".$post_sum."',
									   '".$mysql['postContent']."',
									   '".$smal_img."',
									   '".$big_url."',
									   '".$mysql["reference"]."')");
					$post_id = mysql_insert_id();	
					$post_time = $r['edit_date_time'];
					$ip = $r['post_ip'];
					$edittime = 1;
					$sql = "SELECT COUNT(*) as n
							FROM posts
							WHERE index_id = ".$r['index_id'];
					$re = mysql_query($sql);
					$row2 = mysql_fetch_array($re);
					$ord = $row2["n"];
					mysql_query('INSERT INTO posts
								(post_id, index_id, post_time, poster_ip, post_username, post_edit_time, ord)
								VALUE ("'.$post_id.'",
									   "'.$r['index_id'].'",
									   "'.$post_time.'",
									   "'.$ip.'",
									   "'.$r['post_username'].'",
									   "'.$edittime.'",
									   "'.$ord.'")');
					mysql_query("INSERT INTO acts
								(act_username, type, target, time) 
								VALUE ('".$r['post_username']."','create','".$post_id."','".$post_time."')");
					mysql_query("UPDATE editions
						SET post_id = '".$post_id."',checked = 1
						WHERE id = ".$id) or die(mysql_error());
					Follow::set($r['user_id'],$post_id);
				}
				else{
					mysql_query("UPDATE editions
								 SET post_text = '".$mysql["postContent"]."', 
								 post_id = '".$r['post_id']."',
								 reference='".$mysql["reference"]."',
								 accepted_time=".time()." WHERE id = ".$id) or die(mysql_error());
				}	
		}
	}
	//del draf edition
	public function remove(){
		$q = new Db;
		$q->query("	DELETE FROM editions
					WHERE id = ".$this->id);
	}
	//reject edidion
	public function reject($id){
		$q = new Db;
		$q->query("	UPDATE editions set reject=1
					WHERE id = ".$id);
	}
	//check number row 
	static public function get_num($user_id){
				$str = "SELECT post_id
						FROM editions
						WHERE user_id=$user_id and checked=0";
				$re_row = mysql_query($str) or die(mysql_error());
				while($r = mysql_fetch_assoc($re_row))
					$arr[] = $r;
				$arr['n'] = mysql_num_rows($re_row);		
				return $arr;
	}
	
	public function query_post($post_id,$user_id,$type_query){
		$q = new Db;
		$q->query("select * from users where id=$user_id");
			$r = mysql_fetch_assoc($q->re);
			if($r['level']==1){
				$str_where = "post_id=$post_id group by post_id"; 
			}
			else{
				$str_where = "post_id=$post_id and user_id=$user_id";
				if($type_query==0)
					 $str_where .= " and checked=0 group by post_id";
				else
					 $str_where .= " and checked=1 group by post_id";
			}
			
			$str = "SELECT index_id, post_subject ,post_id FROM editions WHERE $str_where ";

			$q->query($str);
			
			if($q->n != 0)
			{
				while($row = mysql_fetch_assoc($q->re))
					$r1[] = $row;
				return $r1;	
			}	
			return NULL;
	}
/***************************************************************
Choose method save or add
***************************************************************/
        public function c_method(){
            $q = new Db;
            $q->query("select * from editions where user_id = $this->userId and post_id = $this->postId");
            if($q->n>0){
                    $r = mysql_fetch_assoc($q->re);
                    $this->id = $r['id'];
                    $this->save_edition();
                    return 1;
            }
            else
                $this->add();
                return 0;
        }
}
?>