<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/ImageElement.php");

$currentImageElement = new ImageElement();
$currentImageElement->id = ImageElement::filterId($_POST["id"]);
$currentImageElement->query();
$mz_path = MEDIUM_UPLOAD_FOLDER . $currentImageElement->fileName;
$oz_path = UPLOAD_FOLDER . $currentImageElement->fileName;						
$imgPath =(file_exists($mz_path))? $mz_path : $oz_path;
include("../photoEditFormPainter.php");
?>