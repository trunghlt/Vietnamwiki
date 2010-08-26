<?php

/*----------------------------------------------------------------------------
Get the main menu HTML text
- $onFocusCat: the onFocus cat order (if applicable, otherwise default = -1)
- $postId: the id of current post (if applicable, otherwise default = -1)
- $reviewId: the id of current view (if applicable, otherwise default = -1)
->main menu HTML text
------------------------------------------------------------------------------*/
function getMainMenu($onFocusCat = -1, $postId = -1, $reviewId = -1) {
	$param1 = ($onFocusCat == -1)? "" : "cat=" . $onFocusCat;
	$param2 = ($postId == -1)? "" : "&id=" . $postId;
	$param3 = ($reviewId == -1)? "" : "&reviewId=" . $reviewId;
	if ($postId != -1) $nReviews = count(Review::getReviewListByPostId($postId));
	?>
	<div id="mainMenu">
		<ul>
			<li <?php if ($onFocusCat == 0) echo "class='selected'"?>><a href="<?php echo getPostPermalink($postId)?>">Main Content</a></li>
			<li <?php if ($onFocusCat == 1) echo "class='selected'"?>>
				<a href="review.php?<?php echo $param2 . $param3?>">Review<?php if (isset($nReviews)&&($nReviews != 0)) echo " ($nReviews)";?></a>
			</li>
		</ul>
	</div>
	<?php
}

/*--------------------------------------------------------------
Get the review list HTML text for the post with $postId
- $postId: id of the post
->review list HTML text
----------------------------------------------------------------*/
function getReviewListHTML($postId) {
	global $pAvatar;
	$reviewList = Review::getReviewListByPostId($postId); 
	foreach ($reviewList as $review) { 
							
		$reviewDateTimeStr = date("d M Y, H:i", $review->reviewDateTime); 
		$reviewText = str_replace("\n", "<p/>", $review->reviewText);
		?>					
		<div class="reviewElement">
			<div class="reviewHead">
				<?php 
				if($review->email!='' && $review->userId==0){
					$pAvatar->setEmail($review->email)->setSize(80)->setRatingAsPG();?>
					<img class="avatar" src="<?php echo $pAvatar->getAvatar()?>" height=50 width=50/>
					<div class="reviewRate" style="background: url(/images/stars_map.png) 0 <?php echo -19*($review->rateValue - 1)*2 - 19 ?>px no-repeat;">&nbsp;</div>
					&nbsp;reviewed by <span style="color: #990000; font-weight: bold;"><?php echo $review->name;?></span> at <?php echo $reviewDateTimeStr;?>
					<?php 					
				}				
				else {
					?>
					<div class="reviewRate" style="background: url(/images/stars_map.png) 0 <?php echo -19*($review->rateValue - 1)*2 - 19 ?>px no-repeat;">&nbsp;</div>
					&nbsp; Reviewed by <span style="color: #990000; font-weight: bold;"><?php   
					if($review->name!='' && $review->email=='')
						echo $review->name;
					elseif($review->name=='' && $review->email==''){
						$user = User::getUserElementById($review->userId);
						echo $user->username;
					}
					?> 
					</span> at <?php echo $reviewDateTimeStr;
				} ?>
				</div>
					
			<div class="reviewBody">
				<?php
				//delete all words with length >= 30
				$processedText = preg_replace("/\b\w{30,}\b/e", "", $reviewText);

				echo $processedText; 
				?>
			</div>
		</div>
	<?php }
}

/*-----------------------------------------------------------------
Get toolbar HTML text
->toolbar HTML
-------------------------------------------------------------------*/
function getToolbarHTML() {
	?>
	<div align="right" style="padding-top: 5px;">
		<?php
        try {
    		$fbUserId = facebook_client()->get_loggedin_user();
		    if ($fbUserId) {
			    $fbUserInfo = facebook_client()->api_client->users_getInfo($fbUserId, "name, proxied_email, first_name, last_name, locale, pic_square");
			    $currentFbUserInfo = $fbUserInfo[0];
			    if ( !User::chkFbUserReged($fbUserId) ) {
				    $user = new User;
				    $user->fbId = $fbUserId;
				    $user->username = $currentFbUserInfo["name"];
				    $user->email = $currentFbUserInfo["proxied_email"];
				    $user->firstName = $currentFbUserInfo["first_name"];
				    $user->lastName = $currentFbUserInfo["last_name"];
				    $user->locationCode = $currentFbUserInfo["locale"];
				    $user->add();
				    login($user->id);
			    }
			    else {
				    $user = new User;
				    $user->queryByFbId($fbUserId);
				    login($user->id);
			    }
		    }
        }
        catch (Exception $e) {
            $fbUserId = False;
        }
		
		$loggedIn = logged_in();
		
		if ($fbUserId || $loggedIn) { 
			$un = myUsername(myip()); ?>
			<strong> 
			<a class="link" onClick="jQuery('#composeDialog').css('visibility','visible').dialog('open')">Compose new entry</a>
			/
			<?php if ($loggedIn) { ?>
				<?php echo $un ?>
			<?php } else { 
				echo $currentFbUserInfo["name"];;
			} ?>
			/<a class='link' href='/profile.php?username=<?php echo $un?>'>profile</a>
			/<?php if ($fbUserId) { ?>
				<a class="link" onclick="FB.Connect.logout(function() { signOut(); refresh_page(); })">log out</a>
			<?php } else { ?>
				<a class='link' onClick="signOut()">log out</a>       
			<?php } ?>
			</strong>
		<?php } 
		else { ?>          
			<b>
			<a class='link' onClick="jQuery('#loginDialog').css('visibility','visible').dialog('open');">log in</a>
			/<a class='link' href="/signup.php" >sign up</a>			 
			</b>
	   <?php } ?>     
	</div>
	<?php
}
?>
