<script language="javascript">
/*------------------------------------------------------
Load toolbar
- dom: the div where the content is filled 
->Toolbar HTML text
--------------------------------------------------------*/
function loadToolbar(dom) {
	jQuery.post("toolbarPainter.php", {}, 
				function(response) {
					jQuery("#"+dom).html(response);
				},
				"html");				
}

/*------------------------------------------------------ 
Load editor list of post with postId into a dom
- postId: id of a post
- dom: the div filled with the returned content
->editor list HTML text
--------------------------------------------------------*/
function loadEditorList(postid, dom, index) {
	if(postid != "" && index == ""){
		jQuery.post("editorListPainter.php", 
					{postId: postid},
					function(response) {
						jQuery("#"+dom).html(response);
					}, 
					"html");
	}
	else if(postid == "" && index != ""){
		jQuery.post("editorListPainter.php", 
					{Index: index}, 
					function(response) {
						jQuery("#"+dom).html(response);
					}, 
					"html");	
	}
}	

/*-------------------------------------------------------
Load editting ribbon
- postId: current post id
- userId: current user id (-1 if not logged in yet)
- dom: the div filled with the returned content
->editting ribbon HTML text 
---------------------------------------------------------*/
function loadEdittingRibbon(postId, dom) {
	jQuery.post("ribbonPainter.php", 
				{postId: postId}, 
				function(response) {
					jQuery("#"+dom).html(response);
				}, 
				"html");
}

/*-------------------------------------------------------
Load draft ribbon
- dom: the div filled with the returned content
->draft ribbon HTML text 
---------------------------------------------------------*/
function loadDraftRibbon(id,dom) {
	jQuery.post("draftRibbonPainter.php", 
				{Id: id}, 
				function(response) {
					jQuery("#"+dom).html(response);
				}, 
				"html");
}

</script>

