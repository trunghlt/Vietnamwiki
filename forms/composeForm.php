<?php
include('dialog.php');
?>
<div id="composeDialog" title="Edit entry">
	<div id="composeDialogContent"></div>
	<!--
	<iframe style="	margin: 0px 0px 0px -10px; 
					border: 0px; 
					padding: 0px 0px 0px 0px;
					width: 700px; 
					height: 750px;" 
			name="textComposeFrame"
			id="textComposeFrame"				
			src="textEditor.php?destId=<?php echo $destination?>">
	</iframe>  	
	-->
</div>
<script language="javascript">

function convert(s) {
	s = s.replace(/&/g,'|');
	return s;
}

function submitComposeForm() {
/*	
	currentDestItem.removeClass("active");
	currentDestItem.addClass("linksmall");	
	currentIndexItem.removeClass("activeIndex");
	currentIndexItem.addClass("linksmall");	
	currentMySlide.slideOut();	
*/
	var textComposeFrame = document.getElementById("textComposeFrame");
	var frameWindow = textComposeFrame.contentWindow;
	var frameDocument = frameWindow.document;
	var destId = encodeURI(frameDocument.getElementById("location").value); 
	var indexId = encodeURI(frameDocument.getElementById("index").value); 
/*
	currentDestItem = $("destItem_"+destId);
	currentDestItem.removeClass("linksmall");
	currentDestItem.addClass("active");
	currentIndexItem = $("indexLink"+indexId);
	currentIndexItem.removeClass("linksmall");
	currentIndexItem.addClass("activeIndex");
	currentMySlide = mySlide[destId];
	currentMySlide.slideIn();
*/
	var title = encodeURI(frameDocument.getElementById("title").value);
	var smallImgURL = encodeURI(frameDocument.getElementById("smallImgURL").value);
	var bigImgURL = encodeURI(frameDocument.getElementById("bigImgURL").value);
	var summary = encodeURI(frameDocument.getElementById("summary").value);		
	var content = convert(encodeURI(frameWindow.tinyMCE.activeEditor.getContent()));
	var ref = encodeURI(frameDocument.getElementById("reference").value);
	var preview = frameDocument.getElementById("preview").value;
	var bool = true;
	if(preview != 1) bool = confirm("<?=NOPREVIEW_CONFIRM_MSG?>");
	if(bool == true){	
		jQuery.post("submitComposeForm.php",{indexId: indexId, title: title, smallImgURL: smallImgURL,
											 bigImgURL: bigImgURL, summary: summary, content: content, ref: ref}, 
											function(response){				
												switch(response.replace(/^\s+|\s+$/g,"")){
												 case 'null':{
														alert('Please insert Title, Summary, and content !');
												 }
												 break;
												 case 'preview':{
													composeDialog.dialog('close');
													jQuery('#confirm').css('visibility','visible').dialog('open');
													//window.location = "index2.php";
												 }
												 break;
												 default:
												 {
													window.location = response;
													composeDialog.dialog('close');
												 }break;
												}
											}
		);
	}
}
jQuery(document).ready(function(){ 
	composeDialog = jQuery("#composeDialog").dialog({
		autoOpen: false,
		width: '720px',
		height: 'auto',
		modal: true,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Submit': function() {
				submitComposeForm();
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
	jQuery("#composeDialog").bind("dialogopen", function(event, ui) {
		if (jQuery("#composeDialogContent > iframe").size() == 0) {
			composeDialogContent = document.getElementById("composeDialogContent");
			iframe = document.createElement("IFRAME");
			iframe.setAttribute("src", "textEditor.php?destId=<?=$destination?>");
			iframe.setAttribute("id", "textComposeFrame");
			iframe.style.width = "700px";
			iframe.style.height = "750px";
			iframe.style.border = "0px";
			composeDialogContent.appendChild(iframe);
		}
	});
});	
</script>
