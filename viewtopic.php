<?php
	include('core/common.php');
	include('core/init.php');
	include('core/classes.php');
	include('core/session.php');
	//include("core/classes/Color.php");
	include('core/filters.php');
	include('core/classes/CommentElement.php');
	$postElement = new PostElement;
	
	if($postElement->query(PostElement::filterId($_GET["id"]))==0)
		header("location:index.php");
	$indexElement = new IndexElement;
	$indexElement->query($postElement->indexId);
	$destination = $indexElement->destId;
	$index_id = $indexElement->id;
	$post_id = $postElement->id;		
	include('preprocess.php');
	include('header.php');	
	include('destination.php');

	$q = new Db;	
	$user_info = new User;
//change_template();
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
     <td classs="right">
        <?php include('listquestion.php');?>
    </td>
</tr>
</tbody>
</table>
<?php 
include("forms/editForm.php");
include("forms/commentForm.php");
include("forms/composeForm.php");
include("forms/edit_login.php");
include("forms/deleteConfirmForm.php");
include("forms/loginForm.php");
include('dialog.php');
include("forms/register_email.php");
include("footer.php");
?>


