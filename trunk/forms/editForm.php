<?php include("ajaxLoad.php");
include('dialog.php');
?>

<div id="editDialog" title="Edit entry">

	<iframe style="	margin: 0px 0px 0px -10px; 
					border: 0px; 
					padding: 0px 0px 0px 0px;
					width: 700px; 
					height: 660px;" 
			name="textEditFrame"
			id="textEditFrame"				
			src="textEditor.php?<?php 
			if(isset($draf)) 
				echo "editionId_draf=".$draf;
			elseif(isset($currentPostElement->id))
					echo "id=".$currentPostElement->id;?>">
	</iframe>  	

</div>
<script language="javascript">

function convert(s) {
	s = s.replace(/&/g,'|');
	return s;
}

function submitEditForm() {
	var textEditorFrame = document.getElementById("textEditFrame");
	var frameWindow = textEditorFrame.contentWindow;
	var frameDocument = frameWindow.document;
	var destId = encodeURI(frameDocument.getElementById("location").value); 
	var indexId = encodeURI(frameDocument.getElementById("index").value); 
<?php if(isset($currentPostElement->id)) {?>
	currentDestItem.removeClass("active");
	currentDestItem.addClass("linksmall");	
	currentIndexItem.removeClass("activeIndex");
	currentIndexItem.addClass("linksmall");	
	currentMySlide.slideOut();
	

	
	currentDestItem = $("destItem_"+destId);
	currentDestItem.removeClass("linksmall");
	currentDestItem.addClass("active");
	currentIndexItem = $("indexLink"+indexId);
	currentIndexItem.removeClass("linksmall");
	currentIndexItem.addClass("activeIndex");
	currentMySlide = mySlide[destId];
	currentMySlide.slideIn();
<?php }	?>

	var title = frameDocument.getElementById("title").value;
	var smallImgURL = frameDocument.getElementById("smallImgURL").value;
	var bigImgURL = frameDocument.getElementById("bigImgURL").value;	
	var summary = frameDocument.getElementById("summary").value;		
	var content = convert(frameWindow.tinyMCE.activeEditor.getContent());
	var ref = encodeURI(frameDocument.getElementById("reference").value);
	var id = <?php if(isset($draf)) echo $currentEdition->postId;
					 elseif(isset($currentPostElement->id))
							echo $currentPostElement->id;?>;
	var type = <?php if(isset($draf)) echo '1';
					 elseif(isset($currentPostElement->id))
							echo '2';?>;
/*	
	jQuery.post("submitPost.php",
				{id: id, indexId: indexId, title: title, smallImgURL: smallImgURL, bigImgURL: bigImgURL, summary: summary, content: content},
				function(response) {
					jQuery("#postContent").html(response);
				},
				"html");
*/				
	
	var submitPostRequest = new Request({	url: "submitPost.php",
											evalScripts: true
											});	
											
	submitPostRequest.send( "id=" + id
							+"&indexId=" + indexId 					
							+ "&title=" + title
							+ "&smallImgURL=" + smallImgURL
							+ "&bigImgURL=" + bigImgURL
							+ "&summary=" + summary
							+ "&content=" + content
							<?php if(isset($draf)) {?>
							+ "&id_edition=" + <?php echo $draf?>
							<?php } ?> 
							+ "&ref=" + ref 
							+ "&type=" + type);
							
	submitPostRequest.addEvent('onComplete', function(response) {
		$("postContent").set('html', response);
		<?php if(isset($draf)){?>
			window.location = 'draft.php?id=<?php echo $draf?>';
		<?php }elseif(isset($currentPostElement->id)){?>
		loadEditorList(<?php echo $currentPostElement->id ?>, "editorList");
		<?php }?>
	});
	
}

jQuery(document).ready(function(){ 
	editDialog = jQuery("#editDialog").dialog({
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
				submitEditForm();
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});	
</script>