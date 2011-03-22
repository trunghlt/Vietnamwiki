<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/ImageElement.php");
$currentImageElement = new ImageElement();
$currentImageElement->id = ImageElement::filterId($_GET["ie_id"]);
$currentImageElement->query();
$currentImageElement->destId = ImageElement::filterDestId($_GET["ie_loc"]);
$currentImageElement->description = ImageElement::filterDescription($_GET["ie_des"]);
$currentImageElement->tags = ImageElement::filterTags($_GET["ie_tags"]);
$currentImageElement->save();
$dest_id = $currentImageElement->filterDestId($_GET["dest_id"]);
$page = $_GET["page"];
include("../imageListPainter.php");
?>