<?php
include('core/common.php');
include('core/init.php');
include('core/classes/Db.php');
include('core/classes/User.php');
include('core/classes/IndexElement.php');
include('core/classes/DestinationElement.php');
include('core/session.php');
include("ajax_header.php");
include('header.php'); 
include('destination.php');
?>
  

    <td class="center">	
	<?php include('toolbar.php'); ?>
	<div id='col2'>
		<table class="contentTable"><tbody><tr><td >
		<?php include("view_about.php");?>
		</td><tr></tbody></table>
	</div>
	</td>
   </tr>
  </tbody></table>
  
<?php
include("footer.php");
?>
