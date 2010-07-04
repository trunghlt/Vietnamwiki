<?php include("ajaxLoad.php");
include('dialog.php');
?>
<div id="editDialog" title="Edit entry">

	<iframe style="	margin: 0px 0px 0px -10px; 
					border: 0px; 
					padding: 0px 0px 0px 0px;
					width: 700px; 
					height: 750px;" 
			name="textEditFrame"
			id="textEditFrame"				
			src="textEditor.php?<?php 
			if(isset($draf)) 
				echo "editionId_draf=".$draf;
			elseif(isset($post['id']))
					echo "id=".$post['id'];?>">
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
/*
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
*/
	var title = frameDocument.getElementById("title").value;
	var smallImgURL = frameDocument.getElementById("smallImgURL").value;
	var bigImgURL = frameDocument.getElementById("bigImgURL").value;	
	var summary = frameDocument.getElementById("summary").value;		
	var content = convert(frameWindow.tinyMCE.activeEditor.getContent());
	var ref = encodeURI(frameDocument.getElementById("reference").value);
	var id = <?php if(isset($draf)) echo $currentEdition->postId;
					 elseif(isset($post['id']))
							echo $post['id'];?>;
	var type = <?php if(isset($draf)) echo '1';
					 elseif(isset($post['id']))
							echo '2';?>;
/*	
	jQuery.post("submitPost.php",
				{id: id, indexId: indexId, title: title, smallImgURL: smallImgURL, bigImgURL: bigImgURL, summary: summary, content: content},
				function(response) {
					jQuery("#postContent").html(response);
				},
				"html");
*/				
	var preview = frameDocument.getElementById("preview").value;
	var bool = true;
	if(preview != 1)
		bool = confirm("Do you want to continue submit");
	if(bool == true){
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
			<?php }else if(isset($currentPostElement->id)){?>
				window.location = '<?php echo getPostPermalink($currentPostElement->id)?>';
				loadEditorList(<?php echo $currentPostElement->id ?>, "editorList");
			<?php  }?>
		});
		return true;
	}
	else{
		return false;
	}
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
				if(submitEditForm())
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});	
</script>
