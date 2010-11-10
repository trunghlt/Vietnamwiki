<?php
include('core/common.php');
include('core/init.php');
include('core/classes.php');
include('core/session.php');
include("core/classes/Color.php");
	$check_index = new IndexElement;
	if($_GET["index_id"]!='' && $check_index->query(Edition::filterId($_GET["index_id"]))==0)
		header("location:index.php");

include('preprocess.php');
include('redirect.php');
include('header.php'); 
include("ajaxLoad.php");
include('destination.php');
//change_template();
?>
<td class="center">	
<div style = "background: #EDEFF4; height: 28px;">
<div id="menuWrapper">
	<div id="toolbar"></div>
    </div>
</div>
<div id='col2'>
	<div id="contentTable">
	<?php include("viewdest.php");?>
	</div>
</div>
</td>
<td classs="right">
    <?php include('listquestion.php');?>
</td>
</tr>
<tr>
<td colspan=3>
	<?php include("footLinks.php");?>
</td>
</tr>
</tbody></table>
<script language="javascript">
jQuery(function(){
	loadToolbar("toolbar");
	
});

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
                                        load_qanda(0);
					loadToolbar("toolbar");
						if(document.getElementById('link_add').value == 1){
							document.getElementById('link_add').value = 0;
							document.getElementById('link_add').innerHTML = "<a onClick=\"jQuery('#loginDialog').css('visibility','visible').dialog('open')\">+ Add new topic</a>";							
						}
					jQuery('#field_not_login_comment').html("Email :<br /><input class='field' name='fill_email_comment' id='fill_email_comment' type='text' style='width:250px' value=''/><br />Name :<br /><input class='field' name='fill_name_comment' id='fill_name_comment' type='text' style='width:250px' value=''/><br /><input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='1'/>");					
				});
}
/*
function submitLogin() {	
	var loginForm = $("loginForm");
	loginForm.set("send", {	url: "requests/postLogin.php", evalScripts: true});
	loginForm.send();
	loginForm.get("send").addEvent("onComplete", function(response){
		loadToolbar("toolbar");
	});
}*/
function submitLogin(dom,check) {	
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(), 
			function(response){
			
				if(response==-2)
					alert("This user has been banned");
 				else if(response == 'false'){
                                    alert("Login's fail");
                                }
				else{
					if(response != '' && response != 'success'){
						loadToolbar("toolbar");
						if(document.getElementById('link_add').value == 0){
							document.getElementById('link_add').value = 1;
							document.getElementById('link_add').innerHTML = "<a onClick=\"jQuery('#composeDialog').css('visibility','visible').dialog('open')\">+ Add new topic</a>";
						}
						document.getElementById('id_user').value = response;
						jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");
						jQuery('#FillEmailDialog').css('visibility','visible').dialog('open');
					}
					else if(response == 'success'){
						loadToolbar("toolbar");
                                                load_qanda(0);
						if(document.getElementById('link_add').value == 0){
							document.getElementById('link_add').value = 1;
							document.getElementById('link_add').innerHTML = "<a onClick=\"jQuery('#composeDialog').css('visibility','visible').dialog('open')\">+ Add new topic</a>";
						}
						jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");
					}
				}
	});
}

</script>
<?php
include("forms/composeForm.php");
include("forms/askquestion.php");
include("forms/replyquestion.php");
include("forms/loginForm.php");
include("forms/register_email.php");
include("footer.php");
?>
