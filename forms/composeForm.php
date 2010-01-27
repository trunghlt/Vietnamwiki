<?php
include('dialog.php');
?>
<div id="composeDialog" title="Edit entry">

	<iframe style="	margin: 0px 0px 0px -10px; 
					border: 0px; 
					padding: 0px 0px 0px 0px;
					width: 700px; 
					height: 660px;" 
			name="textComposeFrame"
			id="textComposeFrame"				
			src="textEditor.php?destId=<?php echo $destination?>">
	</iframe>  	

</div>
<script language="javascript">

function convert(s) {
	s = s.replace(/&/g,'|');
	return s;
}

function submitComposeForm() {
	currentDestItem.removeClass("active");
	currentDestItem.addClass("linksmall");	
	currentIndexItem.removeClass("activeIndex");
	currentIndexItem.addClass("linksmall");	
	currentMySlide.slideOut();	

	var textComposeFrame = $("textComposeFrame");
	var frameWindow = textComposeFrame.contentWindow;
	var frameDocument = frameWindow.document;
	var destId = encodeURI(frameDocument.getElementById("location").value); 
	var indexId = encodeURI(frameDocument.getElementById("index").value); 

	currentDestItem = $("destItem_"+destId);
	currentDestItem.removeClass("linksmall");
	currentDestItem.addClass("active");
	currentIndexItem = $("indexLink"+indexId);
	currentIndexItem.removeClass("linksmall");
	currentIndexItem.addClass("activeIndex");
	currentMySlide = mySlide[destId];
	currentMySlide.slideIn();

	var title = encodeURI(frameDocument.getElementById("title").value);
	var smallImgURL = encodeURI(frameDocument.getElementById("smallImgURL").value);
	var bigImgURL = encodeURI(frameDocument.getElementById("bigImgURL").value);
	var summary = encodeURI(frameDocument.getElementById("summary").value);		
	var content = convert(encodeURI(frameWindow.tinyMCE.activeEditor.getContent()));
	var ref = encodeURI(frameDocument.getElementById("reference").value);
	
	var submitPostRequest = new Request({	url: "submitComposeForm.php",
											evalResponse: false
											});	
											
	submitPostRequest.send(	"indexId=" + indexId 
							+ "&title=" + title
							+ "&smallImgURL="+smallImgURL
							+ "&bigImgURL=" + bigImgURL
							+ "&summary=" + summary
							+ "&content=" + content
							+ "&ref=" + ref);
							
	submitPostRequest.addEvent('onComplete', function(response) {
		switch(response){
		 case 'null':{
			alert('Please insert Title, Summary, and content');
			composeDialog.dialog("open");		 	
		 }
		 break;
		 case 0:{
			post_review.dialog('open');
			window.location = "index2.php";
		 }
		 break;
		 default:
			window.location = "viewtopic.php?id=" + response;
		}
	});
}
jQuery(document).ready(function(){ 
	composeDialog = jQuery("#composeDialog").dialog({
		autoOpen: false,
		width: '720',
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
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});	
</script>