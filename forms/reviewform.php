<div id="review_Dialog" title="Add a review">

	<!--<div id="review1">-->
		<span id='rateStatus'>Please rate...</span><br/>
		<div id='rateMe' title='Please rate this topic'>
		<div onclick="rateIt(this)" id='_1' class='none' title="This is very bad, never try it !!!" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id='_2' class='none' title="This is bad, I don't recommend it" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id='_3' class='none' title="This is ok, no thing special" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id='_4' class='none' title='This is good, I recommend it' onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id='_5' class='none' title="This is very good, highly recommend !!!" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		</div>
	<!--</div>-->

	<form id="reviewForm">
	<div id='field_not_login'>
	<?php if(!logged_in()) {?>
		<p>Email: (required)<br />
		<input class="field" name="fill_email_review" id="fill_email_review" type="text" style="width:250px" value=""/><br />
		<span style="font-size: 9px; color: #777;">We loathe spamming. We will never spam you! We use your email to display your <a href="http://en.gravatar.com/">Gravatar</a>.</span></p>
		
		<p>Name: (required)<br />
		<input class="field" name="fill_name_review" id="fill_name_review" type="text" style="width:250px" value=""/><br /></p>
		
		<input class="field" name="check_login" id="check_login" type="hidden" value="1"/>
	<?php }else{?>
		<input class="field" name="check_login" id="check_login" type="hidden" value="2"/>
	<?php }?>
	</div>

	<textarea 	id="reviewText" 
				onKeyDown="updateReviewText(this)"
				onKeyUp="updateReviewText(this)"
				rows="10" 
				cols="80"></textarea>
	</form>
	<label>Your review can't excess 5000 characters.</label>
	<label id="reviewLimitLbl"></label>
	
</div>

<div id="mustRateAlert" title="Alert">Sorry, you have to rate to finish reviewing !</div>
<div id="mustNameEmailAlert" title="Alert">You must fill Name or Email</div>

<div id="reviewLowerBound" title="Alert">Sorry, your review has to be more than 140 characters. 
Click on <a href="<?php echo getPostPermaLink($post_id)?>">comment</a> if you want to comment less than 140 characters.</div>

<script language="javascript">
function updateReviewText(reviewText) {
	if (jQuery(reviewText).val().length > 5000)
		jQuery(reviewText).val(jQuery(reviewText).val().substring(0, 5000));			
	jQuery('#reviewLimitLbl').html('You have ' + (5000 - jQuery(reviewText).val().length) + ' characters left.');
}

jQuery(document).ready(function(){ 
	loadToolbar("toolbar");
	reviewLowerBound = jQuery("#reviewLowerBound").dialog({autoOpen: false});
	mustRateAlert = jQuery("#mustRateAlert").dialog({autoOpen: false});
	mustNameEmailAlert = jQuery("#mustNameEmailAlert").dialog({autoOpen: false});
	reviewDialog = jQuery("#review_Dialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: '450',
		modal: true,
		resizable:false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Submit': function() {
				if (getRateValue() == 0) {
					mustRateAlert.dialog("open");
				}
				else if (jQuery("#reviewText").val().length <= 140) {
					reviewLowerBound.dialog("open");
				}
				else {
					
					if(jQuery("#check_login").val() == 1){
						
						if(jQuery("#fill_name_review").val() == '' && jQuery("#fill_email_review").val() == '')
							mustNameEmailAlert.dialog("open");
						else{
							submitReview('reviewText','fill_email_review','fill_name_review');
							resetRating();
							jQuery(this).dialog('close');
						}							
					}
					else{
						submitReview('reviewText','','');
						resetRating();
						jQuery(this).dialog('close');
					}
				}
			},
		Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});
</script>