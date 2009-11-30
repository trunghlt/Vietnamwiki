<?php
	include('core/common.php');
	include('core/init.php');
	include('core/classes.php');
	include('core/session.php');
	include('core/filters.php');
	
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
include("forms/deleteConfirmForm.php");
include("forms/loginForm.php");
include("footer.php");
?>
