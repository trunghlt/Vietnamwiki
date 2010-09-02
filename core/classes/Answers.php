<?php
/***************************************************************
Class connect table answer
***************************************************************/
class Answers {
/***************************************************************
Variables
 * id record
 * user's id has registered
 * user's name hasn't yet registered
 * user's email hasn't yet registered
 * content's answer
 * id's topic
 * id's index
 * id's dest
 * date creates answer
 * user's ip
***************************************************************/
	public $id;
        public $question_id;
	public $user_id;
	public $username;
	public $email;
	public $content;
	public $date;
        public $ip;
/***************************************************************
Get num row in query
Return num
 * $arr : array coditions
***************************************************************/
	public function query($arr='') {
		$q = new Active;
		$r = $q->select('','answer',$arr);
              /*  if($q->get_num()==1){
                    $this->id = $r["id"];
                    $this->question_id = $r['question_id'];
                    $this->user_id = $r["user_id"];
                    $this->user_name = $r["user_name"];
                    $this->email = $r["email"];
                    $this->content = $r["content"];
                    $this->date = $r["date"];
                    $this->ip = $r["ip"];
                $arr = array(
                                "id"=>$this->id,
                                "user_id"=>$this->user_id,
                                "question_id"=>$this->question_id,
                                "username"=>$this->username,
                                "email"=>$this->email,
                                "content"=>$this->content,
                                "date"=>$this->date,
                                "ip"=>$this->ip
                            );
                    return $arr;
                }*/
                if($q->get_num()>0){
                    return $r;
                }
                else {
                    return 0;
                }
	}
/***************************************************************
add new record
***************************************************************/
	public function add() {
		$q = new Active;
                $arr = array(
                                "user_id"=>$this->user_id,
                                "question_id"=>$this->question_id,
                                "username"=>$this->username,
                                "email"=>$this->email,
                                "content"=>$this->content,
                                "date"=>$this->date,
                                "ip"=>$this->ip
                            );
                $this->id = $q->add('answer', $arr);
                $memcache->delete("ans");
	}
/***************************************************************
delete table answer
***************************************************************/
	public function remove() {
		$q = new Active;
		$q->delete('answer');
	}
/***************************************************************
delete table answer with condition
***************************************************************/
	public function delete_id($id) {
		$q = new Active;
		$q->delete('answer',"id=$id");
                $memcache->delete("ans");
	}
/***************************************************************
edit a record in table answer
***************************************************************/
	public function edit(){
		$q = new Active;
                $arr = array(
                                "user_id"=>$this->user_id,
                                "question_id"=>$this->question_id,
                                "username"=>$this->username,
                                "email"=>$this->email,
                                "content"=>$this->content,                                
                                "date"=>$this->date,
                                "ip"=>$this->ip
                            );
                $q->update('answer', $arr,"id=$this->id");
                $memcache->delete("ans");
	}
}
?>