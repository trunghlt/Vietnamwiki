<h1>Search result:</h1>
<?php
function get_value_in_text(){
	return $arr = file('file.txt');
}
function change_value($v){
		return str_replace(" ",'',strtolower($v));
}      
      $st = $_REQUEST["search_text"];
      if (isset($st)) {
      
		$st = htmlspecialchars($st, ENT_QUOTES);
		
		$str = change_value($st);
		$row_value = get_value_in_text();
		foreach($row_value as $value){
			if(strpos(trim($str),trim(change_value($value))) !== FALSE)
			{
				$len = strpos(trim($str),trim(change_value($value)));
				if(substr($st,$len,0)==='' && $len!=0){
					$st = substr_replace($st,trim($value),$len+1);
				}
				else{
					$st = substr_replace($st,trim($value),$len,strlen(trim($value)));
				}
			}
		}
            
		$dest_id = isset($_REQUEST["id"])? htmlspecialchars($_REQUEST["id"], ENT_QUOTES) : 1;
		
		$request = ($dest_id == 1)? "" : "AND dest_id = '".$dest_id."'";
                        
                        
		//sort
		$sort_type = isset($_REQUEST["sort"])? $_REQUEST["sort"] : "";
		ltrim($sort_type);
		rtrim($sort_type);		
		$style1 = "";
		$style2 = "";
		$style3 = "";
		if ($sort_type == "rate") { $sort_query = "rate"; $style2 = "font-weight :bold";}
		elseif ($sort_type == "view") { $sort_query = "page_view"; $style3 = "font-weight :bold"; }
		else { $sort_query = "post_id"; $style1 = "font-weight :bold"; }		
		
		$href = "search_display.php?search_text=".$st."&id=". $dest_id;
            ?>
		
		<div> 
		<span class="style2">
		Sorted by: <a style="<?php echo $style1 ?>" href= "<?php echo $href?>">posted time</a>
		| <a style="<?php echo $style2 ?>" href= "<?php echo $href . "&sort=rate"?>">rates</a>
		| <a style="<?php echo $style3 ?>" href= "<?php echo $href . "&sort=view"?>">views</a>
 		</span>
		<div>
		<br />
		
		<?php      
		//content		
		$num_per_page = 20;
		$page = isset($_GET["page"])? $_GET["page"] : 1;
		
        //echo $st;
		
		//$match_query = "WHERE MATCH(post_subject,post_summary,post_text) AGAINST ('".$st."')";
		$match_query = "WHERE (post_subject LIKE '%$st%' || post_summary LIKE '%$st%' || post_text LIKE '%$st%')";
		
		$dest_query = ($dest_id == 1)? "" : "&&((SELECT dest_id 
												FROM posts
												WHERE post_id = `posts_texts`.post_id) = '".$dest_id."')";
		
		$result = mysql_query("SELECT * FROM `posts_texts` "
								.$match_query
								.$dest_query) or die(mysql_error()); 
								             
		$numrow = mysql_num_rows($result);
		$x = ($numrow > 1)? " entries have " : " entry has ";
		
		?><span class='style2'><?php echo $numrow . $x?>been found</span><br/><br/><?php
		
        if (($page - 1) * $num_per_page > $numrow) die_to_index();
		$start = ($page - 1) * $num_per_page;
		$end = $page * $num_per_page;
		
		$result = mysql_query("SELECT * FROM `posts_texts` "
								.$match_query
								.$dest_query
								."ORDER BY ( SELECT(".$sort_query.")  
											FROM posts 
											WHERE post_id = `posts_texts`.post_id ) DESC 
								LIMIT ".$start.",".$end) or die(mysql_error()); 
		
		While ($row = mysql_fetch_array($result)) {       
			
            $sql = "SELECT *
					FROM posts
					WHERE post_id='".$row['post_id']."'";
			$re2 = mysql_query($sql) or die(mysql_error());
			$post = mysql_fetch_array($re2);	
			
			$title = $row['post_subject'];
			$content = $row['post_summary'];
	
			if (isset($row["post_small_img_url"])) 
				$smallImgURL = htmlspecialchars_decode($row["post_small_img_url"], ENT_QUOTES);
	
			if (isset($row["post_big_img_url"]))
				$bigImgURL = htmlspecialchars_decode($row["post_big_img_url"], ENT_QUOTES);
				
			?>
			<div style="clear:left;">
			<div style="float: left; margin-right: 10px;">				
				<?php if ( isset($smallImgURL) && (rtrim($bigImgURL) != "") ) { ?>
					<a rel="lightbox" href="<?php echo $bigImgURL?>">
						<img class="postSmallImg" src="<?php echo $smallImgURL?>"/>
					</a>
				<?php } 
					else if (isset($smallImgURL) && (rtrim($smallImgURL) != "")) { ?>
						<img class="postSmallImg" src="<?php echo $smallImgURL?>"/>
				<?php }?>
			</div>	
			
			<div>
			<?php                  						
			//title
			echo "<a href='".getPostPermaLink($row["post_id"])."' class=\"head2\">". HtmlSpecialChars($title) . "</a><br>";      
			
			// post time information
			$posttime = $post['post_time'];
			$timelabel = date("d M, Y H:i", $posttime);
			echo "<span class='style2'>". $timelabel . "</span><p>";
			
			//content
			$s = $content;      
			$s = MakeTextViewable($s);      
			echo $s . "<p>"; 
			?>
			</div>
			</div>
			<?php
			mysql_free_result($re2);
		} 
		mysql_free_result($result);
		
		function write_link_dest($i , $c) {
				global $dest_id, $st;
				echo '<a class="link" href="search_display.php?search_text='.$st.'&id='. $dest_id.'&page='.$i.'">'.$c.'</a>';
		}
		
		if ($numrow > $num_per_page) {
			write_link_dest(1, "<<");
			$numpage = intval($numrow / $num_per_page);
			echo " ";
			for ($i = 1; $i <= $numpage; $i++) {
				if ($i > 1) echo " | ";
				if ($i == $page) {
					echo "<b>";
				}								
				write_link_dest($i, $i);
				if ($i == $page) {
					echo "</b>";
				}
			}
			echo " ";
			write_link_dest($numpage, ">>");
		}
            		
 }
 ?>