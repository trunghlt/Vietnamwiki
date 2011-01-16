<div class="head3" style="margin-left: -10px; width: 182px;">Destinations</div>

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
    $n++;
    $is_selected = $row["id"] == $destination;
	$class = $is_selected? "active" : "linksmall";
	?>
 	<tr id="des_<?=$row["id"]?>" class="des">
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
		<div id="itemList_<?php echo $row["id"]?>" name="itemList_<?php echo $row["id"]?>" class="<?=$is_selected? "index_active":"index_des"?>">
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
							    <a id="indexLink<?php echo $r["id"]?>" class="<?php echo $indexItemClass?>" href="<?php echo getIndexPermalink($r["id"])?>"><?php echo $r["name"];?></a>
						    <?php } ?>
					    </li>
			    <?php }	
			    } ?>
			    <li class="indexItem" ><a class="<?php $class= ($photo==1&&$destination==$row["id"])? "activeIndex":"linksmall"; echo $class ?>" href="photo.php?dest_id=<?php echo $row["id"]?>&page=1">Photo</a></li>
		    </ul>
		</div>

	 </td>
	 </tr>
	 <tr><td height="1" bgcolor="#e7e6e6" colspan="2"></td></tr>

	<?php
}
?>
</tbody>
</table>

<script>
jQuery(document).ready(function() {
    jQuery(".index_des").css("display", "none");
    jQuery(".des").click(function() {
        $this = jQuery(this);
        if (!$this.hasClass("des_active")) {
            jQuery(".index_active").hide("slow");
            jQuery(".des_active").addClass("des");
            jQuery(".des_active").removeClass("des_active");            
            jQuery(".index_active").addClass("index_des");
            jQuery(".index_active").removeClass("index_active");
            $this.removeClass("des");
            $this.addClass("des_active");
            $this.next().find(".index_des").addClass("index_active");
            $this.next().find(".index_des").removeClass("index_des");
            $this.next().find(".index_active").show("slow");
        }
    });
});    
</script>
