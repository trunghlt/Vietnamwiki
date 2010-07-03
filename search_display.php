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

	<td  valign="top">	
		<div style = "background: #EDEFF4; height: 28px;">
			<div id ="toolbar"></div>
		</div>
		<div id="contentTable">
			<?php include("sbody.php");?>
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
	var loginForm = $("loginForm");
	loginForm.set("send", {	url: "requests/postLogin.php", evalScripts: true});
	loginForm.send();
	loginForm.get("send").addEvent("onComplete", function(response){
		loadToolbar("toolbar");
	});
}
</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include("footer.php");
?>
