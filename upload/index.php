<html>
<head>
<title>Asynchronous image file upload without AJAX</title>
<style>

iframe {
	border-width: 0px;
	height: 60px;
	width: 380px;
}
iframe.hidden {
	visibility: hidden;
	width:0px;
	height:0px;
}

#main {
	overflow: hidden;
	margin: auto;
	width: 380px;
	height: 200px;
	border-style: solid;
	border-width: 1px;
	background-color: white;
}

#footer {
	margin: auto;
	width: 410px;
	height: 20px;
	border-style: solid;
	border-width: 1px;
	border-top-width: 0px;
	background-color: white;
	text-align: center;
	font-family: verdana;
	font-size: 80%;
}

#images {
	width: 390px;
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
</html>
<body><center>
<!--SERCURITY NOTE-->
<form>
<input style="visibility: hidden; display:none;" id="tmp_file_name" name="tmp_file_name" type="text" value="" />
</form>
<div id="main">
<div id="iframe_container">
<iframe src="upload/upload.php" frameborder="0"></iframe>
</div>
<div id="images_container">
<div id="image">
</div>
</div>
</center></body>
</html>