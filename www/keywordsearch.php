<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>trailmaker.org</title>
    <script src="http://maps.google.com/maps/api/js?sensor=true"
            type="text/javascript"></script>
      <script src="scriptaculous-js-1.9.0/lib/prototype.js" type="text/javascript"></script>
  <script src="scriptaculous-js-1.9.0/src/scriptaculous.js" type="text/javascript"></script>        
            
    <script type="text/javascript">
    //<![CDATA[
    var map;
    var markers = [];
    var infoWindow;
    var locationSelect;

    function load() {
	// called to start page
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(40, -100),
        zoom: 12,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
	  
	
      infoWindow = new google.maps.InfoWindow();
	  overlay = new google.maps.OverlayView(); 
overlay.draw = function() {}; 
overlay.setMap(map); 
	 
// marker options will go here 

  if (navigator.geolocation) 
{

//we have current position
$('navstatus').appear();
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
		
		<?php 
	  if (isset($_REQUEST['q'])) { ?>
	  // they searched for something
	  searchLocationsAbout("<?php echo ($_GET["q"]);  ?>",position.coords.latitude,position.coords.longitude);
	  

	  <?php }
	  
	  ?>
$('navstatus').hide();
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
					alert ('Timeout');
					break;
				case error.POSITION_UNAVAILABLE:
					alert ('Position unavailable');
					break;
				case error.PERMISSION_DENIED:
					alert ('Permission denied');
					break;
				case error.UNKNOWN_ERROR:
					alert ('Unknown error');
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
	  
   }
	function centerLocal() {

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
					alert ('Timeout');
					break;
				case error.POSITION_UNAVAILABLE:
					alert ('Position unavailable');
					break;
				case error.PERMISSION_DENIED:
					alert ('Permission denied');
					break;
				case error.UNKNOWN_ERROR:
					alert ('Unknown error');
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
         alert(address + ' not found');
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
							  mapTypeId: google.maps.MapTypeId.ROADMAP
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
					alert ('Timeout');
					break;
				case error.POSITION_UNAVAILABLE:
					alert ('Position unavailable');
					break;
				case error.PERMISSION_DENIED:
					alert ('Permission denied');
					break;
				case error.UNKNOWN_ERROR:
					alert ('Unknown error');
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
     var searchUrl = 'phpsqlsearch_genxml.php?lat=' + lat + '&lng=' + lon + '&radius=' + radius;
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
  
  
    function searchLocationsAbout(keyword,lat,lon) {
     clearLocations(); 

     var radius = document.getElementById('radiusSelect').value;
     var searchUrl = 'phpsqlkeywordsearch_genxml.php?keyword=' + keyword +  '&lat=' + lat + '&lng=' + lon + '&radius=' + radius;
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
  
  
    function createMarker(latlng, name, address,video,picture,audio,markerID) {
		 var icon = new google.maps.MarkerImage("http://trailmaker.org/" + markerID);
		 
      var html = "<b>" + name + "</b> </br>" + address;
	  
	  if ((video.length) > 0) {
		html = html + "<video width=200 controls=\"controls\" src="+video.substring(3) +"></video><br />";  
	  }
	  if ((audio.length) > 0) {
		html = html + "<audio controls=\"controls\" src="+audio.substring(3) +"></audio><br />";  
	  }
	    if ((picture.length) > 0) {
		html = html + "<img width=200 src="+picture.substring(3) +" /><br />";  
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
img.src = 'http://maps.googleapis.com/maps/api/staticmap?center=' + map.getCenter().lat() + ',' + map.getCenter().lng() +'&zoom=13&size=2400x1200&maptype=roadmap&sensor=false';
			localStorage.setItem("background", img);
	var allMarkers = [];
// Create some markers
   for(var i=0; i < markers.length; i++){
	  
var point = 
map.fromLatLngToContainerPixel(markers[i].latLng);
      allMarkers.push(markers[i] + "|Test|" + point.x + "|" + point.y);
    }    
			localStorage.setItem("markers", allMarkers);
		
	}
	
	
	
	function loadMap() {
		//hide the Google map
		
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
  </head>
  <body style="margin:0px; padding:0px;" onLoad="load();"> 
    <div style="display:none">
<input type="button" onClick="searchLocal()" value="Show items around me" style="visibility:hidden"/>
     <input type="text" id="addressInput" size="10" style="visibility:hidden"/>
    <select id="radiusSelect" style="visibility:hidden">
      <option value="25" selected>25mi</option>
      <option value="100">100mi</option>
      <option value="200">200mi</option>
    </select>
    <input type="button" onClick="searchLocations()" value="Search" style="visibility:hidden"/>
    <select id="mapType" style="visibility:hidden">
      <option value="roadmap" selected>Roadmap</option>
      <option value="satellite ">Satellie</option>
      <option value="terrain">Terrain</option>
         <option value="hybrid">Hybrid</option>
    </select>
</div>
  <div><select id="locationSelect" style="width:100%;visibility:hidden"></select></div>
    <div id="map" style="width: 100%; height: 80%"></div>  <input type="button" onClick="saveMap()" value="Save Current Map"  style="visibility:hidden"/>
    <input type="button" onClick="loadMap()" value="Load Saved Map"  style="visibility:hidden"/>
    
    <input type="button" onClick="showLocalStorage()" value="Show Local Storage"  style="visibility:hidden"/>
  <div id="navstatus" style="display:none; width:80%; height:80px; background:#c2defb; border:1px solid #333;">Getting Location...</div>
  </body>
</html>