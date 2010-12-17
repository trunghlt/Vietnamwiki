<?php 
class User {
	public $username;
	public $user_ip;
	public $id;
	public $level;
	public $password;
	public $email;
	public $regDateTime;
	public $firstName;
	public $lastName;
	public $locationCode;
	public $dob;
	public $avatar;
	public $fbId;
	
	/*-----------------------------------------------------------
	Parse from an array into a new User object
	- $arr: the input array
	->User object
	-----------------------------------------------------------*/
	public static function parseArr($arr) {
		$e = new User;
		$e->assignFromArr($arr);
		return $e;
	}
	
	/*------------------------------------------------------------------
	Check whether a Facebook user has registered or not
	- $fbUserId: Facebook user id
	->false: not registered yet, true: already registered
	------------------------------------------------------------------*/
	public static function chkFbUserReged($fbUserId) {
		$q = new db;
		$q->query("SELECT * FROM users WHERE fbId={$fbUserId}");
		return (mysql_num_rows($q->re) > 0);	
	}
	
	
	/*-----------------------------------------------------------
	Assign values of an array into user's properties
	- $arr: the input array
	-----------------------------------------------------------*/
	public function assignFromArr($arr) {
		array2object($arr, $this);
	}
	
	
	public static function getUserElementById($id) {
		$e = new User;
		$e->query($id);
		return $e;
	}
	
	function update() {	
		global $q;
		$this->user_ip = myip();
		$sql = "SELECT user_id
				FROM sessions
				WHERE ip = '".$this->user_ip."'";
		$q->query($sql);
		$r = mysql_fetch_array($q->re);
		$this->id = $r["user_id"];
		
		$sql = "SELECT *
				FROM users
				WHERE id = '".$this->id."'";
		$q->query($sql);
		$r = mysql_fetch_array($q->re);
		$this->assignFromArr($r);
	}
	
	function query($id) {
		$this->id = 0;
		if ($id == 0) {
			$this->username = "Anonymous traveler";
			return;
		}		
		$q = new db();
		$q->query(" SELECT *
					FROM users
					WHERE id = $id");
		$r = mysql_fetch_array($q->re);
		$this->assignFromArr($r);
	}
	
	function queryByFbId($fbId) {
		$q = new db;
		$q->query("SELECT * FROM users WHERE fbId={$fbId}");
		$r = mysql_fetch_array($q->re);
		$this->assignFromArr($r);
	}
	
	
	function add() {
		db::sQuery("INSERT INTO users
					(username, password, email, regDateTime, level, firstName, lastName, locationCode, dob, avatar, fbId)
					VALUES ('{$this->username}', '{$this->password}', '{$this->email}', '{$this->regDateTime}', '{$this->level}', '{$this->firstName}', '{$this->lastName}', '{$this->locationCode}', '{$this->dob}', '{$this->avatar}', '{$this->fbId}')");
		$this->id = mysql_insert_id();
	}
	
	public function update_email($id,$email){
		$q = new db;
		$q->query("update users set email='".$email."' where id=".$id);
	}
	
	public static function check_user($user_id,$post_id=''){
		$q = new db;
			$q->query('select * from users where id='.$user_id.' and level=1');
			if($q->n==0)
			{
				if($post_id!=''){
                                    $q->query('select * from follow where user_id='.$user_id.' and post_id='.$post_id);
                                            return $q->n;
                                }
			}
			return $q->n;
	}
	public static function check_user_post($user_id,$post_id=''){
		$q = new db;
		if($user_id != ""){
			$q->query("select property_value
						from setting
						where property_name = 'ALLOW_DIRECT_UPDATE'");
			$row = mysql_fetch_assoc($q->re);
			//Allow user =1
			if($row['property_value']==0){
				$q->query("select level
							from users
							where id='".$user_id."'");
				$row2 = mysql_fetch_assoc($q->re);
				if($row2['level']==1){
                                    return TRUE;
                                }
                                if($post_id!=''){
                                    $q->query("select checked
							from editions
							where user_id='".$user_id."' and post_id = '".$post_id."'");

                                    if(mysql_num_rows($q->re)==1){
                                        $r = mysql_fetch_assoc($q->re);

                                            if($r['checked']==1){
                                                return TRUE;
                                            }
                                            
                                    }
                                    else if(mysql_num_rows($q->re)>1)
                                    {
                                        while($r = mysql_fetch_assoc($q->re)){
                                            if($r['checked']==1){
                                                return TRUE;
                                            }
                                        }
                                    }
                                }
                                return FALSE;
                        }
                        return TRUE;
                }
	}
	/*-----------------------------------------------------------
	Assign values of an array into user's properties
	- $arr: the input array
	-----------------------------------------------------------*/
	function query_username($username='',$email='') {
		$q = new db();
                $row = array();
                if($email==''){
                    $q->query(" SELECT *
                                            FROM users
                                            WHERE username='$username'");
                }
                else if($username==''){
                    $q->query(" SELECT *
                                            FROM users
                                            WHERE email='$email'");
                }
		if($q->re){
                    if($q->n){
			while($r = mysql_fetch_assoc($q->re))
				$row = $r;
			return @$row;
                    }
		}
		else
			return 0;
	}
	function query_id($id) {
		$q = new db();
		$q->query(" SELECT *
					FROM users
					WHERE id=$id");
		if($q->n>0){			
			while($r = mysql_fetch_assoc($q->re))
				$row = $r;
		}
		else
			return 0;
		return @$row;
	}
        // Get User's avatar
	function getUserAvatar($row){
            $q = new db();
                    $fbId = 0;
                    if($row["user_id"]!=0){
                            $x = $this->query_id($row["user_id"]);
                            if (isset($x["fbId"])) $fbId = (int) $x["fbId"];
                            $email = $x["email"];
                            $name = $x["username"];
                    }
                    else {
                            $email = $row["email"];
                            $name = $row["username"];
                    }
                    if ($fbId != 0&& $fbId != NULL&&$fbId != '') {
//                            try {
//                                    $fbUserInfo = facebook_client()->api_client->users_getInfo($fbId, "pic_square");
//                                    $avatarURL = $fbUserInfo[0]["pic_square"];
//                                    $q->query("UPDATE users SET avatar='{$avatarURL}' WHERE id={$row["user_id"]}");
//                            }
//                            catch (Exception $e) {
                                    $avatarURL = $x["avatar"];
//                            }
                    }
                    else {
                            $pAvatar = new TalkPHP_Gravatar();
                            $pAvatar->setEmail($email)->setSize(80)->setRatingAsPG();
                            $avatarURL = $pAvatar->getAvatar();
                    }
                return @$avatarURL;
        }
	function query_level($level){
		$q = new db();
		$q->query(" SELECT *
					FROM users
					WHERE level=$level");
		if($q->n>0){			
			while($r = mysql_fetch_assoc($q->re))
				$row[] = $r;
		}
		else
			return 0;
		return @$row;		
	}
        function getname($v){
                if(($v['user_id']!='' || $v['user_id']!=NULL) && $v['user_id']!=0){
                    $r_u = $this->query_id($v['user_id']);
                    $str = "<font color='#DB1C00' style='font-weight:bold;'>".$r_u['username']."</font>";
                }
                else
                {
                        $str = "<font color='#DB1C00' style='font-weight:bold;' >$v[username]</font>";
                }
                return @$str;
        }
        public static function updateTimeLogin($id){
		$q = new db();
		$q->query("UPDATE users SET lastLogin=".time()." WHERE id=".$id);
        }
}
$user_info = new User;
?>