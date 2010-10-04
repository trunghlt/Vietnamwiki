<style type="text/css">

	.thankYouLbl {
		font-size: 10px;
		color: #707070;
		margin-bottom: 5px;
	}

	#fbDiv {
		position: absolute; 
		top: 200px; 
		left: -2px; 
		width: auto;
		
	}
	
		#fbDiv #fbContent {
			border: 1px solid rgb(204, 204, 204); 
			overflow: hidden; 
			height: auto; 
			width: 200px; 
			float: left; 
			background-color:#E0E0E0; /*rgb(255, 255, 255);*/
			padding: 0px 10px 10px 10px;
			display: none;
		}
		
		#fbDiv #fbContent #fbTxt {
			border: 1px solid #B3B3B3;
			background: #FFFFFF url('css/images/bg/txt-bg.jpg') repeat-x scroll 0 0;
			font-family: "Lucida Grande", Tahoma;
			font-size: 12px;
			color: #707070;
			width: 190px; 
			height: 50px;
		}		
		
		#fbDiv #fbContent #feedbackSend {
			padding: 5px 5px;
			font-size: 150%;
			width: 100px;
		}
		

	
	#fbDiv #fbButton {
		float: left; 
		position: relative; 
		left: -1px;
	}
	
	
</style>
<div id="fbDiv">
	<div id="fbContent">
		<h1>Feedback</h1>
		<p style="font-size: 10px; font-family: font-family: Lucida Grande, Tahoma;">VietnamWiki.net is in beta. Love it? Hate it? Want to suggest new features or report a bug? We'd love to hear from you. Leave your email if you want us to get back to you.</p>
		<textarea id="fbTxt">Please leave us your feedback</textarea>
		<label id="thankYou" class="thankYouLbl">Thank you so much for your feedback, we will read it carefully and have appropriate responses asap !</label>
		<div align="center" style="margin-top: 10px;"><input type="button" value="send" name="feedbackSend" id="feedbackSend" /></div>
	</div>
	<a id="fbButton" onClick="jQuery('#fbContent').toggle(300); jQuery('#slideButtonLeft').toggle();jQuery('#slideButtonLeftActive').toggle();">
		<div id="slideButtonLeft" ></div>
		<div id="slideButtonLeftActive"></div>
	</a>
</div>


<script language="javascript">
jQuery(document).ready(function() {
	jQuery("#fbContent").hide();
	jQuery("#slideButtonLeftActive").hide();
	jQuery("#thankYou").hide();
});

jQuery("#feedbackSend").click(function(){
	jQuery.post("requests/emailFeedback.php", {fbContent: jQuery("#fbTxt").val()} , function() {
		jQuery("#thankYou").show("slow");
	});
});

jQuery("#fbTxt").focus(function(){
	jQuery(this).val("");
	jQuery(this).animate({
		height: "100px"
	}, 800);
});
</script>
