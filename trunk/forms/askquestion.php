<div id="questionDialog" title="Question">
<form id="questionForm">
	<div id='field_not_login_question'>
	<?php if(!logged_in()) {?>
		<p>Email: (required)<br />
		<input class="field" name="fill_email_question" id="fill_email_question" type="text" style="width:250px" value=""/><br />
		<span style="font-size: 9px; color: #777;">We loathe spamming. We will never spam you! We use your email to display your <a href="http://en.gravatar.com/">Gravatar</a>.</span></p>

		<p>Name: (required)<br />
		<input class="field" name="fill_name_question" id="fill_name_question" type="text" style="width:250px" value=""/><br /></p>

		<input class="field" name="check_login_question" id="check_login_question" type="hidden" value="1"/>
	<?php }else{?>
		<input class="field" name="check_login_question" id="check_login_question" type="hidden" value="2"/>
	<?php }?>
	</div>
	<br />
	<input type="text" id="postId" name="postId" value="<?php
	if(isset($draf)) echo '0';
	elseif(isset($post["id"])) echo $post["id"];?>" style="visibility:hidden; display: none"/>
	 <?php if(isset($draf)){ ?>
<input type="text" id="editionId" name="editionId" value="<?php echo $draf?>" style="visibility:hidden; display: none"/>
	<?php } ?>
	<textarea 	id="questionText"
				name="questionText"
				rows="3"
				cols="120"></textarea>
	<br/>
</form>
</div>
<div id="Emailquestion1" title="Alert">You must fill Name or Email</div>
<script language="javascript">
function submitquestion(){
	var myHTMLRequest = new Request.HTML({url: "requests/updatequestionList.php", evalResponse: true}).post($("questionForm"));
	myHTMLRequest.addEvent("onSuccess",function(responseTree, responseElements, responseHTML, responseJavaScript){
		$("questionList").set("html", responseHTML);
		var newScript = document.createElement('script');
		newScript.language = 'javascript';
		newScript.text = responseJavaScript;
		$("questionList").appendChild(newScript);
	});
}

jQuery(document).ready(function(){
	Email_question = jQuery("#Emailquestion1").dialog({autoOpen: false});
	questionDialog = jQuery("#questionDialog").dialog({
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
				if(jQuery("#check_login_question").val() == 1){
					if(jQuery("#fill_name_question").val() == '' || jQuery("#fill_email_question").val() == ''|| checkEmail(jQuery("#fill_email_question").val())==false){
						jQuery('#Emailquestion1').css('visibility','visible').dialog("open");
					}
					else{
						submitquestion();
						jQuery(this).dialog('close');
					}
				}
				else{
						submitquestion();
						jQuery(this).dialog('close');
				}
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});
    function checkEmail(test){
        reg = /^[a-zA-Z0-9._]+\@[a-zA-Z0-9]{2,}\.[a-zA-Z]{2,}$/;
        return reg.test(test);
    }
</script>