<?php

$q = strtolower($_GET["q"]);
if (!$q) return;
$items = array(
"Vietnam",
"Sapa",
"Ha Noi",
"Ha Long",
"Phong Nha",
"Ke Bang",
"Hue",
"Da Nang",
"Hoi An",
"My Son",
"Nha Trang",
"Da Lat",
"Mui Ne",
"Vung Tau",
"Ho Chi Minh",
"Mekong",
"Phu Quoc",
"Dien Bien Phu",
"Cuc Phuong",
"Hung King",
"Yok Don"
);

foreach ($items as $value) {
	if (strpos(strtolower($value), $q) !== false) {
		echo $value."\n";
	}
}

?>