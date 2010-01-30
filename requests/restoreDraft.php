<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/Edition.php");
include("../core/classes/PostElement.php");
if(isset($_REQUEST["editionId"]) && $_POST["type"]==1){
	$clean["editionId"] = Edition::filterId($_REQUEST["editionId"]);
	$currentEdition = new Edition;
	$currentEdition->query($clean["editionId"]);
	$currentEdition->restore();
}
else if(isset($_REQUEST["editionId"]) && $_POST["type"]==2){
	$clean["editionId"] = Edition::filterId($_REQUEST["editionId"]);
	$currentEdition = new Edition;
	$currentEdition->query($clean["editionId"]);
	$currentEdition->restore(2);
}
?>