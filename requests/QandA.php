<?php
include("../core/init.php");
include("../core/common.php");
include("../core/classes/Db.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/User.php");
include('../libraries/TalkPHP_Gravatar.php');
include("../core/classes/Questions.php");
include("../core/classes/Answers.php");
include("../core/classes/IpAnswers.php");
include("../core/classes/IpQuestions.php");

////////////////////////////////////////////////////////////////////////////////
/*
 * Check Like in question and anwser by Ip
 * type :
 *      1 : question
 *      2 : answer
 */
////////////////////////////////////////////////////////////////////////////////
function checkLike($id,$type){
    if($type==1){
        $arr = IpQuestions::getByIp(myip());
        $str = "question_ids";
    }
    else{
        $arr = IpAnswers::getByIp(myip());
        $str = "answer_ids";
    }
    if(is_array($arr))
    {
        $r = explode("/", $arr[0][$str]);
        foreach($r as $key=>$value)
        {
            if( $value === $id)
                return 1;
        }
    }
    return 0;
}
////////////////////////////////////////////////////////////////////////////////
if(is_numeric($_POST["num_row"]))
    $num_row = $_POST["num_row"];
else
   $num_row = 5;

if(is_numeric($_POST["start"]))
    $s = $_POST["start"];
else
    $s = 0;
/*
 * sort=1, sort with answer
 */
$sort = 0;
if(isset($_POST["sort"]))
{
    if(is_numeric($_POST["sort"]) && $_POST["sort"]==1)
        $sort = $_POST["sort"];
}
/*
 * type:
 *      = 1 : sort question by time
 *      = 2 : sort question by like
 */
if(is_numeric($_POST["type"]))
    $type = $_POST["type"];
else
    $type = 1;

switch($type){
    case 1:{
        $q_s = Mem::$memcache->get("ques");
        if($q_s == NULL){
            $q_s = Questions::getQ(1);
        }
        $a_r = Mem::$memcache->get("ans");
        if($a_r == NULL){
            $a_r = Answers::getA();
        }
    };break;
    case 2:{
        $q_s = Mem::$memcache->get("ques_like");
        if($q_s == NULL){
            $q_s = Questions::getQ(2);
        }
        $a_r = Mem::$memcache->get("ans");
        if($a_r == NULL){
            $a_r = Answers::getA();
        }
    };break;
}

	$row_per_page = $num_row;
        if(is_array($q_s)){
            $n_row = count($q_s);
            if($n_row > $row_per_page)
                $num_page = ceil($n_row/$row_per_page);
            else {
                $num_page = 1;
            }
            $per = (($s/$row_per_page)+1)*$row_per_page;
            if($per <= $n_row)
		$r = $per;
            else
		$r = $n_row;
            echo "<ul>";
            foreach ($q_s as $key=>$v){
                if($key >= $s && $key < $r ){
                    if($sort==1){
                        $sort_str = "&nbsp;&nbsp;<a style='cursor: pointer;' onclick='sortanswer($v[id]);'><img src='../css/images/demo-spindown-open.gif' /></a>";
                    }
                    else
                        $sort_str = '';
                    /////////////////////////////
                    if(checkLike($v['id'],1)==1){
                        $like_str_q = "&nbsp;&nbsp;<span id='like_q_$v[id]' class='_liked'>Like</span>&nbsp;";
                    }
                    else{
                        $like_str_q = "&nbsp;&nbsp;<a style='cursor: pointer;' onclick='like($v[id],1,$v[like_q]);' id='like_q_$v[id]'><img src='../css/images/like.jpg' /></a> &nbsp;&nbsp;";
                    }

                    echo "<li><div class='question'><img src='".$v['avatar']."' height=30 width=30 align='left'/>$v[name]:".$v['content']."&nbsp;&nbsp;<a style='cursor: pointer; color: #DB1C00;text-decoration: underline;' onclick='answer($v[id]);' >reply</a>$sort_str<br />$like_str_q<span id='like_num_$v[id]' class='_like'>$v[like_q]</span></div>";

                    if(is_array($a_r)){
                        echo "<ul id='q$v[id]'>";
                        foreach ($a_r as $v2)
                        {
                            ////////////////////////////
                            if(checkLike($v2['id'],2)==1){
                                $like_str_a = "&nbsp;&nbsp;<span id='like_a_$v2[id]' class='_liked'>Like</span>&nbsp;";
                            }
                            else{
                                $like_str_a = "&nbsp;&nbsp;<a style='cursor: pointer;' onclick='like($v2[id],2,$v2[like_a]);' id='like_a_$v2[id]'><img src='../css/images/like.jpg' /></a> &nbsp;&nbsp;";
                            }
                            /////////////////////////////
                            if($v2['question_id']==$v['id'])
                                echo "<li><img src='".$v2['avatar']."' height=30 width=30 align='left'/>$v2[name]:".$v2["content"]."<br />$like_str_a<span id='like_num_a_$v2[id]' class='_like'>$v2[like_a]</span></li>";
                        }
                        echo "</ul>";
                    }
                    echo "<div class='clear'></div></li>";
                }
            }
            echo "</ul>";
        

        if($num_page > 1){
?>
<br />
<div class="phantrang">
<?php if($s>0){?>
    <div class='prev'><a onclick="load_qanda(<?php echo $s-$row_per_page;?>);" style=" cursor: pointer; color: #CC0000; text-decoration: underline;">&laquo; Prev</a></div>
<?php } if(($s+$row_per_page) <= $n_row) {?>
<div class='next'><a  onclick="load_qanda(<?php echo $s+$row_per_page;?>);" style=" cursor: pointer; color: #CC0000; text-decoration: underline;">&nbsp;Next &raquo;</a></div>
<?php }?>
<div style='clear:left;'><!-- --></div>
</div>
<?php
            }
                if(isset($_POST["post_id"]))
                    $post_id = $_POST["post_id"];
                if(isset($_POST["index_id"]))
                    $index_id = $_POST["index_id"];
                if(isset($_POST["destination"]))
                    $destination = $_POST["destination"];
                include("../forms/askquestion.php");
                include("../forms/replyquestion.php");
        }
?>
