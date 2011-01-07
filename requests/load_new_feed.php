<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/CommentElement.php");
include("../core/classes/User.php");
include("../core/classes/PostElement.php");
include("../core/classes/IndexElement.php");
include("../core/classes/DestinationElement.php");
include("../core/classes/Review.php");
include("../core/classes/ImageElement.php");
/*
 * Type = 1 :Post
 * Type = 2 :Comment
 * Type = 3 :Review
 * Type = 4 :Image
 */

    if(is_numeric($_POST["feed_type"]) && is_numeric($_POST["time"])){
        $feed_type = $_POST["feed_type"];
        $time = $_POST["time"];
        $post = new PostElement;
        $des = new DestinationElement;
        $row_per_page = 5;
        $num_item = 0;
        if(is_numeric($_POST["s"]))
            $s = $_POST["s"];
        else
            $s = 0;
        
        switch($feed_type){
            case 1:$arr = PostElement::getByTime($time);$p = "new_articles" ;break;
            case 2:$arr = CommentElement::getByTime($time);$p = "new_comments" ;break;
            case 3:$arr = Review::getByTime($time);$p = "new_reviews" ;break;
            case 4:$arr = ImageElement::getByTime($time);$p = "new_uploaded_images" ;break;
        }
        if(is_array($arr)){
            //////pagination(num page)/////
            $n_row = count($arr);
            if($n_row > $row_per_page)
                $num_page = ceil($n_row/$row_per_page);
            else 
                $num_page = 1;
            $per = (($s/$row_per_page)+1)*$row_per_page;
            if($per <= $n_row)
		$r = $per;
            else
		$r = $n_row;
            //////////////////////////////

            echo "<ul>";
            foreach($arr as $key=>$v){
                if($num_item == $row_per_page)
                    break;
                if($key >= $s && $key < $r ){
                    $num_item = $num_item + 1;
                    if($feed_type==1 || $feed_type==2 || $feed_type==3){
                        if($v["post_id"]!=0){
                            if($feed_type==1 || $feed_type==2)
                                $str = 'http://www.vietnamwiki.net'.getPostPermalink($v["post_id"]);
                            else
                                $str = 'http://www.vietnamwiki.net/review.php?&id='.$v["post_id"];

                            $post->query($v["post_id"]);
                            if($post->title=='' || $post->title==NULL)
                                $title = "No title";
                            else
                                $title = htmlspecialchars_decode($post->title, ENT_QUOTES);
                        }
                        else
                            $title = "no data";
                    }
                    else if($feed_type==4){
                        $str = 'http://www.vietnamwiki.net/photo.php?dest_id='.$v["dest_id"].'&page=1';
                        $des->query($v["dest_id"]);
                        $title = $des->engName;
                    }
                    echo "<li><a href='$str'>".$title."</a></li>";
                }
            }
            echo "</ul>";

            //////pagionation/////
            echo "<div class='phantrang'><ul >";
            if($num_page > 1){
                $tranghh = ($s/$row_per_page)+1;
                if($s>0){
                ?>
                    <li><a onclick="loadPage(<?php echo $s-$row_per_page;?>,<?=$feed_type?>,<?=$time?>,'<?=$p?>');return false;" style="color:#CC0000; cursor: pointer;">&laquo; Prev</a>&nbsp;&nbsp;&nbsp;</li>
                <?php
                }
                if($s+$row_per_page < $n_row) {?>
                        <li><a onclick="loadPage(<?php echo $s+$row_per_page;?>,<?=$feed_type?>,<?=$time?>,'<?=$p?>');return false;" style="color:#CC0000; cursor: pointer;">Next &raquo;</a></li>
                <?php
                    }
            }
            echo "</ul></div>";
            //////////////////////
        }
    }
    else{
        die_to_index();
    }
?>
