<?php
/***************************************************************
Class connect table ip question
***************************************************************/
class IpQuestions {
/***************************************************************
Variables
 * ip : user's ip
 * question_ids : question's id
***************************************************************/
        public $ip;
        public $question_ids;

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
		$r = $q->select('','ip_questions',$arr);
                if(is_array($r)){
                   return $r;
                }
                else {
                    return null;
                }
	}
/***************************************************************
add new record
***************************************************************/
	public function add($value,$id) {
		$q = new Active;
                $arr = array(
                                "ip"=>$this->ip,
                                "question_ids"=>$this->question_ids
                            );
                $arr2 = array(
                                "like_q"=>$value
                            );
                $this->id = $q->add('ip_questions', $arr);
                $q->update('question', $arr2,"id=".$id);
	}
/***************************************************************
delete table ip question
***************************************************************/
	public function remove() {
		$q = new Active;
		$q->delete('ip_questions');
	}
/***************************************************************
delete table ip question with condition
***************************************************************/
	public function delete_id($ip) {
		$q = new Active;
		$q->delete('ip_questions',"ip='$ip'");
	}

/***************************************************************
edit a record in table ip question
 * value : change value in question
 * id : question's id
***************************************************************/
	public function editLike($value,$id){
		$q = new Active;
                $arr = array(
                                "question_ids"=>$this->question_ids
                            );
                $arr2 = array(
                                "like_q"=>$value
                            );
                $q->update('ip_questions', $arr,"ip='".$this->ip."'");
                $q->update('question', $arr2,"id=".$id);
	}

/***************************************************************
Get value
 * ip : user's ip
***************************************************************/
       public static function getByIp($ip){
            $arr_query = array('ip'=>$ip);
            $n_row = self::query($arr_query);
            if(is_array($n_row))
                    return $n_row;
             return 0;
        }
}
?>
