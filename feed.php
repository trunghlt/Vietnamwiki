<?php
include('core/common.php');
include('core/init.php');
include('core/classes.php');

$u = new User;
$name = mysql_real_escape_string(htmlspecialchars(filter_content_script($_GET["username"])));
if(!logged_in())
    header("location:index.php");
$user_filtered = $u->query_username($name);

if(!is_array($user_filtered))
    if($user_filtered==0)
    header("location:index.php");

if(myUsername(myip())!==$name)
    header("location:index.php");

include('core/session.php');
include('core/filters.php');
include('preprocess.php');
include('header.php');
include('destination.php');

?>
<style>
.phantrang ul{
    margin:0px;
    padding: 0px;
    list-style-type: none;
}
.phantrang ul li{
    display: inline;
}
.phantrang ul li a{
    color: #CC0000;
    width: 50px;
}
</style>
    <td class="center" style="width:820px;">
		<div id="menuWrapper"><div id="toolbar"><?php getToolbarHTML();?></div></div>
		<div id="contentTable">
                    <div>
                        <h1>New Articles</h1>
                        <div id="new_articles"></div>
                    </div>
                    <div>
                        <h1>New Comments</h1>
                        <div id="new_comments"></div>
                    </div>
                    <div>
                        <h1>New Reviews</h1>
                        <div id="new_reviews"></div>
                    </div>
                    <div>
                        <h1>New Uploaded Images</h1>
                        <div id="new_uploaded_images"></div>
                    </div>
		</div>
	</td>
</tr>
<tr>
	<td colspan=3>
		<?php include("footLinks.php");?>
	</td>
</tr>
</tbody>
</table>
<script type="text/javascript">
 jQuery(document).ready(function(){
     load_new_feed();
});
    function signOut(){
	jQuery.post("/requests/logout.php", {},
				function(response) {
                                    window.location="index.php";
				});
    }
    function load_new_feed(){
        jQuery.post("/requests/load_new_feed.php",{feed_type:1,time:<?=$user_filtered["lastLogin"]?>},function(response){jQuery("#new_articles").html(response);});
        jQuery.post("/requests/load_new_feed.php",{feed_type:2,time:<?=$user_filtered["lastLogin"]?>},function(response){jQuery("#new_comments").html(response);});
        jQuery.post("/requests/load_new_feed.php",{feed_type:3,time:<?=$user_filtered["lastLogin"]?>},function(response){jQuery("#new_reviews").html(response);});
        jQuery.post("/requests/load_new_feed.php",{feed_type:4,time:<?=$user_filtered["lastLogin"]?>},function(response){jQuery("#new_uploaded_images").html(response);});
    }
    function loadPage(_s,_f_t,_t,_p){
        jQuery.post("/requests/load_new_feed.php",{s:_s,feed_type:_f_t,time:_t},function(response){jQuery("#"+_p).html(response);});
    }
</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include('dialog.php');
include("forms/register_email.php");
include("footer.php");
?>