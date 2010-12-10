<?php
/***************************************************************
Class connect table ip answer
***************************************************************/
class IpAnswers {
/***************************************************************
Variables
 * ip : user's ip
 * answer_ids : answer's id
***************************************************************/
        public $ip;
        public $answer_ids;

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
		$r = $q->select('','ip_answers',$arr);
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
                                "answer_ids"=>$this->answer_ids
                            );
                $arr2 = array(
                                "like_a"=>$value
                            );
                $this->id = $q->add('ip_answers', $arr);
                $q->update('answer', $arr2,"id=".$id);
	}
/***************************************************************
delete table ip answer
***************************************************************/
	public function remove() {
		$q = new Active;
		$q->delete('ip_answers');
	}
/***************************************************************
delete table ip answer with condition
***************************************************************/
	public function delete_id($ip) {
		$q = new Active;
		$q->delete('ip_answers',"ip=$ip");
	}

/***************************************************************
edit a record in table ip answer
 * value : change value in answer
 * id : answer's id
***************************************************************/
	public function editLike($value,$id){
		$q = new Active;
                $arr = array(
                                "answer_ids"=>$this->answer_ids
                            );
                $arr2 = array(
                                "like_a"=>$value
                            );
                $q->update('ip_answers', $arr,"ip='".$this->ip."'");
                $q->update('answer', $arr2,"id=".$id);
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
