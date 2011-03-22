var mapIcons = new Array();
mapDir = "../images/gmap/icons/";
mapIcons["others"] = "marker.png";
mapIcons["restaurant"] = "restaurant.png";
mapIcons["beach"] = "beach.png";
mapIcons["bridge"] = "bridgemodern.png";
mapIcons["shop"] = "shoppingmall.png";
mapIcons["bank"] = "bank.png";
mapIcons["hotel"] = "hotel.png";

function createStaticMarker(point, cat, des) {
	var vnwkIcon = new GIcon();
	vnwkIcon.image = mapDir + mapIcons[cat];
	vnwkIcon.iconSize = new GSize(32, 37);
	vnwkIcon.shadowSize = new GSize(0, 0);
	vnwkIcon.iconAnchor = new GPoint(10, 24);
	vnwkIcon.infoWindowAnchor = new GPoint(20, 10); 	
	
    var marker = new GMarker(point, {icon: vnwkIcon, draggable: false});
    if (des != "") {
	    GEvent.addListener(marker, "click", function(des) {
			marker.openInfoWindowHtml(des);
	    });
    }
    return marker;
}
