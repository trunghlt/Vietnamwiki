<table class="leftmenu" cellpadding="0" cellspacing="0">
<tbody>

<!--DESTINATION MENU-->
<tr><td colspan="2" class="head3" >Destinations</td></tr>
<?php
if (!isset($destination)) { $destination = 0; $index_id = 0; $photo = 0;}

if (!isset($photo)) $photo = 0;

$indexMenus = Array();
$q->query("	SELECT *
			FROM index_menu
			ORDER BY ord");			
while ($r = mysql_fetch_array($q->re)) {
	$destId = $r["dest_id"];
	if ( !isset($indexMenus[$destId]) ) $indexMenus[$destId] = Array();
	$indexMenus[$destId][] = $r;
}

$q->query("	SELECT * 
			FROM destinations
			ORDER BY ord");
$result = $q->re;
$n = 0;
$numrow = mysql_num_rows($result);
While ($row = mysql_fetch_array($result)) {
	$destId = $row["id"];
    $n++; ?>
 	<tr>
	 	<td width="23px">
		 	<img style="margin-left: 5px;" src="css/images/bg/arrow.jpg"/>
		 </td>
			<?php 	 
			$class = ($row["id"] == $destination)? "active" : "linksmall";
			$href = ($n < $numrow)? ("index2.php?id=".$row['id']) : "about.php";
			?>
		<td>
			<p style='margin-bottom: 2px; margin-top: 2px;'>
				<a id="destItem_<?php echo $row["id"]?>" class='<?php echo $class?>' href='#' ><?php echo $row["EngName"]?></a>
			</p>
		</td>
	</tr>		
	<tr><td colspan="2" height="0">
		<div id="itemList_<?php echo $row["id"]?>" name="itemList_<?php echo $row["id"]?>">
		<ul class="itemList">
			<?php $i = 0;
			if (isset($indexMenus[$destId])) {
				for ($i = 0; $i < sizeof($indexMenus[$destId]); $i++) {
					$r = $indexMenus[$destId][$i];
					$indexItemClass = ($photo==0 && $r["id"] == $index_id)? "activeIndex" : "linksmall";
					?>
					<li class="indexItem">
						<a id="indexLink<?php echo $r["id"]?>" class="<?php echo $indexItemClass?>" href="<?php echo getIndexPermalink($r["id"])?>">
						<?php echo $r["name"];?>
						</a>
					</li>
			<?php }	
			} ?>
			<li class="indexItem" >
			<a class="<?php $class= ($photo==1&&$destination==$row["id"])? "activeIndex":"linksmall"; echo $class ?>" 
				href="photo.php?dest_id=<?php echo $row["id"]?>&page=1">
				Photo
			</a>
			</li>
		</ul>
		</div>
		<script language="javascript">
			mySlide["<?php echo $row["id"]?>"] = new Fx.Slide('itemList_<?php echo $row["id"]?>');
			
			<?php if ($row["id"] != $destination) { ?>
				mySlide["<?php echo $row["id"]?>"].hide();
			<?php } ?> 
			$('destItem_<?php echo $row["id"]?>').addEvent('click', function(e) {					
				e = new Event(e);
				mySlide["<?php echo $row["id"]?>"].toggle();
				e.stop();
			});
		</script>
	 </td>
	 </tr>
	 <tr><td height="1" bgcolor="#e7e6e6" colspan="2"></td></tr>

	<?php
}
?>


</tbody>
</table>
