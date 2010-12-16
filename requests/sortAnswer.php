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
/*
 * type = 1: question
 * type = 2: answer
 */
if(is_numeric($_POST["type"])){
    $type=$_POST["type"];
}
else
    $type=0;
if($type==2){
    if(is_numeric($_POST["id_q"])){
        $id_q = $_POST["id_q"];
        $arr = Answers::getA($id_q);
        if(is_array($arr)){
            foreach ($arr as $v2)
            {
                ////////////////////////////
                if(checkLike($v2['id'],2)==1){
                    $like_str_a = "<span id='like_a_$v2[id]'><span id='like_num_a_$v2[id]' class='_liked'>$v2[like_a]</span></span>";
                }
                else{
                    $like_str_a = "<a style='cursor: pointer;' onclick='like($v2[id],2,$v2[like_a],$id_q);' id='like_a_$v2[id]'><span id='like_num_a_$v2[id]' class='_like'>$v2[like_a]</span></a>";
                }
                /////////////////////////////
               echo "<li><img src='".$v2['avatar']."' height=30 width=30 align='left'/>$v2[name] <span style='font-weight: normal;font-style: italic;' >said</span>: ".$v2["content"]." $like_str_a</li>";
            }
        }
    }
}
else if($type==1){
    if(is_numeric($_POST["id_q"])){
        $id_q = $_POST["id_q"];
        $v = Questions::getQById($id_q);
        if(is_array($v)){
                ////////////////////////////
                if(checkLike($v['id'],1)==1){
                    $like_str_q = "<span id='like_q_$v[id]' ><span id='like_num_$v[id]' class='_liked'>$v[like_q]</span></span>";
                }
                else{
                    $like_str_q = "<a style='cursor: pointer;' onclick='like($v[id],1,$v[like_q],$v[id]);' id='like_q_$v[id]'><span id='like_num_$v[id]' class='_like'>$v[like_q]</span></a>";
                }
                /////////////////////////////
               echo "<img src='".$v['avatar']."' height=30 width=30 align='left'/>$v[name] <span style='font-weight: normal;font-style: italic;'>asked</span>: ".$v['content']."&nbsp;&nbsp;<a style='cursor: pointer; color: #DB1C00;text-decoration: underline;' onclick='answer($v[id]);' >reply</a> $like_str_q";
        }
    }
}
?>
