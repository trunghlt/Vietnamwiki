<?php
/***************************************************************
Class connect table question
***************************************************************/
class Questions {
/***************************************************************
Variables
 * id record
 * user's id has registered
 * user's name hasn't yet registered
 * user's email hasn't yet registered
 * content's question
 * id's topic
 * id's index
 * id's dest
 * date creates question
 * user's ip
***************************************************************/
	public $id;
	public $user_id;
	public $username;
	public $email;
	public $content;
	public $topic_id;
	public $index_id;
	public $dest_id;
	public $date;
        public $ip;
/***************************************************************
Get num row in query
Return num
 * $arr : array coditions
 ***************************************************************/
	public function query($arr='',$limit='',$orderby='') {
		$q = new Active;
                if($limit!='')
                    $q->limit ($limit);
                if($orderby!='')
                    $q->orderby ($orderby);
		$r = $q->select('','question',$arr);
               /* if($q->get_num()==1){
                    $this->id = $r["id"];
                    $this->user_id = $r["user_id"];
                    $this->user_name = $r["user_name"];
                    $this->email = $r["email"];
                    $this->content = $r["content"];
                    $this->topic_id = $r["topic_id"];
                    $this->index_id = $r["index_id"];
                    $this->dest_id = $r["dest_id"];
                    $this->date = $r["date"];
                    $this->ip = $r["ip"];
                $arr = array(
                                "id"=>$this->id,
                                "user_id"=>$this->user_id,
                                "user_name"=>$this->user_name,
                                "email"=>$this->email,
                                "content"=>$this->content,
                                "topic_id"=>$this->topic_id,
                                "index_id"=>$this->index_id,
                                "dest_id"=>$this->dest_id,
                                "date"=>$this->date,
                                "ip"=>$this->ip
                            );
                    return $arr;
                }
                else*/ if($q->get_num()>0){
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
                                "username"=>$this->username,
                                "email"=>$this->email,
                                "content"=>$this->content,
                                "topic_id"=>$this->topic_id,
                                "index_id"=>$this->index_id,
                                "dest_id"=>$this->dest_id,
                                "date"=>$this->date,
                                "ip"=>$this->ip
                            );
                $q->add('question', $arr);
                $memcache->delete("ques");
	}
/***************************************************************
delete table question
***************************************************************/
	public function remove() {
		$q = new Active;
		$q->delete('question');
	}
/***************************************************************
delete table question with condition
***************************************************************/
	public function delete_id($id) {
		$q = new Active;
		$q->delete('question',"id=$id");
                $memcache->delete("ques");
	}
/***************************************************************
edit a record in table question
***************************************************************/
	public function edit(){
		$q = new Active;
                $arr = array(
                                "user_id"=>$this->user_id,
                                "username"=>$this->username,
                                "email"=>$this->email,
                                "content"=>$this->content,
                                "topic_id"=>$this->topic_id,
                                "index_id"=>$this->index_id,
                                "dest_id"=>$this->dest_id,
                                "date"=>$this->date,
                                "ip"=>$this->ip
                            );
                $q->update('question', $arr,"id=$this->id");
                $memcache->delete("ques");
	}
}
?>