<?php
include('core/common.php');
include('core/init.php');
include('core/session.php');
include('core/filters.php');
include('core/classes.php');
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
                                        jQuery(".editpho").html("<div align=\"center\" id=\"editpho\"><!-- --></div>");
				});
}

function submitLogin() {
        jQuery.post("/requests/postLogin.php", jQuery("#loginForm").serialize(),function(response){            
                if(response==-2)
                        alert("This user has been banned");
                else{
                        if(response != '' && response != 'success'){
                                jQuery('#FillEmailDialog').css('visibility','visible').dialog('open');
                        }
                          loadToolbar("toolbar");
                          jQuery(".editpho").html("<a	href=\"#\" class=\"small_link\" onclick=\"imageEditClick(<?php echo $img["id"]?>)\"> [edit] </a>");
                }
        });
}
</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include("forms/register_email.php");
include("footer.php");
?>


