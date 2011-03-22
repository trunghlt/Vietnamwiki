<?php	 
	 if (isset($index_id)) {
		 $sql = "SELECT * 
				FROM posts
				WHERE index_id = ".$index_id."
				LIMIT 2";
		 $q->query($sql);
		 if ($q->n == 1) {
			$r = mysql_fetch_array($q->re);
				header("location: ".getPostPermalink($r['post_id']));
		 }
	}
?>