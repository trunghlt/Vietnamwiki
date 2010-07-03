<div id="commentDialog" title="Comment">
<form id="commentForm">
	<div id='field_not_login_comment'>
	<?php if(!logged_in()) {?>
		<p>Email: (required)<br />
		<input class="field" name="fill_email_comment" id="fill_email_comment" type="text" style="width:250px" value=""/><br />
		<span style="font-size: 9px; color: #777;">We loathe spamming. We will never spam you! We use your email to display your <a href="http://en.gravatar.com/">Gravatar</a>.</span></p>
		
		<p>Name: (required)<br />
		<input class="field" name="fill_name_comment" id="fill_name_comment" type="text" style="width:250px" value=""/><br /></p>
		
		<input class="field" name="check_login_comment" id="check_login_comment" type="hidden" value="1"/>
	<?php }else{?>
		<input class="field" name="check_login_comment" id="check_login_comment" type="hidden" value="2"/>
	<?php }?>
	</div>
	<br />
	<input type="text" id="postId" name="postId" value="<?php 
	if(isset($draf)) echo '0';
	elseif(isset($currentPostElement->id)) echo $currentPostElement->id;?>" style="visibility:hidden; display: none"/>
	 <?php if(isset($draf)){ ?>
<input type="text" id="editionId" name="editionId" value="<?php echo $draf?>" style="visibility:hidden; display: none"/> 
	<?php } ?>
	<textarea 	id="commentText" 
				name="commentText" 
				onKeyDown="updateCommentText(this)" 
				onKeyUp = "updateCommentText(this)"
				rows="3" 
				cols="120"></textarea>
	<br/>
<?php if(isset($currentPostElement->id)){?> 	
	<label>Your comment can't excess 140 characters.</label>
	<label id="commentLimitLbl"></label>
	<label>Please click on <a href="review.php?id=<?php echo $post_id?>">Review</a> for any longer comments.</label>
<?php }?>
</form>
</div> 
<div id="EmailComment1" title="Alert">You must fill Name or Email</div>
<script language="javascript">
function updateCommentText(reviewText) {
	if (reviewText.value.length > 140)
		reviewText.value = reviewText.value.substring(0, 140);			
	jQuery('#commentLimitLbl').html('You have ' + (140 - reviewText.value.length) + ' characters left.');
}

function submitComment(){
	var myHTMLRequest = new Request.HTML({url: "requests/updateCommentList.php", evalResponse: true}).post($("commentForm"));
	myHTMLRequest.addEvent("onSuccess",function(responseTree, responseElements, responseHTML, responseJavaScript){
		$("commentList").set("html", responseHTML);
		var newScript = document.createElement('script');
		newScript.language = 'javascript';
		newScript.text = responseJavaScript;
		$("commentList").appendChild(newScript);
	});	
}

jQuery(document).ready(function(){
	Email_Comment = jQuery("#EmailComment1").dialog({autoOpen: false});
	commentDialog = jQuery("#commentDialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: '300',
		modal: true,
		resizable:false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},		
		buttons: {
			'Submit': function() {
				if(jQuery("#check_login_comment").val() == 1){
					if(jQuery("#fill_name_comment").val() == '' && jQuery("#fill_email_comment").val() == ''){
						jQuery('#EmailComment1').css('visibility','visible');	
						Email_Comment.dialog("open");
					}
					else{
						submitComment();
						jQuery(this).dialog('close');
					}			
				}
				else{
						submitComment();
						jQuery(this).dialog('close');					
				}
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}		
	});
});
</script>
