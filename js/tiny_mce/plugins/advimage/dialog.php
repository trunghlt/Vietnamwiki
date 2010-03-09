<script src="../../../jquery/jquery-1.2.6.js" type="text/javascript"></script>
<?php

include("../../../../core/init.php");
include("../../../../core/classes/Filter.php");
include("../../../../core/common.php");
include("../../../../core/classes/ActiveRecord.php");

if (isset($_GET["task"])) {
	$task = $_GET["task"];
	if (isset($_GET["dest_id"])) {
		$id = Filter::filterInput($_GET["dest_id"],"index.php",1);
		if ($id > 0) {
				$dest_id = $id;
				$page = $_GET["page"];
				if (chk_sql_injection($dest_id)&&(chk_sql_injection($page))) {
					//number of images per row/column 
					$n = 2;
					$m = 1;
					$t = $m * $n;
					
					$q = new Active;
					$row = $q->select('','images',"dest_id = $dest_id");

					//cal number of images in this destination
					$k = $q->get_num();
					
					//start, end image
					$s = $t * ($page - 1);
					if ($s > $k) die_to_index();
					$e = $page * $t - 1;
					if ($e > ($k - 1)) $e = $k - 1;	
					$c = $e - $s + 1;
					
					/*$sql = "SELECT * FROM images
							WHERE dest_id = '".$dest_id."'
							ORDER BY uploaded_at DESC
							LIMIT ".$s.",".$t;*/
					//$q->query($sql);
					$q->orderby('uploaded_at','desc');
					$q->limit($s,$t);
					$row = $q->select('','images',"dest_id = $dest_id");

					$d = 0;
					for ($i = $s; $i <= $e; $i++) {
						$r[$d] = $row[$d];//mysql_fetch_array($q->re);
						$d++;		
					}
					$c = 0;
				}
				?>
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
										$mz_path = '../../../../'.MEDIUM_UPLOAD_FOLDER . $img["filename"];
										$oz_path = '../../../../'.UPLOAD_FOLDER . $img["filename"];						
										$img_path =(file_exists($mz_path))? $mz_path : $oz_path;
										$img_full_path = URL . $img_path;
									?>
											<img class="upload_image" src="<?php echo $img_path?>"/>
				
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
						return "dialog.php?task=1&dest_id=".$dest_id."&page=".$cp;
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
								<a href="#" onclick="update_body(1,<?php echo $dest_id?>,<?php echo $page-1?>)">
								<img class="no_border" src="../../../../images/other/previous.png"/>
								</a>
								</td>
								<?php
							}
							for ($i = 1; $i <= $pn; $i++) {
								if ($i != $page) {
								?>
									<td valign="center">
									<a href="#" onclick="update_body(1,<?php echo $dest_id?>,<?php echo $i?>)">
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
								<a href="#" onclick="update_body(1,<?php echo $dest_id?>,<?php echo $page+1?>)">
								<img class="no_border" src="../../../../images/other/next.png"/>
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
				
					
		}
	}
return;	
}

?>

<p><label>Destinations</label>
<?php
	
	if(!isset($_GET["page"]))
		$page = 1;
	else
		$page = chk_sql_injection($_GET["page"]);
		
?>
<input type="hidden" id="page" value="<?php echo $page?>" />
<select name="dest1" id="dest1" onChange="javascript:update_dest(1)">
<option value='0'>Unknown</option>
<?php
	$q = new Active;
	$q->orderby('ord');
	$row = $q->select('','destinations','');	
	foreach($row as $r) {
		if(isset($_GET['dest_id']))
		{
			$id = $_GET['dest_id'];
			if(chk_sql_injection($id))
			{
				if($id == $r["id"])
					echo "<option value='".$r["id"]."' selected>".$r["EngName"]."</option>";
			}
		}	
		else{
			echo "<option value='".$r["id"]."'>".$r["EngName"]."</option>";
		}
	}
?>

</select></p>
<div id="body1" style="cursor: pointer";>
</div>
<script type="text/javascript">
	function update_dest(x) {
		var e = document.getElementById("dest" + x);
		var page = document.getElementById("page");
		jQuery.get('dialog.php',{task:1,dest_id:e.value,page:page.value},				
					function(data){
						document.getElementById('body'+x).innerHTML = data;
					});
	}
	function update_body(x,id,page) {
		jQuery.get('dialog.php',{task:x,dest_id:id,page:page},
					function(data){
						document.getElementById('body'+x).innerHTML = data;
					});
	}
</script>