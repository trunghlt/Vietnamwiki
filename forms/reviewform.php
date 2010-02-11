<div id="review_Dialog" title="Add a review">

	<!-- Rating - source code reference @ http://reignwaterdesigns.com/ad/tidbits/rateme/ -->
	<div id="review1">><!-- --></div>
	<!------------------------------------------------------------------------------------->
	<form id="reviewForm">
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
					submitReview('reviewText','','');
					resetRating();
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