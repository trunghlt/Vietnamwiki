<script type="text/javascript" language="javascript">

      //check username
      function show_check_un(s) {
            var ucl_obj = document.getElementById("username_check_label");
            ucl_obj.innerHTML = s;
      }
      
      function ajax_check_username() {
            var u_obj =  document.getElementById("username");
            x_instant_check_un(u_obj.value, show_check_un);

      }
      //check pasword
      function show_check_pw(s) {
            var pcl_obj = document.getElementById("password_check_label");
            pcl_obj.innerHTML = s;
      }
      
      function ajax_check_password() {
            var p_obj =  document.getElementById("password");
            x_instant_check_pw(p_obj.value, show_check_pw);
      }
      //check confirm password
      function show_check_cp(s) {
            var cpcl_obj = document.getElementById("confirm_password_check_label");
            cpcl_obj.innerHTML = s;
      }      
      
      function ajax_check_confirm_password() {
            var cp_obj =  document.getElementById("confirm_password");
            var p_obj =  document.getElementById("password");
            x_instant_check_cp(cp_obj.value, p_obj.value, show_check_cp);
      }


      //check image code
      function show_check_image_code(s) {
            var iccl_obj = document.getElementById("image_code_check_label");
            iccl_obj.innerHTML = s;
      }      
      
      function check_image_code() {
            var c = document.getElementById("image_code").value;
            x_instant_check_image_code(c,show_check_image_code);
      }
</script>
      
<form id="form1" name="form1" method="post" action="signup.php">      
<table class="contentTable" style="width: 800px" align="center">
  <tr><td><a class="head2">REGISTRATION</a></td></tr>
  <tr>
    <td><b>Username</b></td>
    <td><input type="text" name="username" id="username" onKeyUp="ajax_check_username()" /><span id="username_check_label"></span></td>
  </tr>
  <tr>
    <td><b>Password</b></td>
    <td><input type="password" name="password" id="password" onKeyUp="ajax_check_password()"/><span id="password_check_label"></span></td>
  </tr>
  <tr>
    <td><strong>Confirm password</strong></td>
    <td><input type="password" name="confirm_password" id="confirm_password" onKeyUp="ajax_check_confirm_password()"/><span id="confirm_password_check_label"></span></td>
  </tr>
  <tr>
    <td><strong>Confirm Image </strong></td>
    <td>
      <p>
        <?php
            $sql = "SELECT id
			   FROM confirm_code
			   WHERE session_id = '".$session_id."'";
            $result = mysql_query($sql) or die(mysql_error());                        		
			$row = mysql_fetch_array($result);
			$s = $row["id"];
			echo '<img src="confirm_image.php?id='.$s.'" />';
      ?>      
      </p>
      <p><em>
      Please type in the text (case sensitive) from the above image: </em></p>
      <p>
        <input size="7" maxlength="6" type="text" name="confirm_code" id="image_code" onKeyUp="check_image_code()"/>
        <span id="image_code_check_label"></span>
      </p></td>
  </tr>
  <tr>
    <td height="215"><strong>Terms of service</strong> </td>
    <td><p><em>You must agree to the terms of service in order to register your account </em></p>
      <p>
	  	<?php
			db_connect();
			$result = mysql_query('SELECT * FROM terms_of_service') or die(mysql_error());			
			$row = mysql_fetch_array($result);
			$s = htmlspecialchars($row['content']);
			echo '<textarea rows=10 cols=50 name="textarea" readonly="readonly">'.$s.'</textarea>'
		?>        
       </p>	   </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="Agree" value="I agree the terms" /></td>
  </tr>
</table>
</form>
