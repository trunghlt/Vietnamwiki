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

function checkLike($id){
    $arr = IpAnswers::getByIp(myip());
    $str = "answer_ids";
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
if(is_numeric($_POST["id_q"])){
    $id_q = $_POST["id_q"];
    $arr = Answers::getA($id_q);
    if(is_array($arr)){
        foreach ($arr as $v2)
        {
            ////////////////////////////
            if(checkLike($v2['id'])==1){
                $like_str_a = "&nbsp;&nbsp;<span id='like_a_$v2[id]' class='_liked'>Like</span>&nbsp;";
            }
            else{
                $like_str_a = "&nbsp;&nbsp;<a style='cursor: pointer;' onclick='like($v2[id],2,$v2[like_a]);' id='like_a_$v2[id]'><img src='../css/images/like.jpg' /></a> &nbsp;&nbsp;";
            }
            /////////////////////////////
           echo "<li><img src='".$v2['avatar']."' height=30 width=30 align='left'/>$v2[name]:".$v2["content"]."<br />$like_str_a<span id='like_num_a_$v2[id]' class='_like'>$v2[like_a]</span></li>";
        }
    }
}
?>
