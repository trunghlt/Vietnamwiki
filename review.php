<?php
include('./core/common.php');
include('./core/init.php');
include('./core/classes.php');
include('./core/session.php');
include('./core/filters.php');
include("preprocess.php");
include('header.php'); 
include('destination.php');
include("ajaxLoad.php");
include('./libraries/TalkPHP_Gravatar.php');
$q = new Db;	
$user_info = new User;
$str_rate="<span id='rateStatus'>Please rate...</span><br/><div id='rateMe' title='Please rate this topic'><div onclick='rateIt(this)' id='_1' class='none' title='This is very bad, never try it !!!' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_2' class='none' title='This is bad, I don't recommend it' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_3' class='none' title='This is ok, no thing special' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_4' class='none' title='This is good, I recommend it' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_5' class='none' title='This is very good, highly recommend !!!' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div></div>";
$pAvatar = new TalkPHP_Gravatar();
?>
    <td class="center">			
		<div id="menuWrapper">
			<?php
			$post_id = PostElement::filterId($_GET["id"]);
			echo getMainMenu(1, $post_id);
			?>
			<div id="toolbar"></div>
		</div>
		<div id='button'>
		<?php if(logged_in()){?>
		<div class="button" style="margin: 20px 20px;" onClick="reviewDialog.dialog('open')"><a>+ Add a new review</a></div>
		<?php }else{?>
		<div class="button" style="margin: 20px 20px;" onClick="review_Dialog1.dialog('open')"><a>+ Add a new review</a></div>		
		<?php }?>
		</div>
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
<?php 
include("forms/loginForm.php");
include("forms/composeForm.php");
include("forms/reviewform.php");
include("forms/reviewform1.php");
include("forms/fill_comment_email_form.php");
include("forms/fill_comment_name_form.php");
include("footer.php");
?>
<script type="text/javascript">
	<?php if(logged_in()){?>
		document.getElementById('review1').innerHTML="<?php echo "$str_rate"?>";
	<?php }else{?>
		document.getElementById('review2').innerHTML="<?php echo "$str_rate"?>";
	<?php }?>
function submitReview(dom,email,name) {
	if(email=='' && name=='' ){
	jQuery.post("submitReview.php",
				{postId: <?php echo $post_id?>, rateValue: getRateValue(), reviewText: jQuery("#"+dom).val()},
				function(response) {
					jQuery("#reviewList").html(response);
				},
				"html");
	}
	else{
	jQuery.post("submitReview.php",
				{postId: <?php echo $post_id?>, rateValue: getRateValue(), reviewText: jQuery("#"+dom).val(),name_guess:  jQuery("#"+name).val(),email_guess: jQuery("#"+email).val()},
				function(response) {
					jQuery("#reviewList").html(response);
				},
				"html");
	}			
}

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
					loadToolbar("toolbar");
				document.getElementById('button').innerHTML="<div class='button' style='margin: 20px 20px;' onClick=review_Dialog1.dialog('open') ><a>+ Add a new review</a></div>";
				document.getElementById('review1').innerHTML="<div style='height:0;'></div>";
				document.getElementById('review2').innerHTML="<?php echo "$str_rate"?>";
				document.getElementById('reviewText').value="";
				});
}

function submitLogin() {	
	var loginForm = $("loginForm");
	loginForm.set("send", {	url: "requests/postLogin.php", evalScripts: true});
	loginForm.send();
	loginForm.get("send").addEvent("onComplete", function(response){
		loadToolbar("toolbar");
		document.getElementById('button').innerHTML="<div class='button' style='margin: 20px 20px;' onClick=reviewDialog.dialog('open') ><a>+ Add a new review</a></div>";
		document.getElementById('review2').innerHTML="<div style='height:0;'></div>";
		document.getElementById('review1').innerHTML="<?php echo "$str_rate"?>";
		document.getElementById('reviewText').value="";
	});
}
</script>

