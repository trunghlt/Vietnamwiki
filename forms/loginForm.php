<div id="loginDialog" title="login">
	<form id = "loginForm">
		Username:
		<input class="field" name="username" id="username" type="text" style="width:130px" /><br/>
		Password:
		<input class="field" name="password" id="password" type="password" style="width:130px" />
                <input class="filed" name="type_login" id="type_login" type="hidden" value="1" />
	</form>
	<div style="padding-top: 10px;"><?php echo render_fbconnect_button();?></div>
        <div style="padding-top: 10px;"><span style="cursor: pointer; color: #DB1C00; text-decoration: underline;" onclick="showforgot_pass();return false;">Forgot Password</span></div>
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
                                submitLogin('loginForm',jQuery("#type_login").val());
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			},
			SignUp: function(){
				jQuery(this).dialog('close');
				window.location = '/signup.php';				
			}
//                        "Forgot Pass":function(){
//				jQuery(this).dialog('close');
//				resetDialog.css('visibility','visible').dialog('open');
//			}
		}		
	});

});
    function showforgot_pass(){
            loginDialog.dialog('close');
            resetDialog.css('visibility','visible').dialog('open');
    }
</script>