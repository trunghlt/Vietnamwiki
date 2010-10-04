<?php include("ajaxLoad.php");
include('dialog.php');
?>
<div id="editDialog" title="Edit entry">
	<div id="editDialogContent"></div>
	<!--
	<iframe style="	margin: 0px 0px 0px -10px; 
					border: 0px; 
					padding: 0px 0px 0px 0px;
					width: 700px; 
					height: 750px;" 
			name="textEditFrame"
			id="textEditFrame"				
			src="textEditor.php?<?php 
			/*if(isset($draf))
				echo "editionId_draf=".$draf;
			elseif(isset($post['id']))
					echo "id=".$post['id'];*/?>">
	</iframe>  	
	-->
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
	var preview = frameDocument.getElementById("preview").value;
        var c_method = frameDocument.getElementById("c_method").value;
	var bool = true;
        var bool2 = true;
	if(preview != 1)
		bool = confirm("<?=NOPREVIEW_CONFIRM_MSG?>");
	if(c_method == 1)
		bool = confirm("<?=REPLACE_OLD_EDITION?>");
	if(bool == true && bool2 == true){
		jQuery.post("submitPost.php", {id: id, indexId: indexId, title: title, smallImgURL: smallImgURL,
									   bigImgURL: bigImgURL, summary: summary, content: content,
									   <?php if(isset($draf)){?>id_edition: <?=$draf.","?> <?php }?>
									   ref: ref, type: type}, 
									   function(response) {
											<?php if(isset($draf)){?>
										       	jQuery('#confirm').css('visibility','visible').dialog('open');
												window.location = 'draft.php?id=<?=$draf?>'; 
											<?php }
											else if(isset($post['id'])) {
                                                if(User::check_user_post(myUser_id(myip()))==TRUE){ ?>
                                                               // alert(response);
                                                     window.location = '<?=getPostPermalink($currentPostElement->id)?>';
											<?php } else{?>
                                                jQuery('#confirm').css('visibility','visible').dialog('open');
                                                //alert(response);
                                            <?php }                                                                                        
           									} ?>
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
				if(submitEditForm()) jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
	jQuery("#editDialog").bind("dialogopen", function(event, ui) {
		if (jQuery("#editDialogContent > iframe").size() == 0) {
			editDialogContent = document.getElementById("editDialogContent");
			iframe = document.createElement("IFRAME");
			iframe.setAttribute("src", "textEditor.php?<?=isset($draf)? 'editionId_draf='.$draf: isset($post['id'])? 'id='.$post['id']:'';?>");
			iframe.setAttribute("id", "textEditFrame");
			iframe.style.width = "700px";
			iframe.style.height = "750px";
			iframe.style.border = "0px";
			editDialogContent.appendChild(iframe);
		}
	});	
});	
</script>
