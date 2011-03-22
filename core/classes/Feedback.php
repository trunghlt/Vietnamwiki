<?php
class Feedback {
	public $id;
	public $content;

	public function add() {
		$q = new db();
		$mysql["content"] = mysql_real_escape_string($this->content);
		$sql = "INSERT INTO feedbacks
				(fbContent)
				VALUES ('{$mysql["content"]}')";
		$q->query($sql);
	}
}

?>