<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/User.php");
include("../core/classes/Email.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/Answers.php");
include("../core/classes/Questions.php");
include('../core/classes/PostElement.php');
include('../core/classes/IndexElement.php');
include('../core/classes/DestinationElement.php');
include('../core/classes/Filter.php');
include("../libraries/sendmail.php");

		$Answers = new Answers;
                $user = new User;
                
		$Answers->question_id = $_POST["questionId"];
                if($_POST['check_login_answer']==1){
                    $Answers->user_id = 0;
                    $Answers->username = $_POST["fill_name_answer"];
                    $Answers->email = $_POST["fill_email_answer"];
                    $name_answer = $Answers->username;
                }
                else if($_POST['check_login_answer']==2){
                    $Answers->user_id = myUser_id(myip());
                    $Answers->username = '';
                    $Answers->email = '';
                    $r = $user->query_id($Answers->user_id);
                    $name_answer = $r['username'];
                }
		$Answers->ip = myip();
		$Answers->date = time();
                $Answers->content = htmlspecialchars(filter_content_script($_POST['answerText']));
                $Answers->add();
                $q = new Questions;
		if($Answers->id != 0)
		{
                        $r2 = $q->query("id=".$Answers->question_id);
                        if($r2[0]['user_id']!='' || $r2[0]['user_id']!=NULL){
                            $r_name = $user->query_id($r2[0]['user_id']);
                            $name_question = $r_name['username'];
                            $email = $r_name['email'];
                        }
                        else{
                            $name_question = $r2[0]['username'];
                            $email = $r2[0]['email'];
                        }

			$row2answer = Email::query(5);
			$message = str_replace('{username}',$name_question,$row2answer['message']);
			$message = str_replace('{Username of answer poster}',$name_answer,$message);
			$message = str_replace('{Question content}',htmlspecialchars_decode($Answers->content),$message);
                        $str = 'http://www.vietnamwiki.net'.getPostPermalink(32);
                        $message = str_replace('{Link to Vietnam General page}',"<a href='$str' > Click here</a>",$message);
                       
                        if($email!='' || $email!=NULL){
                            sendmail($email,$row2answer['subject'],$message,0,$row2answer['from']);
                        }
		}
?>