<div id="answerDialog" title="answer">
<form id="answerForm">
	<div id='field_not_login_answer'>
	<?php if(!logged_in()) { ?>
		<p>Email: (required)<br />
		<input class="field" name="fill_email_answer" id="fill_email_answer" type="text" style="width:250px" value=""/><br />
		<span style="font-size: 9px; color: #777;">We loathe spamming. We will never spam you! We use your email to display your <a href="http://en.gravatar.com/">Gravatar</a>.</span></p>

		<p>Name: (required)<br />
		<input class="field" name="fill_name_answer" id="fill_name_answer" type="text" style="width:250px" value=""/><br /></p>

		<input class="field" name="check_login_answer" id="check_login_answer" type="hidden" value="1"/>
	<?php }else{?>
		<input class="field" name="check_login_answer" id="check_login_answer" type="hidden" value="2"/>
	<?php }?>
	</div>
	<br />
        <input type="hidden" id="questionId" name="questionId" />
	<textarea 	id="answerText"
				name="answerText"
				rows="3"
				cols="120"></textarea>
	<br/>
</form>
</div>
<div id="Emailanswer1" title="Alert">You must fill Name or Email</div>
<script language="javascript">
jQuery(document).ready(function(){
	Email_answer = jQuery("#Emailanswer1").dialog({autoOpen: false});
	answerDialog = jQuery("#answerDialog").dialog({
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
				if(jQuery("#check_login_answer").val() == 1){
					if(jQuery("#fill_name_answer").val() == '' || jQuery("#fill_email_answer").val() == ''|| checkEmail(jQuery("#fill_email_answer").val())==false){
						jQuery('#Emailanswer1').css('visibility','visible').dialog("open");
					}
					else{
						submitanswer();
						jQuery(this).dialog('close');
					}
				}
				else{
						submitanswer();
						jQuery(this).dialog('close');
				}
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});
    function submitanswer(){
    jQuery.post('requests/submitanswer.php',jQuery('#answerForm').serialize(),function(reponse){ load_qanda(0);});
    }
    function checkEmail(test){
        reg = /^[a-zA-Z0-9._]+\@[a-zA-Z0-9]{2,}\.[a-zA-Z]{2,}$/;
        return reg.test(test);
    }
</script>