<?php
	include ("projax/projax.php");
	include("init.php"); 	

	$projax = new Projax();
	if (isset($_POST["list"])) {
		print_r($_POST["list"]);
		return;
	}
?>

<script src="projax/js/prototype.js" type="text/javascript"></script>
<script src="projax/js/scriptaculous.js" type="text/javascript"></script>

<div id="drop_bag">
</div>
<?php echo $projax->drop_receiving_element('drop_bag', array('update'=>'body1','url'=>'topic.php?moving=1'))?>
