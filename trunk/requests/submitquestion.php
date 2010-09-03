<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/Questions.php");


		$Questions = new Questions;

                if($_POST['check_login_question']==1){
                    $Questions->user_id = 0;
                    $Questions->username = filter_content_script($_POST["fill_name_question"]);
                    $Questions->email = filter_content_script($_POST["fill_email_question"]);
                    $name_question = $Questions->username;
                }
                else if($_POST['check_login_question']==2){
                    $Questions->user_id = myUser_id(myip());
                    $Questions->username = '';
                    $Questions->email = '';
                }
                $Questions->topic_id = filter_content_script($_POST["postId"]);
                $Questions->index_id = filter_content_script($_POST["indexId"]);
                $Questions->dest_id = filter_content_script($_POST["destId"]);
		$Questions->ip = myip();
		$Questions->date = time();
                $Questions->content = htmlspecialchars(filter_content_script($_POST['questionText']));
                $Questions->add();
?>