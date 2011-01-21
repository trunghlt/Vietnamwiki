<?php
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
   $num_row = 10;


switch($type_sort){
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
                    /////////////////////////////
                    if(checkLike($v['id'],1)==1){
                        $like_str_q = "<span class='like_wrapper'><span id='like_q_$v[id]'><span id='like_num_$v[id]' class='_liked'>$v[like_q]</span></span></span>&nbsp;";
                    }
                    else{
                        $like_str_q = "<span class='like_wrapper'><a style='cursor: pointer;' onclick='like($v[id],1,$v[like_q],$v[id]);' id='like_q_$v[id]'><span id='like_num_$v[id]' class='_like'>$v[like_q]</span></a></span> &nbsp;&nbsp;";
                    }

                    echo "<li><div class='question' id='l_q_$v[id]'><img src='".$v['avatar']."' height=30 width=30 align='left'/>$v[name]: ".$v['content']."&nbsp;&nbsp;<a style='cursor: pointer; color: #DB1C00;text-decoration: underline;' onclick='answer($v[id]);' >reply</a> $like_str_q</div>";

                    if(is_array($a_r)){
                        echo "<ul id='q$v[id]'>";
                        foreach ($a_r as $v2)
                        {
                            ////////////////////////////
                            if(checkLike($v2['id'],2)==1){
                                $like_str_a = "<span class='like_wrapper'><span id='like_a_$v2[id]' ><span id='like_num_a_$v2[id]' class='_liked'>$v2[like_a]</span></span></span>";
                            }
                            else{
                                $like_str_a = "<span class='like_wrapper'><a style='cursor: pointer;' onclick='like($v2[id],2,$v2[like_a],$v[id]);' id='like_a_$v2[id]'><span id='like_num_a_$v2[id]' class='_like'>$v2[like_a]</span></a></span>";
                            }
                            /////////////////////////////
                            if($v2['question_id']==$v['id'])
                                echo "<li><img src='".$v2['avatar']."' height=30 width=30 align='left'/>$v2[name]: ".$v2["content"]." $like_str_a</li>";


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
<?php
/*
 * $_POST["type_view"] :
 *                      = 1 : view all
 *                      = 2 : main page
 */
echo "<ul>";
if($num_page > 1){
	$tranghh = ($s/$row_per_page)+1;
        if($s>0){
?>
            <li><a href="/viewallq_a.php?s=<?php echo $s-$num_row;?>&type_sort=<?php echo $type_sort;?>" style="color:#CC0000;">&laquo; Prev</a>&nbsp;&nbsp;&nbsp;</li>
<?php
        }
	for($i = 1; $i <= $num_page; $i++){
		if($i != $tranghh){
?>
            <li><a href="/viewallq_a.php?s=<?php echo ($i-1)*$row_per_page;?>&type_sort=<?php echo $type_sort;?>"><?=$i?></a>&nbsp;&nbsp;&nbsp;</li>
<?php
                }
		else
			echo "<li>$i&nbsp;&nbsp;&nbsp;</li>";
	}

     if($s+$num_row < $n_row) {?>
            <li><a href="/viewallq_a.php?s=<?php echo $s+$num_row;?>&type_sort=<?php echo $type_sort;?>" style="color:#CC0000;">Next &raquo;</a></li>
<?php
        }
    }
    echo "</ul>";
}
    
?>
<div style='clear:left;'><!-- --></div>
</div>
<?php

                if(isset($_POST["post_id"]))
                    $post_id = $_POST["post_id"];
                if(isset($_POST["index_id"]))
                    $index_id = $_POST["index_id"];
                if(isset($_POST["destination"]))
                    $destination = $_POST["destination"];
        }
?>
