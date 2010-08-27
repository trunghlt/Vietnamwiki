<?php
include("../core/init.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/Db.php");
include("../core/common.php");
include("../core/classes/User.php");
include('../libraries/TalkPHP_Gravatar.php');
include("../core/classes/Questions.php");
include("../core/classes/Answers.php");

        $u = new User;
        function getname($v){
            global $u;
                if($v['user_id']!='' || $v['user_id']!=NULl){
                    $r_u = $u->query_id($v['user_id']);
                    $str = "<font color='#DB1C00' style='font-weight:bold;'>".$r_u['username']."</font>";
                }
                else
                {
                    if($v['username']!='' || $v['username']!=NULL)
                        $str = "<font color='#DB1C00' style='font-weight:bold;' >$v[username]</font>";
                    else
                        $str = '<font color="#DB1C00" style="font-weight:bold;" >Guest</font>';
                }
                return $str;
        }
        function getQ(){
            $memcache = new Memcache;
            $memcache->connect("127.0.0.1", 11211);
            $q = new Questions;
            global $u;
            $n_row = $q->query("","","date desc");
            $i= 0;
            foreach ($n_row as $v){
                $arr[$i] = array( 'id'=>$v['id'],
                              'user_id'=>$v['user_id'],
                              'username'=>$v['username'],
                              'email'=>$v['email'],
                              'content'=>$v['content'], 
                              'topic_id'=>$v['topic_id'],
                              'index_id'=>$v['index_id'],
                              'dest_id'=>$v['dest_id'],
                              'date'=>$v['date'],
                              'ip'=>$v['ip'],
                              'avatar'=>$u->getUserAvatar($v),
                              'name'=>getname($v)
                                     );
                $i = $i+1;
            }
            $memcache->set("ques",$arr);
            return $arr;
        }
        function getA(){
            $memcache = new Memcache;
            $memcache->connect("127.0.0.1", 11211);
            $q = new Answers;
            global $u;
            $n_row = $q->query();
            $i= 0;
            foreach ($n_row as $v){
                $arr[$i] = array( 'id'=>$v['id'],
                              'question_id'=>$v['question_id'],
                              'user_id'=>$v['user_id'],
                              'username'=>$v['username'],
                              'email'=>$v['email'],
                              'content'=>$v['content'],
                              'date'=>$v['date'],
                              'ip'=>$v['ip'],
                              'avatar'=>$u->getUserAvatar($v),
                              'name'=>getname($v)
                                     );
                $i = $i+1;
            }
            $memcache->set("ans",$arr);
            return $arr;
        }
        $q_s = $memcache->get("ques");
        if($q_s == NULL){
            $q_s = getQ();
        }
        $a_r = $memcache->get("ans");
        if($a_r == NULL){
            $a_r = getA();
        }
	$row_per_page = 3;
	//$id = $_POST["id"];
	$s = $_POST["start"];
        if(count($q_s)>0){
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
                    echo "<li><div class='question'><img src='".$v['avatar']."' height=30 width=30 align='left'/> $v[name] : ".$v['content']."    <a style='cursor: pointer; color: #DB1C00;text-decoration: underline;' onclick='answer(\"askquestion\");' >Rely</a></div>";
                    if(count($a_r)){
                        echo "<ul>";
                        foreach ($a_r as $v2)
                        {
                            if($v2['question_id']==$v['id'])
                                echo "<li><img src='".$v2['avatar']."' height=30 width=30 align='left'/>  $v2[name] : ".$v2["content"]."</li>";
                        }
                        echo "</ul>";
                    }
                    echo "</li>";
                }
            }
            echo "</ul>";
        }
?>
<br />
<div class="phantrang">
<?php
	$tranghh = ($s/$row_per_page)+1;
	for($i = 1;$i <= $num_page; $i++)
		if($i != $tranghh)
			echo "<a style='cursor: pointer; color: #DB1C00;' onclick='load_qanda(".($i-1)*$row_per_page.");'>$i</a>";
		else
			echo " ".$i." ";
?>
</div>