<div id="FillEmailDialog" title="Fill Email">
	<form id = "FillEmail">
		Email :
		<input class="field" name="email" id="email" type="text" style="width:130px" /><br />
		<input class="field" name="id_user" id="id_user" type="hidden" />
	</form>
</div>
<script language="javascript">
jQuery(document).ready(function(){
	Fill_EmailDialog = jQuery("#FillEmailDialog").dialog({
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
			Fill: function() {
				submitEmail('FillEmail');
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}		
	});

});
function submitEmail(dom){
	jQuery.post('../requests/update_email.php',jQuery("#"+dom).serialize(),
					function(data){
						if(data != 'null')alert(data);
				});
}
</script>