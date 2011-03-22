<?php
$dest_id = $_GET["dest_id"];
$page = $_GET["page"];
if (chk_sql_injection($dest_id)&&(chk_sql_injection($page))) {
	//number of images per row/column 
	$n = constant("COL");
	$m = constant("ROW");
	$t = $m * $n;
	
	//cal number of images in this destination
	$sql = "SELECT * FROM images
			WHERE dest_id = '".$dest_id."'";
	$q->query($sql);
	$k = mysql_num_rows($q->re);
	
	//start, end image
	$s = $t * ($page - 1);
	if ($s > $k) die_to_index();
	$e = $page * $t - 1;
	if ($e > ($k - 1)) $e = $k - 1;	
	$c = $e - $s + 1;
	

	$sql = "SELECT * FROM images
			WHERE dest_id = '".$dest_id."'
			ORDER BY uploaded_at DESC
			LIMIT ".$s.",".$t;
	$q->query($sql);
	$d = 0;
	for ($i = $s; $i <= $e; $i++) {
		$r[$d] = mysql_fetch_array($q->re);
		$d++;		
	}
	$c = 0;
}
?>

<p align="center">
	<a class="upload_image" href=# onclick="upload_image_click()" id="upload_button">UPLOAD YOUR IMAGES</a>
</p>

<table width="100%" >
	<tbody>
	<?php 
	$c1 = 0; $c2 = 0; $c3 = 0;
	for ($i = 1; $i <= $m; $i++) {				
	?>
		<!-- Image -->
		<tr>
			<?php 
			for ($j = 1; $j <= $n; $j++) { ?>
				<td colspan=1 width="33%" valign="bottom" align="center">
					<?php if ($c1 < $d) { 
						$img = $r[$c1];
						$mz_path = MEDIUM_UPLOAD_FOLDER . $img["filename"];
						$oz_path = UPLOAD_FOLDER . $img["filename"];						
						$img_path =(file_exists($mz_path))? $mz_path : $oz_path;
						$img_full_path = URL . $img_path;
					?>
						<a href="<?php echo $oz_path ?>" rel="lightbox-upload" title="<?php echo $img["des"]?>">
							<img class="upload_image" src="<?php echo $img_path?>"/>
						</a>				
					<?php } ?>
				</td>
			<?php 
				$c1++;
			} ?>
		</tr>
		
		<!-- Description -->
		<tr>			
			<?php 
			for ($j = 1; $j <= $n; $j++) {
			?>
				<td colspan=1 valign="top" align="center">
					<?php if ($c2 < $d) { 
						$img = $r[$c2];
					?>
						<span class="img_des">
						<?php 
							if ($img["des"] != "") {
								echo $img["des"];
							}
							else {
								echo "<div align='center'>(no descrition)</div>";
							}
						?>
						</span>
					<?php } ?>
				</td>
			<?php 
				$c2++;
			} ?>
		</tr>
		
		
		<!-- URL & Tags -->
		<tr>			
			<?php 
			for ($j = 1; $j <= $n; $j++) {
			?>
				<td colspan=1 valign="top">
					<?php if ($c3 < $d) {  
						$img = $r[$c3];  					
						$img_path = UPLOAD_FOLDER . $img["filename"];
						$mz_path = MEDIUM_UPLOAD_FOLDER . $img["filename"];
						$sz_path = SMALL_UPLOAD_FOLDER . $img["filename"];
						$img_full_path = URL . $img_path;
						?>
						<label class="img">orignal:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input class="img_link" type="text" value="<?php echo $img_full_path?>"/>
						<br/>

						<label class="img">medium:</label>&nbsp;&nbsp;
						<input class="img_link" type="text" value="<?php echo URL . $mz_path?>"/>
						<br/>

						<label class="img">small:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input class="img_link" type="text" value="<?php echo URL . $sz_path?>"/>
						<br/>
						
						<?php if (logged_in()) { ?>
							<div align="center" class="editpho">
								<a	href="#" 
									class="small_link" 
									onclick="imageEditClick(<?php echo $img["id"]?>)">
									[edit]
								</a>
							</div>
						<?php }else{?>
         						<div align="center" class="editpho"><!-- --></div>
                                                <?php
                                                      }
                                                ?>

						<br/>
					<?php } ?>
				</td>
			<?php
				$c3++;
			} ?>			
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php
	$pn = floor(($k - 1) / $t) + 1;
	
	function page_link($cp) {
		global $dest_id;
		return "photo.php?dest_id=".$dest_id."&page=".$cp;
	}
	
	if ($pn > 1) {
		?><div align="center">
			<table>
			<tbody>
			<tr>
			<?php
			if ($page > 1) {
				?>
				<td valign="center">
				<a href="<?php echo page_link($page - 1)?>">
				<img class="no_border" src="images/other/previous.png"/>
				</a>
				</td>
				<?php
			}
			for ($i = 1; $i <= $pn; $i++) {
				if ($i != $page) {
				?>
					<td valign="center">
					<a href="<?php echo page_link($i)?>">
					<?php echo $i ?>
					</a>		
					</td>
				<?php
				}
				else {
					?>
					<td>
					<?php echo $i; ?>
					</td>
					<?php
				}
				?>
				&nbsp;
				<?php		
			}
			if ($page < $pn) {
				?>
				<td valign="center">
				<a href="<?php echo page_link($page + 1)?>">
				<img class="no_border" src="images/other/next.png"/>
				</a>
				</td>
				<?php
			}			
		?>
		</tr>
		</tbody>
		</table>
		</div><?php
	}
?>