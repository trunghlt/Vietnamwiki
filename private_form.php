<div class="privateDialog" id="privateDialog" title="Personal information">
<form id='privateform' name="privateform">
<input id="user_id" name="user_id" type="hidden" value="<?php echo $user_info['id']?>" />	
	<div>
		<h1>Avatar</h1>
		<?php //include("avatar.php"); ?>
		<br/>
		<a href="http://en.gravatar.com/" target="_blank" rel="nofollow">change</a>
		<!--<a href="#" name="AU_toggle" id="AU_toggle" class="link">(change)</a>
		<br/>
		<div id="avatar_upload">
			<?php //include("upload/index.php"); ?>
		</div>-->
	</div>
	<br />
	<h1>Personal information</h1>
	<div id='error'></div>
	<div>
		<label style="float:left; width:100px;">Password: </label>
		<input id="pw" name="pw" type="password" onchange="return change('pw')" />							
	</div><br />
	<div>
		<label style="float:left; width:100px;">Re_Password: </label>
		<input id="re_pw" name="re_pw" type="password" onchange="return change('re_pw')"/>							
	</div><br />
	<div>
		<label style="float:left; width:100px;">Email: </label>
		<input id="email" name="email" type="text" value ="<?php echo $user_info["email"]?>" onchange="return change('email')"/>							
	</div><br />
	<div>
		<label style="float:left; width:100px;">First name: </label>
		<input id="firstname" name="firstname" type="text" value ="<?php echo $user_info["firstName"]?>" />							
	</div><br />
	<div>
		<label style="float:left; width:100px;">Last name: </label>
		<input id="lastname" name="lastname" type="text" value ="<?php echo $user_info["lastName"]?>" />
	</div><br />
	<div>
		<label style="float:left; width:100px;">Date of birth: </label>
<?php
	 $day = date("j", $user_info["dob"]);
	 $month = date("n", $user_info["dob"]);
	 $year = date("Y", $user_info["dob"]);
?>
			<select id="DayDOB" name="DayDOB">
				<?php
				for ($i = 1; $i <= 31; $i++) {
						if($i==$day)
							echo '<option value="'.$i.'" selected>'.$i.'</option>';
						else 
							echo '<option value="'.$i.'">'.$i.'</option>';
					}
				?>		
			</select>

		  <select id="MonthDOB" name="MonthDOB">
		  <?php
		  $arr = array('Jan','Feb','Mar','Apr','May','June','Junly','Aug','Sep','Oct','Nov','Dec');
		  for($i = 1;$i <12; $i++){
		  	if($i==$month)
				echo "<option value='".$i."' selected>".$arr[$i-1]."</option>";
			else
				echo "<option value='".$i."'>".$arr[$i-1]."</option>";
		  }
		?>
		  </select>

		  <select id="YearDOB" name="YearDOB">
			<?php
				for ($i = date("Y") - 1; $i >= 1900; $i--) {
					if($i == $year)
						echo '<option value="'.$i.'" selected>'.$i.'</option>';
					else
						echo '<option value="'.$i.'">'.$i.'</option>';
				}
			?>
		  </select>
	</div><br />
	<div>
			<label style="float:left; width:100px;">Location: </label>

			<select name="loc" id="loc">
				<?php
					$country = new DestinationElement;
					$result = $country->query_country();
					foreach($result as $row) {
						if($row["id"] == $user_info['locationCode']){
						?>
							<option value="<?php echo $row["id"]?>" selected="selected"><?php echo $row["country"]?></option>
						<?php 
						}
						else{
							?>
							<option value="<?php echo $row["id"]?>"><?php echo $row["country"]?></option>
							<?php
						}
					}
				?>     
			</select>
	</div>	
</form>					
</div>

<script type="text/javascript">
jQuery(document).ready(function(){ 
	private_Dialog = jQuery("#privateDialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 720,
		modal: true,
		resizable:false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},		
		buttons: {
			Update: function() {
				ok_click('privateform');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}		
	});
/*var AU_Slide = new Fx.Slide('avatar_upload'); 
	AU_Slide.hide();*/

});
	function page_refresh(user) {
		window.location.replace("profile.php?username="+user);	
	}
	
	function cancel_click() {
		popup_disappear("private_popup");
	}

	function change(dom){
	
		if(dom=='re_pw'){
			if(document.getElementById('pw').value!="")
			{
				jQuery.post('/requests/check.php',{vlpw:document.getElementById('pw').value,vlre_pw:document.getElementById('re_pw').value},function(data){
						if(data!='1'){ 
							document.getElementById(dom).value = '';
							document.getElementById('error').innerHTML = data;
							document.getElementById(dom).focus;
						}
						else
						{
							document.getElementById('error').innerHTML = "<div style='height:0;'></div>";
						}					
				});
			}	
			else{
					document.getElementById('error').innerHTML = 'Please enter password';
					document.getElementById('re_pw').value = '';
					document.getElementById('pw').focus;
			}
		}
		else
		{
				jQuery.post('/requests/check.php',{value:document.getElementById(dom).value,type:dom},
							function(data){ 
								if(data!='1'){ 
									document.getElementById(dom).value = '';
									document.getElementById('error').innerHTML = data;
									document.getElementById(dom).focus;	
								}
								else
								{
									document.getElementById('error').innerHTML = "<div style='height:0;'></div>";
								}								
							});
		}
	}
	function ok_click(dom) {

		jQuery.post('/requests/updateuser.php',jQuery('#'+dom).serialize(),
					function (data){
						if(data && data=='1')
							alert('Update your infomation fail');
						else
						{
							private_Dialog.dialog('close');
							page_refresh(data);
						}
					});
	}
	
/*	var AU_Slide = new Fx.Slide('avatar_upload'); 
					 
	$('AU_toggle').addEvent('click', function(e){
		e = new Event(e);
		if ($('AU_toggle').innerHTML == "(change)") {
				$('AU_toggle').innerHTML = "(hide)";
				AU_Slide.toggle();
		  }
		  else {
				$('AU_toggle').innerHTML = "(change)";
		  		AU_Slide.hide();
		  }	
		  e.stop();
	});*/
</script>