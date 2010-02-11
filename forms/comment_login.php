<div id="commentDialog_login" title="Comment">
<form id="commentForm_login">
	<input type="text" id="postId2" name="postId2" value="<?php 
	if(isset($draf)) echo '0';
	elseif(isset($currentPostElement->id)) echo $currentPostElement->id;?>" style="visibility:hidden; display: none"/>
	 <?php if(isset($draf)){ ?>
<input type="text" id="editionId2" name="editionId2" value="<?php echo $draf?>" style="visibility:hidden; display: none"/> 
	<?php } ?>
	<textarea 	id="commentText2" 
				name="commentText2" 
				onKeyDown="updateCommentText2(this)" 
				onKeyUp = "updateCommentText2(this)"
				rows="3" 
				cols="120"></textarea>
		<input class="field" name="name_guess" id="name_guess" value='' type="hidden" />
		<input class="field" name="email_guess" id="email_guess" value='' type="hidden" />
	<br/>
<?php if(isset($currentPostElement->id)){?> 
	<label>Your comment can't excess 140 characters.</label>
	<label id="commentLimitLbl2"></label>
	<label>Please click on <a href="review.php?id=<?php echo $post_id?>">Review</a> for any longer comments.</label>
<?php }?>
</form>
</div> 
<script language="javascript">
function updateCommentText2(reviewText) {
	if (reviewText.value.length > 140)
		reviewText.value = reviewText.value.substring(0, 140);			
	jQuery('#commentLimitLbl2').html('You have ' + (140 - reviewText.value.length) + ' characters left.');
}

function submitComment_login(){
	var myHTMLRequest = new Request.HTML({url: "requests/updateCommentList.php", evalResponse: true}).post($("commentForm_login"));
	myHTMLRequest.addEvent("onSuccess",function(responseTree, responseElements, responseHTML, responseJavaScript){
		$("commentList").set("html", responseHTML);
		var newScript = document.createElement('script');
		newScript.language = 'javascript';
		newScript.text = responseJavaScript;
		$("commentList").appendChild(newScript);
		document.getElementById('name_guess').value='';
		document.getElementById('email_guess').value='';
	});	
}

jQuery(document).ready(function(){ 
	commentlogin = jQuery("#commentDialog_login").dialog({
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
				submitComment_login();
				jQuery(this).dialog('close');
			},
			Cancel: function() {
					document.getElementById('name_guess').value='';
					document.getElementById('email_guess').value='';
				jQuery(this).dialog('close');
			},
			'Fill Name': function() {
				FillNameComment.dialog('open');
			},
			'Fill Email': function() {
				FillEmailComment.dialog('open');
			}
		}
	});
});
</script>
