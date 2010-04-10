<?php
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/common.php");
include("../core/classes/Color.php");
include("../core/classes/Filter.php");
include("session.php");
	process(session_id(), myip());
	if (!logged_in_admin()) header("location: login.php");
		
if(isset($_POST['id_set']) && isset($_POST['act'])){
	$act = Filter::filterInput($_POST['act'],"login.php",3);
	$id_set = Filter::filterInput($_POST['id_set'],"login.php",1);
	$change = new Color;
	if($act=='changetest'){
		$change->update($id_set,"test");
		$r = $change->query($id_set);
		foreach($r as $arr)
			$color_value = $arr['color'];
			
			echo $color_value;
			return;
		}
	if($act=='changemain'){
		$change->update($id_set,"main","");
		return;
	}
	if($act=='choose_page'){	
		$str = "<br />";
		$str .= "<form name='f_page'>";
		$str .= "<input type='hidden' id='color' value='$id_set'/>";
		$str .= "<select name='page' onchange=\"change_preview()\">";
		$str .=	"<option value='index'>Page Index</option>";
		$str .= "<option value='view' >Page View</option>";		
		$str .= "</select>";
		$str .= "<br />";
		$str .= "<br />";
		$str .= "<input type='checkbox' name='position_page' value='top' onchange=\"change_preview()\" /> Top";
		$str .= "<input type='checkbox' name='position_page' value='body' onchange=\"change_preview()\" /> Body";
		$str .= "<input type='checkbox' name='position_page' id='bottom' value='bottom' onchange=\"change_preview()\" /> Bottom";
		$str .= "</form>";
		$str .= "<br />";
		$str .= "<br />";
		$row1 = $change->query_setting();
		if($row1!=''){
			foreach($row1 as $values){
				$str .= "<div style='width:250;'>Page and color : ".$values['page']."<div class='color2' style='background-color:$values[color];'></div></div><br />";
			}
		}		
		echo $str;
		return;
	}
}

?>
<style>
	#block{
		display:block;
		width:800px;
	}
	.color{
		width:20px;
		height:20px;
		border:1px solid #000000;
		float:left;
		cursor:pointer;	
	}
	.color2{
		width:20px;
		height:20px;
		border:1px solid #000000;	
	}
	.change{
		background:#CCCCCC;
		color:#FF0000;
		font-size:14px;
		height:20px;
		line-height:20px;
		text-align:center;
		width:100px;
		cursor:pointer;
	}
</style>
<link rel="stylesheet" href="../js/jquery/fancybox/jquery.fancybox-1.2.6.css" type="text/css" media="screen"/>
<script language="javascript" src="../js/jquery/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery/fancybox/jquery.fancybox-1.2.6.pack.js"></script>
<?php
	$color = new Color;
	$row = $color->query();
	$count = 0;
	echo "<div id='block'>";
	foreach($row as $value){
		
		echo "<div class='color' id='$value[id]' style='background-color:$value[color]; ' title='$value[color]' onclick='choosepage($value[id])'>&nbsp;</div>";
	}
	echo "</div><br />";
?>
<div id="link" style="clear:left;"></div>
<div id="preview" ></div>
<script>
jQuery(document).ready(function(){

});
function choosepage(id){
	jQuery.post("changetemplate.php",{id_set:id,act:'choose_page'},function(response){
		jQuery("#link").html(""+response+"");
		jQuery("#preview").html("<!-- -->");
	});		
}

function change_preview(){
		var page = document.f_page.page.value;
		var position_page = document.f_page.position_page.value;
		var color = jQuery("#color").val();
		var str = '';
		if(page=='view'){
			document.getElementById("bottom").disabled = true;
		}
		for(var i = 0 ; i < document.f_page.position_page.length;i++)
		{
			if(document.f_page.position_page[i].checked){
				var position_page = document.f_page.position_page[i].value;
				str += "<a href='data_template.php?idcolor="+color+"&page="+page+"&position="+position_page+"' class='iframe' >Preview "+page+" "+position_page+"</a><br />";
			}		
		}
		if(str !=''){
			str += "<br /><br /><div onclick='changemain("+color+")' class='change'>Agree Change</div></p>";
			jQuery("#preview").html(str);
			jQuery("a.iframe").fancybox({
					'frameWidth': 	1000,
					'frameHeight': 	500
				});
		}
		else{
			jQuery("#preview").html("<!-- -->");
		}		
}
function changemain(id){
	if(window.confirm("Do you agree change? It will be change in main website")){
		jQuery.post("changetemplate.php",{id_set:id,act:'changemain'},function(response){
			alert('Success');
		});
	}
}

</script>