<!--style="visibility: hidden; display: none;"-->
 <div class="private_popup"     id="private_popup" style="visibility: hidden; display: none;">
	  <div class="menu_form_header" id="private_popup_drag">
		  <img class="menu_form_exit"   id="private_popup_exit" src="interface/form_exit.png" />
		  &nbsp;&nbsp;&nbsp;Personal information
	  </div>
	  
	  <div class="menu_form_body">			
	  	<div  style="margin:5 5 5 5;">
			<table>
			<tbody>
			<tr>
				<td>
					<h1>Avatar</h1>
					<?php include("avatar.php"); ?>
					<br/>
					<a href="#" name="AU_toggle" id="AU_toggle" class="link">(change)</a>
					<br/> <br/>
					<div id="avatar_upload">
						<?php include("upload/index.php"); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<h1>Personal information</h1>
				</td>
			</tr>
			<tr>
				<td>
					<table>
					<tbody>
					<tr>
						<td style="width: 30%">
							First name:
						</td> 
						<td>
							<input id="firstname" name="firstname" type="text" value ="<?php echo $user_info["firstname"]?>" />							
						</td>				
					</tr>
					<tr>
						<td style="width: 30%">
							Last name:
						</td> 
						<td>
							<input id="lastname" name="lastname" type="text" value ="<?php echo $user_info["familyname"]?>" />
						</td>				
					</tr>		
					<tr>
						<td style="width: 30%">
							Date of birth:
						</td>
						<td>
								<select id="DayDOB" name="DayDOB">
									<?php
										for ($i = 1; $i <= 31; $i++) {
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									?>		
								</select>

							  <select id="MonthDOB" name="MonthDOB">
								<option value='1'>Jan</option>
								<option value='2'>Feb</option>
								<option value='3'>Mar</option>
								<option value='4'>Apr</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>Aug</option>
								<option value='9'>Sep</option>
								<option value='10'>Oct</option>
								<option value='11'>Nov</option>
								<option value='12'>Dec</option>
							  </select>

							  <select id="YearDOB" name="YearDOB">
								<?php
									for ($i = date("Y") - 1; $i >= 1900; $i--) {
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
								?>
							  </select>
						</td>
					</tr>	
					<tr>
						<td>
							Location :
						</td>
						<td>
							<select name="loc" id="loc">
								<?php
									$sql = "SELECT *
											FROM countries";
									$result = mysql_query($sql) or die(mysql_error());
									while ($row = mysql_fetch_array($result)) {
										?>
										<option value="<?php echo $row["id"]?>"><?php echo $row["country"]?></option>
										<?php 
									}
								?>     
							</select>						
						</td>
					</tr>	
					</tbody>
					</table>
			</td>
			</tr>
			<tr>
				<td align="center">
					<br/><br/>
					<input type="submit" value="Update" onclick="ok_click()"/>
					<input type="submit" value="Cancel" onclick="cancel_click()"/>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	  </div>
</div>

<script type="text/javascript">

	function page_refresh(s) {
		location.reload(true);		
	}
	
	function cancel_click() {
		popup_disappear("private_popup");
	}

	function ok_click() {
		var ftmp 	= document.getElementById("tmp_file_name").value;
		var fn 		= document.getElementById("firstname").value;
		var ln 		= document.getElementById("lastname").value;
		var dd 		= document.getElementById("DayDOB").value;
		var mm 		= document.getElementById("MonthDOB").value;
		var yyyy	= document.getElementById("YearDOB").value;
		var loc		= document.getElementById("loc").value;
		x_upload_user_info(ftmp, fn, ln, dd, mm, yyyy, loc, page_refresh);
	}
	
	var AU_Slide = new Fx.Slide('avatar_upload'); 
	
	window.addEvent('domready', function(){
		AU_Slide.hide();
	});
					 
	$('AU_toggle').addEvent('click', function(e){
		e = new Event(e);
		if ($('AU_toggle').innerHTML == "(change)") {
				$('AU_toggle').setHTML("(hide)");
				AU_Slide.toggle();
		  }
		  else {
				$('AU_toggle').setHTML("(change)");
		  		AU_Slide.hide();
		  }	
		  e.stop();
	});
</script>