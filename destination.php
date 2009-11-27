<?php include("top.php");?>
<table class="main" cellspacing="0" cellpadding="0" align="center">
 <tbody><tr>
    <td class="left" colspan=2>
	<div>
		<?php include("search.php");?>
	</div>	
 </td>
 <tr>
 <td class="left">
	<div id="destinationDiv">
	<?php  include("destinationPainter.php");?>   
	</div>   
 </td>
 <script language="javascript">
 currentIndexItem = $("indexLink<?php echo $index_id?>"); 
 currentDestItem = $("destItem_<?php echo $destination?>");
 currentMySlide = mySlide["<?php echo $destination?>"];
 </script>