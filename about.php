<?php
include('core/common.php');
include('core/init.php');
include('core/classes/Db.php');
include('core/classes/User.php');
include('core/classes/IndexElement.php');
include('core/classes/DestinationElement.php');
include('core/session.php');
include('header.php'); 
include('destination.php');
?> 
<td class="center" style="width:820px;">	
<?php include('toolbar.php'); ?>
<div id='col2'>
	<table class="contentTable"><tbody><tr><td >
	<?php include("view_about.php");?>
	</td><tr></tbody></table>
</div>
</td>
</tr>
<tr>
<td colspan=3>
	<?php include("footLinks.php");?>
</td>
</tr>
</tbody></table>  
<?php
include("footer.php");
?>
