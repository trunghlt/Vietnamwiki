<?php
include("../core/init.php");
include("../core/common.php");
include("../core/classes/Db.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/Questions.php");
include("../core/classes/Answers.php");
include("../core/classes/IpAnswers.php");
include("../core/classes/IpQuestions.php");
if(is_numeric($_POST["type"]) && is_numeric($_POST["id"]) && is_numeric($_POST["value"])){
    $type = $_POST["type"];
    $id = $_POST["id"];
    $value = $_POST["value"]+1;
    if($type==1){
        $ip_q = new IpQuestions;
        $ip_q->ip = myip();
        $arr = IpQuestions::getByIp($ip_q->ip);
        if(is_array($arr))
        {
            $r = $arr[0]["question_ids"];
            $r .= "/$id";
            $ip_q->question_ids = $r;
            $ip_q->editLike($value,$id);
        }
        else
        {
            $ip_q->question_ids = $id;
            $ip_q->add($value,$id);
        }
    }
    else if($type==2){
        $ip_a = new IpAnswers;
        $ip_a->ip = myip();
        $arr = IpAnswers::getByIp($ip_a->ip);
        if(is_array($arr))
        {
            $r = $arr[0]["answer_ids"];
            $r .= "/$id";
            $ip_a->answer_ids = $r;
            $ip_a->editLike($value,$id);
        }
        else
        {
            $ip_a->answer_ids = $id;
            $ip_a->add($value,$id);
        }
    }
}
?>
