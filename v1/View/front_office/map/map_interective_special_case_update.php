<!DOCTYPE html>
<html>

<head>
  <title>Leaflet Map with Search</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <style>
    body {
      /* Style the body */
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    #map {
      /* Set the size of the map */
      margin: auto;
      width: 800px;
      height: 600px;
    }

    #search-container {
      /* Style the search container */
      display: none;
    }
  </style>
</head>

<?php
$updating = false;
$lng = 0;
$lat = 0;
$place = "";
if (isset($_GET['lng']) && isset($_GET['lat'])  && isset($_GET['place'])) {
  $lng = urldecode($_GET['lng']);
  $lat = urldecode($_GET['lat']);
  $place = urldecode($_GET['place']);
  $updating = true;
}
?>

<body>

  <div id="map"></div>
  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search for a place...">
  </div>

  <!-- Hidden inputs to store longitude and latitude -->
  <input type="hidden" id="latitude" name="latitude">
  <input type="hidden" id="longitude" name="longitude">
  <input type="hidden" id="place-name" name="place-name">

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
  
  <?php if (! $updating) { ?>
  <script>
    // Create a Leaflet map centered at latitude 51.505 and longitude -0.09, with zoom level 13
    var mymap = L.map('map').setView([36.8994900941884, 10.189252776301089], 13);

    // Add OpenStreetMap tiles to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
    }).addTo(mymap);

    search(); // Perform search

    add_marker(36.8994900941884, 10.189252776301089, "Esprit", false);

    function add_marker(lat, long, name, send=true) {
      var marker = L.marker([lat, long]).addTo(mymap);
      marker.bindPopup("<b>"+name+"</b>").openPopup();
      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = long;
      if (send){
        sendMessageToParent(long, lat, name);
      }
    }

    // Function to perform search
    function search() {
      var searchText = document.getElementById('search-input').value;
      // Perform search
      var searchControl = L.Control.geocoder({
        geocoder: L.Control.Geocoder.nominatim(),
      });
      searchControl.addTo(mymap);
    }


    // Function to handle click events on the map
    function onMapClick(e) {
      // Remove previous markers
      mymap.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
          mymap.removeLayer(layer);
        }
      });

      // Add a marker at the clicked location
      var marker = L.marker(e.latlng).addTo(mymap);

      // Update hidden input fields with latitude and longitude
      document.getElementById('latitude').value = e.latlng.lat;
      document.getElementById('longitude').value = e.latlng.lng;
      sendMessageToParent(e.latlng.lng, e.latlng.lat, 'Unknown place');

      // Reverse geocode the clicked location to get the name of the place
      // var geocoder = L.Control.Geocoder.nominatim();
      // geocoder.reverse(e.latlng, mymap.options.crs.scale(mymap.getZoom()), function(results) {
      //     var result = results[0];
      //     if (result) {
      //         // Add a marker at the clicked location
      //         var marker = L.marker(e.latlng).addTo(mymap);
      //         marker.bindPopup("<b>"+result.name+"</b>").openPopup();

      //         // Update hidden input fields with latitude, longitude, and place name
      //         document.getElementById('latitude').value = e.latlng.lat;
      //         document.getElementById('longitude').value = e.latlng.lng;
      //         //document.getElementById('place-name').value = result.name;

      //         sendMessageToParent(e.latlng.lng, e.latlng.lat, result.name);
      //     }
      // });


    }

    function onMapClick1(e) {
      // Remove previous markers
      mymap.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
          mymap.removeLayer(layer);
        }
      });

      // Add a marker at the clicked location
      var marker = L.marker(e.latlng).addTo(mymap);

      // Perform reverse geocoding to get the name of the place
      L.Control.Geocoder.nominatim().reverse(e.latlng, mymap.options.crs.scale(mymap.getZoom()), function (results) {
        // Update hidden input fields with latitude and longitude
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;

        // Display the name of the place
        if (results && results.length > 0) {
          var placeName = results[0].name;
          document.getElementById('latitude').value = e.latlng.lat;
          document.getElementById('longitude').value = e.latlng.lng;
          document.getElementById('place-name').value = placeName;

          sendMessageToParent(e.latlng.lng, e.latlng.lat, placeName);
        } else {
          //alert("No place name found for this location.");
          document.getElementById('latitude').value = e.latlng.lat;
          document.getElementById('longitude').value = e.latlng.lng;
          document.getElementById('place-name').value = 'Unknown place';
          sendMessageToParent(e.latlng.lng, e.latlng.lat, placeName);
        }
      });
    }


    function sendMessageToParent(lang, latd, place) {
      // Create a JSON object
      var jsonData = {
        message: 'the update location is :',
        data: {
          lng: lang,
          lat: latd,
          name: place
        }
      };

      // Send JSON data to the parent window
      window.parent.postMessage(jsonData, '*');
    }

    // Add click event listener to the map
    mymap.on('click', onMapClick);

  </script>
  <?php } else { ?>
    <script>
    // Create a Leaflet map centered at latitude 51.505 and longitude -0.09, with zoom level 13
    var mymap = L.map('map').setView([36.8994900941884, 10.189252776301089], 13);

    // Add OpenStreetMap tiles to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
    }).addTo(mymap);

    search(); // Perform search

    add_marker(<?= floatval($lat) ?>, <?= floatval($lng) ?>, "<?= $place ?>", false);

    function add_marker(lat, long, name, send=true) {
      var marker = L.marker([lat, long]).addTo(mymap);
      marker.bindPopup("<b>"+name+"</b>").openPopup();
      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = long;
      if (send){
        sendMessageToParent(long, lat, name);
      }
    }

    // Function to perform search
    function search() {
      var searchText = document.getElementById('search-input').value;
      // Perform search
      var searchControl = L.Control.geocoder({
        geocoder: L.Control.Geocoder.nominatim(),
      });
      searchControl.addTo(mymap);
    }


    // Function to handle click events on the map
    function onMapClick(e) {
      // Remove previous markers
      mymap.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
          mymap.removeLayer(layer);
        }
      });

      // Add a marker at the clicked location
      var marker = L.marker(e.latlng).addTo(mymap);

      // Update hidden input fields with latitude and longitude
      document.getElementById('latitude').value = e.latlng.lat;
      document.getElementById('longitude').value = e.latlng.lng;
      sendMessageToParent(e.latlng.lng, e.latlng.lat, 'Unknown place');

      // Reverse geocode the clicked location to get the name of the place
      // var geocoder = L.Control.Geocoder.nominatim();
      // geocoder.reverse(e.latlng, mymap.options.crs.scale(mymap.getZoom()), function(results) {
      //     var result = results[0];
      //     if (result) {
      //         // Add a marker at the clicked location
      //         var marker = L.marker(e.latlng).addTo(mymap);
      //         marker.bindPopup("<b>"+result.name+"</b>").openPopup();

      //         // Update hidden input fields with latitude, longitude, and place name
      //         document.getElementById('latitude').value = e.latlng.lat;
      //         document.getElementById('longitude').value = e.latlng.lng;
      //         //document.getElementById('place-name').value = result.name;

      //         sendMessageToParent(e.latlng.lng, e.latlng.lat, result.name);
      //     }
      // });


    }

    function onMapClick1(e) {
      // Remove previous markers
      mymap.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
          mymap.removeLayer(layer);
        }
      });

      // Add a marker at the clicked location
      var marker = L.marker(e.latlng).addTo(mymap);

      // Perform reverse geocoding to get the name of the place
      L.Control.Geocoder.nominatim().reverse(e.latlng, mymap.options.crs.scale(mymap.getZoom()), function (results) {
        // Update hidden input fields with latitude and longitude
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;

        // Display the name of the place
        if (results && results.length > 0) {
          var placeName = results[0].name;
          document.getElementById('latitude').value = e.latlng.lat;
          document.getElementById('longitude').value = e.latlng.lng;
          document.getElementById('place-name').value = placeName;

          sendMessageToParent(e.latlng.lng, e.latlng.lat, placeName);
        } else {
          //alert("No place name found for this location.");
          document.getElementById('latitude').value = e.latlng.lat;
          document.getElementById('longitude').value = e.latlng.lng;
          document.getElementById('place-name').value = 'Unknown place';
          sendMessageToParent(e.latlng.lng, e.latlng.lat, placeName);
        }
      });
    }


    function sendMessageToParent(lang, latd, place) {
      // Create a JSON object
      var jsonData = {
        message: 'the update location is :',
        data: {
          lng: lang,
          lat: latd,
          name: place
        }
      };

      // Send JSON data to the parent window
      window.parent.postMessage(jsonData, '*');
    }

    // Add click event listener to the map
    mymap.on('click', onMapClick);

  </script>
  <?php } ?>

</body>

</html>