<div class="head3" style="margin-left: -10px; width: 180px;">Destinations</div>

<table class="leftmenu" cellpadding="0" cellspacing="0">
<tbody>

<!--DESTINATION MENU-->
<tr><td height="1" bgcolor="#e7e6e6" colspan="2"></td></tr>
<?php
if (!isset($destination)) { $destination = 0; $index_id = 0; $photo = 0;}

if (!isset($photo)) $photo = 0;

$indexMenus = $memcache->get("indexMenus");
if ($indexMenus == NULL) {
    $indexMenus = Array();
    $q->query("	SELECT *
			    FROM index_menu
			    ORDER BY ord");			
    while ($r = mysql_fetch_array($q->re)) {
	    $destId = $r["dest_id"];
	    if ( !isset($indexMenus[$destId]) ) $indexMenus[$destId] = Array();
	    $indexMenus[$destId][] = $r;
    }
    $memcache->set("indexMenus", $indexMenus);
}

$destinations = $memcache->get("destinations");
if ($destinations == NULL) {
    $q->query("	SELECT * 
			    FROM destinations
			    ORDER BY ord");
    $destinations = $q->re;
    $memcache->set("destinations", $destinations);
}

$result = $destinations;
$n = 0;
$numrow = mysql_num_rows($result);
While ($row = mysql_fetch_array($result)) {
	$destId = $row["id"];
    $n++; ?>

			<?php 	 
			$class = ($row["id"] == $destination)? "active" : "linksmall";
			$href = ($n < $numrow)? ("index2.php?id=".$row['id']) : "about.php";
			?>
 	<tr id="des_<?php echo $row["id"]?>" class="des">
	 	<td width="23px">
		 	<img style="margin-left: 5px;" src="/css/images/bg/arrow.jpg"/>
		 </td>
		<td>
			<p style='margin-bottom: 2px; margin-top: 2px;'>
				<a id="destItem_<?php echo $row["id"]?>" class='<?php echo $class?>' style="cursor:pointer;"><?php echo $row["EngName"]?></a>
			</p>
		</td>
	</tr>		
	<tr><td colspan="2" height="0">
		<div id="itemList_<?php echo $row["id"]?>" name="itemList_<?php echo $row["id"]?>" class="index_des">
		<ul class="itemList">
			<?php $i = 0;
			if (isset($indexMenus[$destId])) {
				for ($i = 0; $i < sizeof($indexMenus[$destId]); $i++) {
					$r = $indexMenus[$destId][$i];
					$indexItemClass = ($photo==0 && $r["id"] == $index_id)? "activeIndex" : "linksmall";
					?>
					<li class="indexItem">
						<?php if ($r["name"] == "Map") { ?>
							<a class="<?php echo $indexItemClass?> iframe" href="map.php?id=<?php echo $destId?>">Map</a>
						<?php } else {?>
							<a id="indexLink<?php echo $r["id"]?>" class="<?php echo $indexItemClass?>" href="<?php echo getIndexPermalink($r["id"])?>">
							<?php echo $r["name"];?>
							</a>
						<?php } ?>
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
			<?php if ($row["id"] != $destination) { ?>
				jQuery("#itemList_<?php echo $row["id"]?>").hide();
				jQuery("#itemList_<?php echo $row["id"]?>").attr('class','index_des');
			<?php } ?> 
			jQuery("#destItem_<?php echo $row["id"]?>").click(function(){
				jQuery("#itemList_<?php echo $row["id"]?>").toggle('slow');
				if(jQuery(".des_active").size()==0){
					jQuery("#itemList_<?php echo $row["id"]?>").attr('class','index_active');
					jQuery("#des_<?php echo $row["id"]?>").attr('class','des_active');
				}
				else{
					jQuery(".des_active").each(function (){
							var id = jQuery(".des_active").find('a').attr('id');
							var id_sub = id.substring(id.indexOf('_')+1,id.length);
							if(id_sub!=<?php echo $row["id"]?>){
								jQuery("#itemList_"+id_sub+"").toggle('slow');
								jQuery("#itemList_"+id_sub+"").attr('class','index_des');
								jQuery("#des_"+id_sub+"").attr('class','des');
								jQuery("#des_<?php echo $row["id"]?>").attr('class','des_active');
								jQuery("#itemList_<?php echo $row["id"]?>").attr('class','index_active');
							}
							else{
								jQuery("#itemList_<?php echo $row["id"]?>").attr('class','index_active');
								jQuery("#des_<?php echo $row["id"]?>").attr('class','des');
							}
					});
				}
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
<script>
jQuery("#des_<?php echo $destination?>").attr('class','des_active');
jQuery("#itemList_<?php echo $destination?>").attr('class','index_active');
</script>
