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
<td class="center">	
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
				});
}

function submitLogin() {	
	jQuery.post("requests/postLogin.php", jQuery("#loginForm").serialize(), function(data) {
		loadToolbar("toolbar");
	});
}
</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include("footer.php");
?>


