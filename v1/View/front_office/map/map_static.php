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
$lng = 0;
$lat = 0;
$place = "";
if (isset($_GET['lng']) && isset($_GET['lat'])  && isset($_GET['place'])) {
  $lng = urldecode($_GET['lng']);
  $lat = urldecode($_GET['lat']);
  $place = urldecode($_GET['place']);
}
?>

<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
  // Create a Leaflet map centered at latitude 51.505 and longitude -0.09, with zoom level 13
  var mymap = L.map('map').setView([36.8994900941884, 10.189252776301089], 13);

  // Add OpenStreetMap tiles to the map
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
  }).addTo(mymap);

  function add_marker(lat, long, name) {
    var marker = L.marker([lat, long]).addTo(mymap);
    marker.bindPopup("<b>"+name+"</b>").openPopup();
    console.log(typeof lat, typeof long, typeof name)
  }

  add_marker(<?= floatval($lat) ?>, <?= floatval($lng) ?>, "<?= $place ?>");
//  // Add a marker at latitude 51.5 and longitude -0.09, with a popup
//  var marker = L.marker([51.5, -0.09]).addTo(mymap);
//   marker.bindPopup("<b>Hello world!</b>").openPopup();
    
</script>

</body>
</html>
