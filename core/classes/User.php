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
	
	
}
$user_info = new User;
?>