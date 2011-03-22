<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/CommentElement.php");
include("../core/classes/User.php");
include("../core/classes/PostElement.php");
include("../core/classes/Edition.php");
include("../core/classes/Email.php");
include("../core/classes/Filter.php");
include("../libraries/sendmail.php");

//if(isset($_POST["commentText"])){
		
		$commentElement = new CommentElement; 
		$commentElement->commentText = $_POST["commentText"];
		$commentElement->postId = $_POST["postId"];
		$commentElement->userId = myUser_id(myip());
		$commentElement->posterIp = myip();
		$commentElement->commentTime = time();
		$post_id = $commentElement->postId;
		$name = '';
		$tittle = '';
		

		if(isset($_POST["editionId"])){
			$commentElement->editionid = $_POST["editionId"];
			$draf = $commentElement->editionid;
			$post_id = $commentElement->editionid;
			$edition_comment = new Edition;
			$edition_comment->query($commentElement->editionid);
			$tittle = $edition_comment->postTitle;
		}
		
		if(isset($_POST["fill_email_comment"])){
			$commentElement->email = $_POST["fill_email_comment"];
			$name = 'Guest';
		}

		if(isset($_POST["fill_name_comment"])){
			$commentElement->name = $_POST["fill_name_comment"];
			$name = $commentElement->name;
		}

		if($commentElement->userId != 0)
		{
			$user_comment = new User;
			$arr_comment = $user_comment->query_id($commentElement->userId);
			$name = $arr_comment['username'];
			
		}

		if($commentElement->postId != 0)
		{
			$post_comment = new PostElement;
			$post_comment->query($commentElement->postId);
			$tittle = $post_comment->title;
			
			$row2comment = Email::query(4);
			$message = str_replace('{content}',$commentElement->commentText,$row2comment['message']);
			$message = str_replace('{time}',date("d/m/Y   H:i a",$commentElement->commentTime),$message);
			$message = str_replace('{username}',$name,$message);
			$message = str_replace('{tittle}',$tittle,$message);
			
			$rcomment = Email::query_post($commentElement->postId);
			
			foreach($rcomment as $row)
			{
					sendmail($row['email'],$row2comment['subject'],$message,0,$row2comment['from']);	
			}		
		}
				
		$commentElement->add();


		
//}
/*else if(isset($_POST["commentText2"])){
		$commentElement = new CommentElement; 
		$commentElement->commentText = $_POST["commentText2"];
		$commentElement->postId = $_POST["postId2"];
		$commentElement->userId = myUser_id(myip());
		$commentElement->posterIp = myip();
		$commentElement->commentTime = time();
		$post_id = $commentElement->postId;
		if(isset($_POST["editionId2"])){
			$commentElement->editionid = $_POST["editionId2"];
			$draf = $commentElement->editionid;
			$post_id = $commentElement->editionid;
		}
		if(isset($_POST["name_guess"])){
			$commentElement->name = $_POST["name_guess"];
		}
		if(isset($_POST["email_guess"])){
			$commentElement->email = $_POST["email_guess"];
		}
		$commentElement->add();
}*/
include("../commentListPainter.php");
?>