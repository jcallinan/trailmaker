<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>trailmaker.org</title>
    <script src="http://maps.google.com/maps/api/js?sensor=true"
            type="text/javascript"></script>
        
    <script type="text/javascript">
	function gup( name ){  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  var regexS = "[\\?&]"+name+"=([^&#]*)";  var regex = new RegExp( regexS );  var results = regex.exec( window.location.href );  if( results == null )    return "";  else    return results[1];}

	LayerControl.prototype.status_ = null;
	LayerControl.prototype.layer_ = null;

// Define setters and getters for this property
LayerControl.prototype.getLayer = function() {
  return this.layer_;
}

LayerControl.prototype.setLayer = function(layer) {
  this.layer_ =  layer;
}

LayerControl.prototype.getStatus = function() {
  return this.status_;
}

LayerControl.prototype.setStatus = function(status) {
  this.status_ =  status;
}
		function LayerControl(map, div,layer, status,color) {

  // Get the control DIV. We'll attach our control UI to this DIV.
  var controlDiv = div;

  // We set up a variable for the 'this' keyword since we're adding event
  // listeners later and 'this' will be out of scope.
  var control = this;

  // Set the home property upon construction.
  control.status_ = status;
  control.layer_ = layer

  // Set CSS styles for the DIV containing the control. Setting padding to
  // 5 px will offset the control from the edge of the map.
  controlDiv.style.padding = '5px';

  // Set CSS for the control border.
  var AEUI = document.createElement('div');
   AEUI.style.backgroundColor = 'white';
  AEUI.style.borderStyle = 'solid';
  AEUI.style.borderWidth = '2px';
  AEUI.style.borderColor = '#1e8fa7';
  AEUI.style.color = color;
  AEUI.style.cursor = 'pointer';
  AEUI.style.textAlign = 'center';
  AEUI.title = 'Click to turn on/off ' + layer.replace(/_/g, " ");
 
  controlDiv.appendChild(AEUI);

  // Set CSS for the control interior.
  var aeText = document.createElement('div');
   aeText.style.fontFamily = 'Arial,sans-serif';
  aeText.style.fontSize = '12px';
  aeText.style.paddingLeft = '4px';
  aeText.style.paddingRight = '4px';
				   aeText.innerHTML = layer.replace(/_/g, " "); 
				   AEUI.style.backgroundColor = 'white';
					  AEUI.style.color = color;
				
  control.setStatus(true);
  AEUI.appendChild(aeText);




  // Setup the click event listener for Home:
  // simply set the map to the control's current home property.
  google.maps.event.addDomListener(AEUI, 'click', function() {
   //TODO - search for AE markers and turn on/off
   var currentStatus = control.getStatus();
   
		  if (currentStatus) {
	control.setStatus(false)	
	} else {
	control.setStatus(true);	
	}	
				   if (currentStatus) {
					   aeText.innerHTML = layer.replace(/_/g, " "); 
						   AEUI.style.backgroundColor = color;
							  AEUI.style.color = '#ffffff';
							  AEUI.style.borderColor = '#ffffff';
				   } else {
						aeText.innerHTML = layer.replace(/_/g, " ") ; 
									 AEUI.style.backgroundColor = '#ffffff';
										AEUI.style.color = '#663332';
										 AEUI.style.borderColor = color;
				   }
  
	  
  
						for (var i=0; i<markers.length; i++){
							var mImage = markers[i].getIcon().url;
							if (mImage.match(control.getLayer() +".png") != null) {
							if (currentStatus) {
								markers[i].setMap(map);
							
						
							} else {
								markers[i].setMap(null);
								
							}
			}
			
}
  
   
  
   
  });

AEUI.click();  
}

	
    //<![CDATA[
    var map;
    var markers = [];
    var infoWindow;
    var locationSelect;

    function load() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(41.95377, -78.70227),
        zoom: 12,
        mapTypeId: 'hybrid',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
	  //41.95377, -78.70227
      infoWindow = new google.maps.InfoWindow({ maxWidth: 325 });
	  overlay = new google.maps.OverlayView(); 
overlay.draw = function() {}; 
overlay.setMap(map); 
	<?php  
require("phpsqlsearch_dbinfo.php");


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
$query = ("SELECT * from categories");
$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

$count = 0;
while ($row = @mysql_fetch_assoc($result)){
	$count++;
	$cat = $row['category'];
	$color = $row['color'];
	$cat_clean = str_replace(' ','_',$cat);
?>
 var layerControlDiv<?php print($cat_clean); ?> = document.createElement('div<?php print($cat_clean); ?>');
 
  var layerControl<?php print($cat_clean); ?> = new LayerControl(map, layerControlDiv<?php print($cat_clean); ?>, "<?php print($cat_clean); ?>",true,"#<?php print($color); ?>");

  layerControlDiv<?php print($cat_clean); ?>.index = <?php print($count); ?>;
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(layerControlDiv<?php print($cat_clean); ?>);	
  	<?php } ?>
   
	 
// marker options will go here 

  if (navigator.geolocation) 
{
	navigator.geolocation.getCurrentPosition( 
 
		function (position) {  
 
		// Did we get the position correctly?
		// alert (position.coords.latitude);
 
		// To see everything available in the position.coords array:
		// for (key in position.coords) {alert(key)}
      var center = new google.maps.LatLng(
            position.coords.latitude,
           position.coords.longitude
        );
        var zoom = 14;

        map.setCenter(center, zoom);
 		var marker = new google.maps.Marker({
							    position: center, 
							    map: map, 
							    title:"Your location"
							});
		}, 
		// next function is the error callback
		function (error)
		{
			switch(error.code) 
			{
				case error.TIMEOUT:
					//alert ('Timeout');
					break;
				case error.POSITION_UNAVAILABLE:
					//alert ('Position unavailable');
					break;
				case error.PERMISSION_DENIED:
					//alert ('Permission denied');
					break;
				case error.UNKNOWN_ERROR:
					//alert ('Unknown error');
					break;
			}
		}
		);
	}

      locationSelect = document.getElementById("locationSelect");
      locationSelect.onchange = function() {
        var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        if (markerNum != "none"){
          google.maps.event.trigger(markers[markerNum], 'click');
        }
      };
	  searchLocal()
	  //done loading
   }
	function centerLocal() {
 //alert('cl');
    var map = new google.maps.Map(document.getElementById("map"));
   
     if (navigator.geolocation) 
{
	navigator.geolocation.getCurrentPosition( 
 
		function (position) {  
 
		// Did we get the position correctly?
		// alert (position.coords.latitude);
 
		// To see everything available in the position.coords array:
		// for (key in position.coords) {alert(key)}
      var center = new google.maps.LatLng(
            position.coords.latitude,
           position.coords.longitude
        );
        var zoom = 8;

        map.setCenter(center, zoom);
 
		}, 
		// next function is the error callback
		function (error)
		{
			switch(error.code) 
			{
				case error.TIMEOUT:
					//alert ('Timeout');
					break;
				case error.POSITION_UNAVAILABLE:
					//alert ('Position unavailable');
					break;
				case error.PERMISSION_DENIED:
					//alert ('Permission denied');
					break;
				case error.UNKNOWN_ERROR:
					//alert ('Unknown error');
					break;
			}
		}
		);
	}

   
  

	
	}
   function searchLocations() {
     var address = document.getElementById("addressInput").value;
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
        searchLocationsNear(results[0].geometry.location.lat(),results[0].geometry.location.lng());
       } else {
        // alert(address + ' not found');
       }
     });
   }
   function success(position) {							
							
 

							var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
							var myOptions = {
							  zoom: 15,
							  center: latlng,
							  mapTypeControl: false,
							  navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
							  mapTypeId: google.maps.MapTypeId.HYBRID
							};
							
							    
							
							var map = new google.maps.Map(document.getElementById("map"), myOptions);

							var marker = new google.maps.Marker({
							    position: latlng, 
							    map: map, 
								
							    title:"Your location"
							});
							
							
   }
   function error(msg) {
							$('#status').text = typeof msg == 'string' ? msg : "error";							
						}
						
						
  function searchLocal() {
     var address = document.getElementById("addressInput").value;
     var geocoder = new google.maps.Geocoder();
	 
	
   
     if (navigator.geolocation) 
{
	navigator.geolocation.getCurrentPosition( 
 
		function (position) {  
 
		// Did we get the position correctly?
		// alert (position.coords.latitude);
 
		// To see everything available in the position.coords array:
		// for (key in position.coords) {alert(key)}
 
		searchLocationsNear(position.coords.latitude,position.coords.longitude);
 
		}, 
		// next function is the error callback
		function (error)
		{
			switch(error.code) 
			{
				case error.TIMEOUT:
					//alert ('Timeout');
					break;
				case error.POSITION_UNAVAILABLE:
					//alert ('Position unavailable');
					break;
				case error.PERMISSION_DENIED:
					//alert ('Permission denied');
					break;
				case error.UNKNOWN_ERROR:
					//alert ('Unknown error');
					break;
			}
		}
		);
	}

      
    
   }
   function clearLocations() {
     infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;

     locationSelect.innerHTML = "";
     var option = document.createElement("option");
     option.value = "none";
     option.innerHTML = "See all results:";
     locationSelect.appendChild(option);
   }
 
   function searchLocationsNear(lat,lon) {
     clearLocations(); 

     var radius = document.getElementById('radiusSelect').value;
	// getRelevantPaths(lat,lon,radius);
     var searchUrl = 'phpsearch_genxml.php?lat=' + lat + '&lng=' + lon + '&radius=' + radius;
     downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("name");
         var address = markerNodes[i].getAttribute("address");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));

         createOption(name, distance, i);
         createMarker(latlng, name, address,markerNodes[i].getAttribute("video"),markerNodes[i].getAttribute("picture"),markerNodes[i].getAttribute("audio"),markerNodes[i].getAttribute("icon"));
         bounds.extend(latlng);
       }
       map.fitBounds(bounds);
       locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
         google.maps.event.trigger(markers[markerNum], 'click');
       };
      });
    }
	function keywordSearchLocationsNear(lat,lon) {
     clearLocations(); 

     var radius = document.getElementById('radiusSelect').value;
	// getRelevantPaths(lat,lon,radius);
     var searchUrl = 'phpsqlkeywordsearch_genxml.php?lat=' + lat + '&lng=' + lon + '&radius=' + radius + '&keyword=' + document.getElementById('addressInput').value;
     downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("name");
         var address = markerNodes[i].getAttribute("address");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));

         createOption(name, distance, i);
         createMarker(latlng, name, address,markerNodes[i].getAttribute("video"),markerNodes[i].getAttribute("picture"),markerNodes[i].getAttribute("audio"),markerNodes[i].getAttribute("icon"));
         bounds.extend(latlng);
       }
       map.fitBounds(bounds);
       locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
         google.maps.event.trigger(markers[markerNum], 'click');
       };
      });
    }
  	function getRelevantPaths(lat,lon,radius) {
	var searchUrl = 'phpsqlsearch_genxmlforpaths.php?lat=' + lat + '&lng=' + lon + '&radius=' + radius;
	downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("pathmark");
       var bounds = new google.maps.LatLngBounds();
	   var arrayOfPoints =[];
       for (var i = 0; i < markerNodes.length; i++) {
         var trailid = markerNodes[i].getAttribute("trailid");
      
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));
		arrayOfPoints.push(latlng);
         
         //createMarker(latlng, name, address,markerNodes[i].getAttribute("video"),markerNodes[i].getAttribute("picture"),markerNodes[i].getAttribute("audio"),markerNodes[i].getAttribute("icon"));
         bounds.extend(latlng);
       }
	   //alert(arrayOfPoints);
	   var trailpath = new google.maps.Polyline({
	   path: arrayOfPoints,
	   strokeColor: "#606060",
	   strokeOpacity: 1.0,
	   strokeWeight: 5
	   });
	   trailpath.setMap(map);
       map.fitBounds(bounds);
       locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
         google.maps.event.trigger(markers[markerNum], 'click');
       };
      });
	}
    function createMarker(latlng, name, address,video,picture,audio,markerID) {
		 var icon = new google.maps.MarkerImage("http://trailmaker.org/" + markerID);
		 
      var html = "<b>" + name + "</b> </br>" + address;
	  
	  if ((video.length) > 0) {
		html = html + "<video width=200 controls=\"controls\" src=\""+video.substring(3) +"\"></video><br />";  
	  }
	  if ((audio.length) > 0) {
		html = html + "<audio controls=\"controls\" src=\""+audio.substring(3) +"\"></audio><br />";  
	  }
	    if ((picture.length) > 0) {
		html = html + "<img width=200 src=\""+picture.substring(3) +"\" /><br />";  
	  }
      var marker = new google.maps.Marker({
        map: map,
        position: latlng,
		icon: icon
      });
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
      markers.push(marker);
    }

    function createOption(name, distance, num) {
      var option = document.createElement("option");
      option.value = num;
      option.innerHTML = name + "(" + distance.toFixed(1) + ")";
      locationSelect.appendChild(option);
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}
	function saveMap() {
		//get the current map by center
		
		//get all markers and save them to local storage
		//markers are set by X, Y
		localStorage.setItem("lat", map.getCenter().lat());
		localStorage.setItem("lng", map.getCenter().lng());
		var img = new Image();
img.id="localStorageMap";
	var allMarkers = [];
// Create some markers
var markersPart = "";

   for(var i=0; i < markers.length; i++){
   //(41.898056,%20-78.57)
   if (i < 21) {
	  markersPart = markersPart + "color:0xFFFFC0%7Clabel:" + i + "%7C" + markers[i].position.toString().replace("(","").replace(" ","").replace(")","") +"&markers=" ;
   }
//var point = 
//map.fromLatLngToContainerPixel(markers[i].latLng);
   //   allMarkers.push(markers[i] + "|Test|" + point.x + "|" + point.y);
//	  document.write(markers[i] + "|Test|" + point.x + "|" + point.y);
    }    
	//		localStorage.setItem("markers", allMarkers);
	
	// var urlForMap = 'http://maps.googleapis.com/maps/api/staticmap?center=' + map.getCenter().lat() + ',' + map.getCenter().lng() +'&zoom=13&size=2400x1200&maptype=roadmap&sensor=false&markers=' + markersPart;
	
	var urlForMap = 'http://maps.googleapis.com/maps/api/staticmap?size=2400x1200&maptype=roadmap&sensor=false&markers=' + markersPart;
	imgForSaveMap.src =  urlForMap;
	var saveMapBox = document.getElementById('saveMapURL');
	saveMapBox.value = urlForMap;
			localStorage.setItem("background", img);	
			document.body.appendChild( img );
			var dmap = document.getElementById("map");
dmap.style.visibility = "hidden";
	}
	
	
	
	function loadMap() {
		//hide the Google map
var dmap = document.getElementById("map");
dmap.style.visibility = "hidden";
var themap = new Image();
themap = localStorage.setItem("background", img);
document.body.appendChild(themap);
		//make a new div, then the image inside it
		
		//then add markers as offset from the image
	}
	function showLocalStorage() {
		    var map = new google.maps.Map(document.getElementById("map"));

		var localStuff;
		for(var i=0, len=localStorage.length; i<len; i++) {
    var key = localStorage.key(i);
    var value = localStorage[key];
 localStuff = localStuff + "<br>" + key + " => " + value;
}

  var divTag = document.createElement("div");
          
            divTag.id = "divLocal";
             
            divTag.setAttribute("align","center");
             
            divTag.style.margin = "0px auto";
             
         
             
            divTag.innerHTML = localStuff;

            document.body.appendChild(divTag);


	}
    //]]>
  </script>
  <style>
  #map {
	  -webkit-touch-callout: none;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
	  
  }
  </style>
  
  </head>
  <body style="margin:0px; padding:0px;background-color:#8A8A86;" onLoad="load();"> 
   
   <base target="_blank">
   <table style="width:100%"><tr><td style="width:10%"> <img src="ApplicationIcon.png" width="25" hspace="4" vspace="4" onclick='document.location.reload()' >





              </td><td style="width:70%"> <input type="text" id="addressInput" style="width:100%;background-color:#D0D0CF" /></td><td style="width:20%"><input type="button" onClick="searchLocations()" value="Search"/></td></tr></table>
   
    <div><select id="locationSelect" style="width:100%;visibility:hidden"></select></div>  <div id="map" style="width: 100%; height: 95%;border:thin #666666 solid"></div> 
  <!--input type="button" onClick="saveMap()" value="Save Current Map"/>
    <!--input type="button" onClick="loadMap()" value="Load Saved Map"/-->
    <!--input type="text" id="saveMapURL" size="10"/>
    <input type="button" onClick="showLocalStorage()" value="Show Local Storage"/>-->
  <img id="imgForSaveMap" />
    <input type="button" style="visibility:hidden;height:1px;width:1px" onClick="searchLocal()" value="Show stuff around me"/>
    
    <select id="radiusSelect" style="visibility:hidden;height:1px;width:1px">
      <option value="25" selected>25mi</option>
      <option value="100">100mi</option>
      <option value="200">200mi</option>
    </select>
  
  
  </body>
</html>