<?php  
require("phpsqlsearch_dbinfo.php");

// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a mySQL server
$connection=mysql_connect ("localhost", $username, $password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active mySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}

// Search the rows in the markers table
$query = sprintf("SELECT paths.*, ( 3959 * acos( cos( radians('%s') ) * cos( radians( markers.lat ) ) * cos( radians( markers.lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( markers.lat ) ) ) ) AS distance, trails.markerID FROM markers,trails,paths where paths.trailid = markers.trailid and markers.trailid = trails.trailid and paths.lat != \"0\" and paths.lng != \"0\"  HAVING distance < '%s' ORDER BY distance LIMIT 0 , 200",
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius));
$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  $node = $dom->createElement("pathmark");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("trailid", $row['trailid']);
  $newnode->setAttribute("lat", $row['lat']);
   $newnode->setAttribute("lng", $row['lng']);
  
 
  /*  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(41.8740854, -78.6549828),
    icon: 'marker.png',
	map: map
});*/

}

echo $dom->saveXML();
?>
