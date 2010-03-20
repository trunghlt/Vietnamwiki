 </tr>
</tbody></table>

<?php include("footLinks.php");?>

<div id="invalidLoginDialog" title="Alert">
	Your usernamse/password is invalid !
</div>
<script language="javascript">
jQuery(document).ready(function(){ 
	invalidLoginDialog = jQuery("#invalidLoginDialog").dialog({
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
</script>
<?php 
include("feedback.php");
include("googleAnalytical.php")
?>

</div>
</body>
</html>