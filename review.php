<?php
include('core/common.php');
include('core/init.php');
include('core/classes.php');
include('core/session.php');
include('core/filters.php');
include("preprocess.php");
include('header.php'); 
include('destination.php');
include("ajaxLoad.php");
$q = new Db;	
$user_info = new User;
?>
    <td class="center">			
		<div id="menuWrapper">
			<?php
			$post_id = PostElement::filterId($_GET["id"]);
			echo getMainMenu(1, $post_id);
			?>
			<div id="toolbar"></div>
		</div>		
		<div class="button" style="margin: 20px 20px;" onClick="reviewDialog.dialog('open')"><a>+ Add a new review</a></div>
		<div id="reviewList"><?php echo getReviewListHTML($post_id); ?></div>
	</td>
</tr>
</tbody>
</table>

<script type="text/javascript" src="./js/ratingsys.js"></script>
<style type="text/css">
	#rateStatus{float:left; clear:both; width:100%; height:30px; font-size: 15px; font-family: Arial, Helvetica, serif; margin-top: 5px;}
	#rateMe{float:left; clear:both; width:100%; height:auto; padding:0px; margin-bottom: 10px; margion-top: 10px; _margin-bottom:0px;}
	#rateMe li{float:left;list-style:none;}
	#rateMe li a:hover,
	#rateMe .on{background:url(/images/star_on.png) no-repeat;width:50px; height:50px;}
	#rateMe div{float:left;background:url(/images/star_off.png) no-repeat;width:50px; height:50px;}
	#rateMe .off{float:left;background:url(/images/star_off.png) no-repeat;width:50px; height:50px;}
	*html #rateMe .off{  /*Fix cho IE6*/       
        background-image:none;
        FILTER: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/images/star_off.png', sizingMethod='crop');
    } 
	*html #rateMe .none{  /*Fix cho IE6*/       
        background-image:none;
        FILTER: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/images/star_off.png', sizingMethod='crop');
    } 
	*html #rateMe .on{  /*Fix cho IE6*/       
        background-image:none;
        FILTER: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/images/star_on.png', sizingMethod='crop');
    } 
	#reviewForm{
		_margin:0;
		_padding:0;
	}
</style>
<div id="reviewDialog" title="Add a review">

	<!-- Rating - source code reference @ http://reignwaterdesigns.com/ad/tidbits/rateme/ -->
	<span id="rateStatus">Please rate...</span>
	
	<div id="rateMe" title="Please rate this topic">
		<div onclick="rateIt(this)" id="_1" class="none" title="This is very bad, never try it !!!" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id="_2" class="none" title="This is bad, I don't recommend it" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id="_3" class="none" title="This is ok, no thing special" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id="_4" class="none" title="This is good, I recommend it" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
		<div onclick="rateIt(this)" id="_5" class="none" title="This is very good, highly recommend !!!" onmouseover="rating(this)" onmouseout="off(this)">&nbsp;</div>
	</div>
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
	reviewDialog = jQuery("#reviewDialog").dialog({
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
					submitReview();
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

function submitReview() {
	jQuery.post("submitReview.php",
				{postId: <?php echo $post_id?>, rateValue: getRateValue(), reviewText: jQuery("#reviewText").val()},
				function(response) {
					jQuery("#reviewList").html(response);
				},
				"html");			
}

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
					loadToolbar("toolbar");
				});
}

function submitLogin() {	
	var loginForm = $("loginForm");
	loginForm.set("send", {	url: "requests/postLogin.php", evalScripts: true});
	loginForm.send();
	loginForm.get("send").addEvent("onComplete", function(response){
		loadToolbar("toolbar");
	});
}
</script>

<?php 
include("forms/loginForm.php");
include("forms/composeForm.php");
include("footer.php");
?>
