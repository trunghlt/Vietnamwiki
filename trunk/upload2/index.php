<style>

iframe {
	height:50px;
}


iframe.hidden {
	visibility: hidden;
	width:0px;
	height:0px;
}


#images {
	width: 190px;
	margin: 20px;
}

#images div {
	margin: 10px;
	width: 100px;
	height: 100px;
	border-style: solid;
	border-width: 5px;
	border-color: #DEDFDE;
	float: left;
	overflow: hidden;
}

#images div:hover {
	border-color: #529EBD;
}

#images img.load {
	margin: 36px;
}


</style>
<center>
	<input style="visibility: hidden; display:none;" id="tmp_file_name" name="tmp_file_name" type="text" value="" />
	<div id="main">
		<div id="iframe_container">
			<iframe src="upload2/upload.php" frameborder="0"></iframe><br />
		</div>
		<div id="iframe_container1">Upload By Url:
			<iframe src="upload2/upload_url.php" frameborder="0"></iframe><br />
		</div>
		<div id="images_container">
			<div id="image"></div>
		</div>
	</div>
</center>
