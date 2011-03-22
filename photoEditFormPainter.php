 <form id="photoEditForm">
 	<div class="image_edit" align="center" valign="middle">
	<table width="100%" height="100%">
	<tbody>
	<tr><td valign="center" align="center">
	<img id="ie_img" src="<?php echo $imgPath?>" width="180px" />
	</td></tr> 
	</tbody>
	</table>
</div>		
<div style="margin: 5px;">
	<label><b>Location</b></label><br/>
	<select id ="ie_loc" name="ie_loc">
		<?php		
			$sql = "SELECT *
					FROM destinations
					ORDER BY ord";
			$q->query($sql);
			while ($row = mysql_fetch_array($q->re)) {
				?>
				<option value="<?php echo $row["id"];?>"
					<?php if ($currentImageElement->destId == $row["id"]) { ?>
						selected="yes"					
					<?php } ?>				
				/><?php echo $row["EngName"];?></option>
				<?php
			}						
		?>
	</select><br/>
	<p><label><b>Description</b></label><br/>
	<input id="ie_des" name="ie_des" type="text" value="<?php echo $currentImageElement->description?>" size="70" /></p>
	<p><label><b>Tags</b></label><br/>
	<input id="ie_tags" name="ie_tags" type="text" value="<?php echo $currentImageElement->tags?>" size="70"/></p>
	<p align="center">
	<input id="ie_id" name="ie_id" type="text" value="<?php echo $currentImageElement->id?>" style="visibility: hidde; display:none"/>
</div>
 </form>
