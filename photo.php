<?php
include('core/common.php');
include('core/init.php');
include('core/session.php');
include('core/filters.php');
include('core/classes.php');
if(filterNumeric($_GET["dest_id"]) && filterNumeric($_GET["page"]))
    $check_dest_id = $_GET["dest_id"];
include('preprocess.php');
include('header.php'); 
include('destination.php');
include('ajaxLoad.php');
?>
<td class="center" style="width:820px;">
	<div style = "background: #EDEFF4; height: 28px;">
		<div id="menuWrapper">
			<div id="toolbar"></div>
		</div>
	</div>
	<div id="contentTable">
		<?php include("photoBody.php");?>
	</div>
</td>
</tr>
</tbody>
</table>
<script language="javascript">
jQuery(function(){
	loadToolbar("toolbar");
});

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
					loadToolbar("toolbar");
                                        loadNotification();
                                        jQuery(".editpho").html("<div align=\"center\" id=\"editpho\"><!-- --></div>");
                                        jQuery('#type_login').val(1);
                                        jQuery('#editpost').val('login'); 
				});
}
//Set value when user register successfully email
function set_value(){
    jQuery(".editpho").html("<a href=\"#\" class=\"small_link\" onclick=\"imageEditClick(<?php echo $img["id"]?>)\"> [edit] </a>");
    loadNotification();
    loadToolbar("toolbar");
}
//end
function submitLogin(dom,check) {
        jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(),function(response){
                response = jQuery.trim(response);
                if(parseInt(response)==-2)
                {
                    jQuery("#dialog_notification").html("This user has been banned");
                    dialog_notification.dialog('open');
                }
                else if(response == 'false'){
                    jQuery("#dialog_notification").html("Login's fail");
                    dialog_notification.dialog('open');
                }
                else{
                        if(response != '' && response != 'success'){
                                document.getElementById('id_user').value = response;

                                var str = jQuery("#"+dom).serialize().split("&");
                                var name = str[0].split("=");
                                jQuery("#name_user").val(name[1]);

                                if(check==2){
                                        document.getElementById('editpost').value = 'photo';
                                }
                                jQuery('#FillEmailDialog').css('visibility','visible').dialog('open');
                        }
                        else if(response == 'success'){
                                if(check==2){
                                        loginDialog.dialog('close');
                                       
                                        set_value();
                                }
                                else{
                                    var str = jQuery("#"+dom).serialize().split("&");
                                    var name = str[0].split("=");
                                    window.location="feed.php?username="+name[1];
                                }
                        }
                }
        });
}
</script>
<?php
include("forms/loginForm.php");
include("forms/register_email.php");
include("forms/resetPass.php");
include("footer.php");
?>


