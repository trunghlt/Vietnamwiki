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
        switch($feed_type){
            case 1 :{
                    if(is_array($arr = PostElement::getByTime($time))){
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
                    if(is_array($arr = CommentElement::getByTime($time))){
                        echo "<ul>";
                        foreach($arr as $v){
                            if($v["post_id"]!=0){
                                $str = 'http://www.vietnamwiki.net'.getPostPermalink($v["post_id"]);
                                $post->query($v["post_id"]);
                                echo "<li><a href='$str'>$post->title</a></li>";
                            }
                            else{
                                 echo "<li>No data</li>";
                            }
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
                            $str = 'http://www.vietnamwiki.net/review.php?&id='.$v["post_id"];
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
                            $des->query($v["dest_id"]);
                            echo "<li><a href='$str'>$des->engName</a></li>";
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
