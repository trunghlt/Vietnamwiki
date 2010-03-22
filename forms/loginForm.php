<div id="loginDialog" title="login">
	<form id = "loginForm">
		Username:
		<input class="field" name="username" id="username" type="text" style="width:130px" /><br/>
		Password:
		<input class="field" name="password" id="password" type="password" style="width:130px" />
	</form>
	<div style="padding-top: 10px;"><?php echo render_fbconnect_button();?></div>
</div>
<script language="javascript">
jQuery(document).ready(function(){ 
	loginDialog = jQuery("#loginDialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 200,
		modal: true,
		resizable:false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},		
		buttons: {
			Login: function() {
				submitLogin('loginForm',1);
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			},
			SignUp: function(){
				jQuery(this).dialog('close');
				window.location = '/signup.php';				
			}
		}		
	});

});
</script>