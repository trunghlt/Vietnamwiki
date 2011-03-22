<?php
/***************************************************************
Class connect table question
***************************************************************/
class Notification {
/***************************************************************
Variables
 * user's ip
 * status wkiki_aware_checked
***************************************************************/
        public $ip_address;
        public $wkiki_aware_checked;
/***************************************************************
add new record
***************************************************************/
	public function add() {
		$q = new Active;
                $mysql["ip_address"] = mysql_real_escape_string($this->ip_address);
                $arr = array(
                                "ip_address"=>$mysql["ip_address"],
                                "wkiki_aware_checked"=>1
                            );
                $q->add('notification', $arr);
	}
/***************************************************************
Check ip
 * $ip : user's ip
 return bool
***************************************************************/
        public static function Checkip($ip){
                $q = new Active;
                $arr = array("ip_address"=>$ip);
                $r = $q->select('','notification',$arr);
                if($r!=0){
                    if($r[0]['wkiki_aware_checked']==1)
                        return 1;

                }
                return 0;
        }
}
?>