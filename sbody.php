<?php
#----begin controller-----#
function get_value_in_text(){
	return $arr = file('file.txt');
}
function change_value($v){
		//return mysql_real_escape_string(str_replace(" ", "+", $v));
    return mysql_real_escape_string($v);
}

      $st = (isset($_REQUEST["search_text"]))?$_REQUEST["search_text"]:"vietnam";
      if (isset($st)) {

		$st = htmlspecialchars($st, ENT_QUOTES);

		$st = change_value($st);


		$type_view = isset($_REQUEST["type_view"])? (is_numeric($_REQUEST["type_view"])?$_REQUEST["type_view"]:4 ): 4;

                $type_view = ($type_view > 4 || $type_view < 0)? 3:$type_view-1;

		//sort
        $num_per_page = 20;
		$style1 = "";
		$style2 = "";
                $style3 = "";
		if ($type_view == 1) { $sort_query = "question";$num_per_page = 5;; $style1 = "font-weight :bold"; }
        elseif($type_view == 2){ $sort_query = "review"; $num_per_page = 5;$style2 = "font-weight :bold"; }
		elseif($type_view == 0){ $sort_query = "answer"; $num_per_page = 5;$style0 = "font-weight :bold"; }
		else { $sort_query = "article"; $style3 = "font-weight :bold"; }

		$href = "search_display.php?search_text=".$st;

#----get data-----#

		$page = isset($_GET["page"])? $_GET["page"] : 1;

    $solr = new Solr;
    $check_sorl = $solr->check_exists_data($type_view);
    if($check_sorl==-1) die_to_index();
    elseif($check_sorl==0){
        $solr->add_data($type_view);
    }
	if($type_view==1 || $type_view==0){
		$add_type = ($type_view==1)?0:1;
		$check_sorl = $solr->check_exists_data($add_type);
		if($check_sorl==-1) die_to_index();
		elseif($check_sorl==0){
			$solr->add_data($add_type);
		}
	}
#----check entry-----#
		//$arr = $solr->get_solr($type_view,$st,$page,$num_per_page);
 		$start = ($page - 1) * $num_per_page;
		$arr1 = $solr->get_solr($type_view,$st);

		if(!is_array($arr1)) {
                    if($arr1 == -1)
                        die_to_index();
        }
		$numrow = $arr1[count($arr1)-1];
		
    if($numrow>0){
		$x = ($numrow > 1)? " entries have " : " entry has ";

        if (($page - 1) * $num_per_page > $numrow) die_to_index();
 		$start = ($page - 1) * $num_per_page;
		if($type_view==1 || $type_view==0){
			$sorl2 = new Solr;
		}

		$arr = $solr->get_solr($type_view,$st,$start,$num_per_page);
#----next page-----#		
		function write_link_dest($i , $c) {
				global $type_view, $st;
				echo '<a class="link" href="search_display.php?search_text='.$st.'&type_view='.($type_view+1).'&page='.$i.'">'.$c.'</a>';
		}
#----end controller-----#
#---- begin view -------#
?>
		<div>
		<span class="style2">
		Search : <a style="<?php echo $style3 ?>" href= "<?php echo $href ."&type_view=4"?>">Article</a>
		| <a style="<?php echo $style2 ?>" href= "<?php echo $href . "&type_view=3"?>">Review</a>
		| <a style="<?php echo $style1 ?>" href= "<?php echo $href . "&type_view=2"?>">Question</a>
		| <a style="<?php echo $style0 ?>" href= "<?php echo $href . "&type_view=1"?>">Answer</a>
 		</span>
		<div>
		<br />
		<span class='style2'><?php echo $numrow . $x?>been found</span><br/><br/>
<?php		
		if(is_array($arr)){
          foreach($arr as $row) {
		   if(is_array($row)){
			$posttime = 0;

			$title = ($row['subject']!="")?$row['subject']:"No title";
			$content = ($row['summary']!="")?$row['summary']:$row['hightlighted_content'];
			$smallImgURL = ($row["post_small_img_url"]!="")?$row["post_small_img_url"]:"";
			$bigImgURL = ($row["post_big_img_url"]!="")?$row["post_big_img_url"]:"";
			$link = $row["link"];
			$timelabel = $row['date'];

			?>
			<div style="clear:both;">
			<div style="float: left; margin-right: 10px;">
				<?php if ( $smallImgURL!="" && $bigImgURL != "" ) { ?>
					<a rel="lightbox" href="<?php echo $bigImgURL?>">
						<img class="postSmallImg" src="<?php echo $smallImgURL?>"/>
					</a>
				<?php }
					else if ($smallImgURL!="" && $bigImgURL == "") { ?>
						<img class="postSmallImg" src="<?php echo $smallImgURL?>"/>
				<?php }?>
			</div>

			<div>
			<?php
			//title
                        if($type_view==3 || $type_view==2)
                            echo "<a href='".$link."' class=\"head2\">". $title. "</a><br>";
                        else
                            echo "<a>". $title . "</a><br>";
			// post time information
			echo "<span class='style2'>". $timelabel . "</span><p>";

			//content
		    # Temporary fix for broken html snippet of search
		     echo $content . "</p>";
		    # ---- genuine version ------
			#echo $row["summary"] . "</p>";
			
			
				if(isset($sorl2)){
				  if($type_view==0)
					$arr2 = $sorl2->get_a_q(1,$row['question_id']);
				  elseif($type_view==1)
					$arr2 = $sorl2->get_a_q(0,$row['id']);
				  if(is_array($arr2)){
					if($arr2[count($arr2)-1]>0){
						echo "<ul>";
						foreach($arr2 as $sub_v){
							if(is_array($sub_v)){
								 echo "<li><a>". $sub_v['subject'] . "</a><br>";
								 echo "<span class='style2'>". $sub_v['date'] . "</span>";
								 echo "<p>". $sub_v['summary'] . "</p><br></li>";
							}
						}
						echo "</ul>";
					}
				  }
				}
			?>
			</div>
			</div>
			<?php
          }
		 }
        }
        else{
        ?>
                <span class='style2'>No entry has been found</span><br/><br/>
        <?php
        }
#------ end view -------#

		if ($numrow > $num_per_page) {
			echo "<div style='clear:left'>";
			$numpage = ceil(($numrow / $num_per_page));
			if($numpage>1){
				write_link_dest(1, "<<");
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
			echo "</div>";
		}
	}
        else
           echo "<span class='style2'>No entry has been found</span><br/><br/>";
 }
 ?>
 </div>
 <div class="clear_search"><!----></div>
