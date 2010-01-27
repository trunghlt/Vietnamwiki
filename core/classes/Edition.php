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
	public $post_username;
	public $post_ip;
	public $index_id;
	public $checked;
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
			return $row;
	}
	
	public function add() {
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		$mysql["reference"] = mysql_real_escape_string($this->reference);
		$this->post_ip = myip();
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
							(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url,index_id,post_ip,post_username,checked,reference)
							VALUE ('".$this->userId."', '".$this->postId."', '".$this->postTitle."', '".$this->postSummary."', '".$mysql["postContent"]."', 
									'".$this->editDateTime."',
									'".$this->postSmallImgURL."', '".$this->postBigImgURL."',							
									'".$this->index_id."','".$this->post_ip."','".$this->post_username."','0','".$mysql["reference"]."')") or die(mysql_error());
			}

			else{
				mysql_query("INSERT INTO editions
							(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url,index_id,post_ip,post_username,checked,reference)
							VALUE ('".$this->userId."', '".$this->postId."', '".$this->postTitle."', '".$this->postSummary."', '".$mysql["postContent"]."', 
									'".$this->editDateTime."',
									'".$this->postSmallImgURL."', '".$this->postBigImgURL."',							
									'".$this->index_id."','".$this->post_ip."','".$this->post_username."','1','".$mysql["reference"]."')") or die(mysql_error());			
			}
		}
		else{
		mysql_query("INSERT INTO editions
					(user_id, post_id, post_subject, post_summary, post_text, edit_date_time, post_small_img_url, post_big_img_url,index_id,post_ip,post_username,checked,reference)
					VALUE ('".$this->userId."', '".$this->postId."', '".$this->postTitle."', '".$this->postSummary."', '".$mysql["postContent"]."', 
							'".$this->editDateTime."',
							'".$this->postSmallImgURL."', '".$this->postBigImgURL."',							
							'".$this->index_id."','".$this->post_ip."','".$this->post_username."','1','".$mysql["reference"]."')") or die(mysql_error());
		}
		$this->id = mysql_insert_id();		
	}
	
	public function save() {	
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
	public function restore() {		
		//Update posts_texts	
		$mysql["postContent"] = mysql_real_escape_string($this->postContent);
		$mysql["reference"] = mysql_real_escape_string($this->reference);
		mysql_query("UPDATE posts_texts
					SET post_subject = '{$this->postTitle}',
						post_summary = '{$this->postSummary}',
						post_text = '{$mysql["postContent"]}',
						post_small_img_url = '{$this->postSmallImgURL}',
						post_big_img_url = '{$this->postBigImgURL}',
						reference = '".$mysql["reference"]."'
					WHERE post_id = {$this->postId}") or die(mysql_error());
		
		//Delete all later editions
		mysql_query("DELETE FROM editions
					WHERE (post_id = {$this->postId}) AND (edit_date_time > {$this->editDateTime})") or die(mysql_error());
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
					SET post_text = '".$mysql["postContent"]."',checked = 1,reference='".$mysql["reference"]."' 
					WHERE id = ".$id) or die(mysql_error());
				mysql_query("UPDATE posts_texts
					SET post_text = '".$mysql["postContent"]."',reference='".$mysql["reference"]."' 
					WHERE post_id = ".$post_id) or die(mysql_error());
		}
		else{				
				mysql_query("UPDATE editions
					SET post_text = '".$mysql["postContent"]."',checked = 1,reference='".$mysql["reference"]."'
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
								VALUE ('".$post_sub."','".$post_sum."','".$mysql['postContent']."','".$smal_img."','".$big_url."','".$mysql["reference"]."')");
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
								VALUE ("'.$post_id.'","'.$r['index_id'].'","'.$post_time.'","'.$ip.'","'.$r['post_username'].'","'.$edittime.'","'.$ord.'")');
					mysql_query("INSERT INTO acts
								(act_username, type, target, time) 
								VALUE ('".$r['post_username']."','create','".$post_id."','".$post_time."')");
					mysql_query("UPDATE editions
						SET post_id = '".$post_id."',checked = 1
						WHERE id = ".$id) or die(mysql_error());					
				}
				else{
					mysql_query("UPDATE editions
						SET post_text = '".$mysql["postContent"]."', post_id = '".$r['post_id']."',reference='".$mysql["reference"]."'	WHERE id = ".$id) or die(mysql_error());
				}	
		}
	}
	//del draf editon
	public function remove() {
		$q = new Db;
		$q->query("	DELETE FROM editions
					WHERE id = ".$this->id);
	}
}
?>
