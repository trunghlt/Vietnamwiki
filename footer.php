</tr>
</tbody></table>


<div id="invalidLoginDialog" title="Alert">
	Your usernamse/password is invalid !
</div>
<script language="javascript">
jQuery(document).ready(function(){
        loadNotification();
	invalid_LoginDialog = jQuery("#invalidLoginDialog").dialog({
		autoOpen: false,
		height: '100',
		resizable: false,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Ok': function() {
				jQuery(this).dialog('close');
			}		
		}
	});
});
function loadNotification(){
    jQuery.post("/requests/notification.php", {},function(data){
        jQuery('#text_notification').html(data);
    });
}
    function agree_notification(){
        jQuery.post("/requests/agreenotification.php", {},function(data){
            loadNotification();
            alert("Thanks");
        });
    }
</script>
<?php
include("feedback.php");
include("googleAnalytical.php")
?>
</div>
</body>
</html>
