<?php
function sendmail($to,$subject,$message,$type,$from){

		$to1 = Filter::filterEmail($to);
		$to1 = "$to1";	
					
		if($to1 != $to)
			return 1;
		$i = strpos($to1,'@');
		$k = strpos($to1,'.',$i) - ($i+1);
		$str = substr($to1,$i+1,$k);			
		
		$subject = Filter::filterInputText($subject);
		$message = Filter::filterInputText($message);
		$message = "\n $message";
		$from = Filter::filterEmail($from);
		
		$headers = "MIME-Version: 1.0\n";
	
	if($type == 1){
 		$headers .= "Content-type: text/html; charset=utf-8\n";
	}
	else if($type==0){
		$headers .= "Content-type: text/plain; charset=utf-8\n";
		$message = strip_tags($message);
		$message = "\n $message";
	}
	if(strtolower($str) == 'yahoo'){
		$headers .= "Content-Transfer-Encoding: 8bit\n";
		$headers .= "From: VietnamWiKi<$from> \n";
		$headers .= "X-Priority: 1\n";
		$headers .= "X-MSMail-Priority: High\n";
		$headers .= "X-Mailer: PHP/" . phpversion()."\n";
	}
	else{
		$headers .= "From: VietnamWiKi<$from> \n";
		$headers .= "X-Mailer: PHP/" . phpversion()."\n";
	}	
	return mail($to1, $subject, $message, $headers);
}
?> 
