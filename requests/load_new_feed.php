<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/CommentElement.php");
include("../core/classes/User.php");
include("../core/classes/PostElement.php");
include("../core/classes/Review.php");
include("../core/classes/ImageElement.php");
/*
 * Type = 1 :comment
 * Type = 2 :Post
 * Type = 3 :Review
 * Type = 4 :Image
 */

    if(is_numeric($_POST["feed_type"]) && is_numeric($_POST["time"])){
        $feed_type = $_POST["feed_type"];
        $time = $_POST["time"];
        $post = new PostElement;
        switch($feed_type){
            case 1 :{
                    if(is_array($arr = CommentElement::getByTime($time))){
                        echo "<ul>";
                        foreach($arr as $v){
                            $str = 'http://www.vietnamwiki.net'.getPostPermalink($v["post_id"]);
                            $post->query($v["post_id"]);
                            echo "<li><a href='$str'>$post->title</a></li>";
                        }
                        echo "</ul>";
                    }
                    else{
                        echo "No new data";
                    }
            };break;
            case 2 :{
                    if(is_array($arr = PostElement::getByTime($time))){
                        echo "<ul>";
                        foreach($arr as $v){
                            $str = 'http://www.vietnamwiki.net'.getPostPermalink($v["post_id"]);
                            echo "<li><a href='$str'>$v[post_subject]</a></li>";
                        }
                        echo "</ul>";
                    }
                    else{
                        echo "No new data";
                    }
            };break;
            case 3 :{
                    if(is_array($arr = Review::getByTime($time))){
                        echo "<ul>";
                        foreach($arr as $v){
                            $str = 'http://www.vietnamwiki.net/review.php?&id='.$v["id"];
                            $post->query($v["post_id"]);
                            echo "<li><a href='$str'>$post->title</a></li>";
                        }
                        echo "</ul>";
                    }
                    else{
                        echo "No new data";
                    }
            };break;
            case 4 :{
                    if(is_array($arr = ImageElement::getByTime($time))){
                        echo "<ul>";
                        foreach($arr as $v){
                            $str = 'http://www.vietnamwiki.net/photo.php?dest_id='.$v["dest_id"].'&page=1';
                            if($v["des"]=="" || $v["des"]==null){
                                $title = "Click here to direct that page";
                            }
                            else
                                 $title = $v["des"];
                            echo "<li><a href='$str'>$title</a></li>";
                        }
                        echo "</ul>";
                    }
                    else{
                        echo "No new data";
                    }
            };break;
        }
    }
    else{
        die_to_index();
    }
?>
