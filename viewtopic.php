<?php
	include('core/common.php');
	include('core/init.php');
	include('core/classes.php');
	include('core/session.php');
	include('core/filters.php');
	include('core/classes/CommentElement.php');
	$postElement = new PostElement;
	$postElement->query(PostElement::filterId($_GET["id"]));
	$indexElement = new IndexElement;
	$indexElement->query($postElement->indexId);
	$destination = $indexElement->destId;
	$index_id = $indexElement->id;
	$post_id = $postElement->id;		
	
	include('header.php');	
	include('destination.php');
	$q = new Db;	
	$user_info = new User;
?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
    <td class="center">			
		<div id="menuWrapper">
			<?php
			echo getMainMenu(0, $post_id);
			?>
			<div id="toolbar"><?php getToolbarHTML();?></div>
		</div>
		<div id="contentTable">			
			<?php include("viewtopicBody.php") ?>
		</div>
	</td>
</tr>
</tbody>
</table>
<?php 
include("forms/editForm.php");
include("forms/commentForm.php");
include("forms/composeForm.php");
include("forms/comment_login.php");
include("forms/edit_login.php");
include("forms/deleteConfirmForm.php");
include("forms/loginForm.php");
include('dialog.php');
include("forms/register_email.php");
include("forms/fill_comment_email_form.php");
include("forms/fill_comment_name_form.php");
include("footer.php");
?>