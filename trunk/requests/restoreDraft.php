<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
include("../core/classes/Edition.php");

$clean["editionId"] = Edition::filterId($_REQUEST["editionId"]);
$currentEdition = new Edition;
$currentEdition->query($clean["editionId"]);
$currentEdition->restore();
?>