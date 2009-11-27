<?php
	include('core/common.php');
	include('core/init.php');
	include('core/filters.php');
	include('core/classes/Db.php');
	include('core/classes/PostElement.php');
	include("core/classes/IndexElement.php");	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<script type="text/javascript" src="js/core.js"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<link type="text/css" href="css/dialog.css" rel="stylesheet" />
</head>

<body>
<script language="javascript">
	tinyMCE.init({
				// General options
				mode : "exact",
				elements: "contentTextarea",
				theme : "advanced",
				height : "400",
				width: "100%",
				plugins : "googlemaps,safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
			
				// Theme options
				theme_advanced_buttons1 : "insertlayer,pagebreak,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,googlemaps, googlemapsdel",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,|,print,preview",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_resizing : false,
			
				// Example content CSS (should be your site CSS)
				content_css : "/css/topic-content.css",

				//add iframe element
				extended_valid_elements : "iframe[src|width|height|name|align][frameborder][scrolling][marginheight][marginwidth]",
			
				// Drop lists for link/image/media/template dialogs
				template_external_list_url : "js/template_list.js",
				external_link_list_url : "js/link_list.js",
				external_image_list_url : "js/image_list.js",
				media_external_list_url : "js/media_list.js",
			
				// Replace values for the template plugin
				template_replace_values : {
					username : "Some User",
					staffid : "991234"
				}
			});
</script>	
<?php 
$editting = isset($_GET["id"]);
if ($editting) {
	$postId = postIdFilter($_GET["id"]);
	$currentPostElement =  new PostElement;
	$currentPostElement->query($postId);
	$indexElement = new IndexElement();
	$indexElement->query($currentPostElement->indexId);
	$destId = $indexElement->destId;	
}
else {
	$destId = filterDestId($_GET["destId"]);
}

function filterDestId($destId) {
	return $destId;	
}
?>

<form>
  <div style="float: left; margin-right: 10px;">
		<b><label>Location:</label></b> <br/>
		<div id="loc" name="loc">
		<select name="location" id="location" onchange="javascript:update_dest()">
			<?php			
				$sql = "SELECT *
						FROM destinations
						ORDER BY ord";
				$result = mysql_query($sql) or die(mysql_error());
				$numrow = mysql_num_rows($result);
				$n = 0;
				while ($row = mysql_fetch_array($result)) {
					$n++;
					if (($n < $numrow)) {
						if ($row["id"] != $destId) {
							echo '<option value="'. $row['id'] . '">'. $row['EngName'] . '</option>';
						}
						else { 
							echo '<option value="'. $row['id'] . '" selected="yes">'. $row['EngName'] . '</option>';
						}					
					}
				}
				mysql_free_result($result);
			?>
		</select>
		<script language="javascript">
			function update_dest() {
				var request = new Request({	url: "requests/updateIndexList.php"});
				request.send("locId="+$("location").value);
				request.addEvent("onComplete", function(response){
					$("index_div").set('html', response);
				});
			}
		</script> 
		</div>
	</div>
	<div>
		<b><label>Index:</label></b>
		<div id='index_div' name='index_div'>
			<select id="index" name="index">
				<?php
					$q = new Db;
					$q->query("	SELECT *
								FROM index_menu
								WHERE dest_id = $destId");
					while ($r = mysql_fetch_array($q->re)) {
						if ($r["locked"]==0) {
							?>
							<option value="<?php echo $r["id"]?>" <?php
								if($editting) {
									if  ($r["id"]== $indexElement->id) {
										?>selected="yes"<?php 
									}
								}
							?>>
							<?php echo $r["name"]?>
							</option>
							<?php
						} 
					}
				?>
			</select>
		</div>
	</div>
	
	<p>
	<b><label>Entry Title:</label></b> <br/>
    <input style="width:100%" type="text" name="title" id="title" value="<?php if ($editting) echo $currentPostElement->title?>"/>
	</p>
	
	<div style="float: left; margin-right: 10px;">
	<b><label>Small image's URL:</label></b>
	<input type="text" size=36 name="smallImgURL" id="smallImgURL" value="<?php if ($editting) echo $currentPostElement->smallImgURL?>"/>
	</div>
	
	<div>
	<b><label>Big image's URL:</label></b>
	<input type="text" size=36 name="bigImgURL" id="bigImgURL" value="<?php if ($editting) echo $currentPostElement->bigImgURL?>"/>
	</div>
	
	
	<p>	
	<b><label> Summary/Recommendation:</label></b><br />
	<span class="style1">This will appear as the summary of the topic. This should not exceed 500 characters</span>.<br/>
    <textarea name="summary" id="summary" style="width:100%" onKeyDown="remainChar()"><?php 
    	if ($editting) echo $currentPostElement->summary;
    ?></textarea><br/>
	<span class="style1" id="rc">500 characters left</span>
	</p>
	<script language="JavaScript" type="text/javascript">
		function remainChar() {
				if (document.getElementById("summary").value.length > 500) {
					document.getElementById("summary").value = document.getElementById("summary").value.substring(0,500);
				}
			document.getElementById("rc").innerHTML = 500 - document.getElementById("summary").value.length + " characters left";
		}
	</script>

	<textarea name="contentTextarea" id="contentTextarea" style="width:100%"><?php
		if ($editting) {
			$content = htmlspecialchars_decode($currentPostElement->content, ENT_QUOTES);
			$content = str_replace("|", "&", $content);		 
			$content = str_replace('\"', '"', $content);
			$content = str_replace("\'", "'", $content);
			
			echo $content;
		}
	?></textarea>
</form>

</body>
</html>
