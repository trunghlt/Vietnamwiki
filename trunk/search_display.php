<?php
session_start();
$session_id = session_id();
include('core/common.php');
include('core/init.php');
include('core/classes.php');
include('core/session.php');
	$ip = $_SERVER['REMOTE_ADDR'];
	process($session_id, $ip);
include('header.php'); 
include('destination.php');
include('ajaxLoad.php');
?> 

	<td  class="center" valign="top" style="width:820px;">
		<div id="menuWrapper">
			<div id ="toolbar"></div>
		</div>
		
		<div id="contentTable">
			<?php include("sbody.php");?>
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

<script language="javascript">
jQuery(function(){
	loadToolbar("toolbar");
});

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
					loadToolbar("toolbar");
                                        loadNotification();
				});
}

//Set value when user register successfully email
function set_value(){
    loadToolbar("toolbar");
    loadNotification();
}
//end
function submitLogin(dom,check) {
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(),
			function(response){
				if(response == -2)
				{
					alert("This user has been banned");
				}
				else if(response == 'false'){
                                    alert("Login's fail");
                                }
                                else
				{
					if(response != '' && response != 'success'){
						document.getElementById('id_user').value = response;

                                                var str = jQuery("#"+dom).serialize().split("&");
                                                var name = str[0].split("=");
                                                jQuery("#name_user").val(name[1]);
						document.getElementById('editpost').value = 'search';
                                                jQuery('#FillEmailDialog').css('visibility','visible').dialog("open");
					}
					else if(response == 'success'){
                                                loginDialog.dialog("close");
                                                set_value();
					}
				}
	});
}
</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include("forms/register_email.php");
include("forms/resetPass.php");
include("footer.php");
?>
