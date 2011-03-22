<div id="reviewDialog1" title="Add a review">

	<!-- Rating - source code reference @ http://reignwaterdesigns.com/ad/tidbits/rateme/ -->
	<div id="review2"><!-- --></div>
	<!------------------------------------------------------------------------------------->
	<form id="reviewForm1">
		<textarea 	id="reviewText1" 
					onKeyDown="updateReviewText1(this)"
					onKeyUp="updateReviewText1(this)"
					rows="10" 
					cols="80"></textarea>
			<input type="hidden" name="name_guess" id='name_guess' value='' />
			<input type="hidden" name="email_guess" id='email_guess' value='' />		
	</form>
	<label>Your review can't excess 5000 characters.</label>
	<label id="reviewLimitLbl1"></label>
	
</div>

<div id="mustRateAlert1" title="Alert">Sorry, you have to rate to finish reviewing !</div>

<div id="reviewLowerBound1" title="Alert">Sorry, your review has to be more than 140 characters. 
Click on <a href="<?php echo getPostPermaLink($post_id)?>">comment</a> if you want to comment less than 140 characters.</div>
<div id='filldialog' title="Fill">
You must have fill Email or your name
</div> 
<script language="javascript">
function updateReviewText1(reviewText) {
	if (jQuery(reviewText).val().length > 5000)
		jQuery(reviewText).val(jQuery(reviewText).val().substring(0, 5000));			
	jQuery('#reviewLimitLbl1').html('You have ' + (5000 - jQuery(reviewText).val().length) + ' characters left.');
}

jQuery(document).ready(function(){ 
	loadToolbar("toolbar");
	fill =  jQuery("#filldialog").dialog({
		autoOpen: false,
		buttons: {
		 'Fill Name': function() {
				FillNameComment.dialog('open');
		 },
		 'Fill Email': function() {
			FillEmailComment.dialog('open');
		 },		 
		 Cancel: function() {
				document.getElementById('name_guess').value='';
				document.getElementById('email_guess').value='';
			jQuery(this).dialog('close');
		 }
		}
	});
	review_LowerBound = jQuery("#reviewLowerBound1").dialog({autoOpen: false});
	must_RateAlert = jQuery("#mustRateAlert1").dialog({autoOpen: false});
	review_Dialog1 = jQuery("#reviewDialog1").dialog({
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
					must_RateAlert.dialog("open");
				}
				else if (jQuery("#reviewText1").val().length <= 140) {
					review_LowerBound.dialog("open");
				}
				else {
					if(jQuery("#name_guess").val()=='' && jQuery("#email_guess").val()=='')
						fill.dialog('open');
					else{	
						submitReview('reviewText1','email_guess','name_guess');
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