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
//Add span rate in review1 or review
/*$str_rate="<span id='rateStatus'>Please rate...</span><br/><div id='rateMe' title='Please rate this topic'><div onclick='rateIt(this)' id='_1' class='none' title='This is very bad, never try it !!!' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_2' class='none' title='This is bad, I don't recommend it' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_3' class='none' title='This is ok, no thing special' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_4' class='none' title='This is good, I recommend it' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div><div onclick='rateIt(this)' id='_5' class='none' title='This is very good, highly recommend !!!' onmouseover='rating(this)' onmouseout='off(this)'>&nbsp;</div></div>";*/
$pAvatar = new TalkPHP_Gravatar();
?>
    <td class="center" style="width:820px;">
		<div id="menuWrapper">
			<?php
			$post_id = PostElement::filterId($_GET["id"]);
			echo getMainMenu(1, $post_id);
			?>
			<div id="toolbar"></div>
		</div>
		<div id='button'>
		<?php //if(logged_in()){?>
		<div class="button" style="margin: 20px 20px;" onClick="jQuery('#review_Dialog').css('visibility','visible');reviewDialog.dialog('open')"><a>+ Add a new review</a></div>
		<?php //}else{?>
<!--<div class="button" style="margin: 20px 20px;" onClick="review_Dialog1.dialog('open')"><a>+ Add a new review</a></div>	-->	
		<?php //}?>
		</div>
		<div id="reviewList"><?php 
			$review = $memcache->get("review_".$post_id);
			if($review == NULL){
				$review = getReviewListHTML($post_id);
				$memcache->set("review_".$post_id,$review);
			}
			echo $review ; 
		?></div>
	</td>
</tr>
<tr>
<td colspan=3>
	<?php include("footLinks.php");?>
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
include("forms/register_email.php");
include("footer.php");
?>
<script type="text/javascript">

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

				document.getElementById('reviewText').value="";
				document.getElementById('field_not_login').innerHTML="Email :<br /><input class='field' name='fill_email_review' id='fill_email_review' type='text' style='width:250px' value=''/><br />Name :<br /><input class='field' name='fill_name_review' id='fill_name_review' type='text' style='width:250px' value=''/><br /><input class='field' name='check_login' id='check_login' type='hidden' value='1'/>";
				});
}

function submitLogin() {
        jQuery.post("requests/postLogin.php",jQuery('#loginForm').serialize(),function(response){
 		loadToolbar("toolbar");
                if(response==-2)
                        alert("This user has been banned");
                else if(response == 'false'){
                    alert("Login's fail");
                }
                else{
                        if(response != '' && response != 'success'){
                                jQuery('#FillEmailDialog').css('visibility','visible').dialog('open');
                                document.getElementById('reviewText').value="";
                                jQuery('#field_not_login').html("<input class='field' name='check_login' id='check_login' type='hidden' value='2'/>");
                        }
                        else if(response == 'success'){
                                document.getElementById('reviewText').value="";
                                jQuery('#field_not_login').html("<input class='field' name='check_login' id='check_login' type='hidden' value='2'/>");
                        }
                }
        });
	/*var loginForm = $("loginForm");
	loginForm.set("send", {	url: "requests/postLogin.php", evalScripts: true});
	loginForm.send();
	loginForm.get("send").addEvent("onComplete", function(response){
		loadToolbar("toolbar");
				if(response==-2)
					alert("This user has been banned");
				else{
					if(response != '' && response != 'success'){
						jQuery('#FillEmailDialog').css('visibility','visible');
						Fill_EmailDialog.dialog('open');
						document.getElementById('reviewText').value="";
						jQuery('#field_not_login').html("<input class='field' name='check_login' id='check_login' type='hidden' value='2'/>");						
					}
					else{
						document.getElementById('reviewText').value="";
						jQuery('#field_not_login').html("<input class='field' name='check_login' id='check_login' type='hidden' value='2'/>");						
					}
				}
					

	});*/
}
</script>

