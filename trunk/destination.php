<?php //include("top.php");?>
<table class="main" cellspacing="0" cellpadding="0" align="center">
 <tbody>
 <tr>
 <td class="top_search" colspan=3>
 	<?php include("top.php"); ?>
	</td>
 </tr>
 	<tr>
    <td colspan=3><div id="banner"><?php include("pic_banner.php");?></div></td>
</tr>
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