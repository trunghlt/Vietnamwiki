<?php
include('core/common.php');
include('core/init.php');
include('core/classes.php');
include('core/session.php');
include('preprocess.php');
include('redirect.php');
include('header.php'); 
include("ajaxLoad.php");
include('destination.php');
?>
    <td class="center">	
	<div style = "background: #EDEFF4; height: 28px;">
		<div id="toolbar"></div>
	</div>
	<div id='col2'>
		<div id="contentTable">
		<?php include("viewdest.php");?>
		</div>
	</div>
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
					loadToolbar("toolbar");
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
function submitLogin() {	
	jQuery.post("/requests/postLogin.php", jQuery("#loginForm").serialize(), 
			function(response){
				if(response==-2)
					alert("This user has been banned");
				else
					loadToolbar("toolbar");
	});
}
</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include("footer.php");
?>


 