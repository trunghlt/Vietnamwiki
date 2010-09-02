<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/User.php");
include("../core/classes/Email.php");
include("../core/classes/Filter.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/Answers.php");
include("../core/classes/Questions.php");
include("../libraries/sendmail.php");

		$Answers = new Answers;
		$Answers->question_id = $_POST["questionId"];
                if($_POST['check_login_answer']==1){
                    $Answers->user_id = '';
                    $Answers->$username = $_POST["fill_name_answer"];
                    $Answers->$email = $_POST["fill_email_answer"];
                }
                else if($_POST['check_login_answer']==2){
                    $Answers->user_id = myUser_id(myip());
                    $Answers->$username = '';
                    $Answers->$email = '';
                }
		$Answers->ip = myip();
		$Answers->date = time();
                $Answers->content = $_POST['answerText'];
                $Answers->add();
		if($Answers->id != 0)
		{
			$post_comment = new PostElement;
			$post_comment->query($Answers->postId);
			$tittle = $post_comment->title;

			$row2comment = Email::query(4);
			$message = str_replace('{content}',$Answers->commentText,$row2comment['message']);
			$message = str_replace('{time}',date("d/m/Y   H:i a",$Answers->commentTime),$message);
			$message = str_replace('{username}',$name,$message);
			$message = str_replace('{tittle}',$tittle,$message);

			$rcomment = Email::query_post($Answers->postId);

			foreach($rcomment as $row)
			{
					sendmail($row['email'],$row2comment['subject'],$message,0,$row2comment['from']);
			}
		}
?>