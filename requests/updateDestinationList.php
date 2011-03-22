<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/IndexElement.php");

function filterDestId($destId) {
	return $destId;
}

$destination = filterDestId($_POST["newDestId"]);
$index_id =  IndexElement::filterId($_POST["newIndexId"]);
$photo = 0;
include("../destinationPainter.php");
?>