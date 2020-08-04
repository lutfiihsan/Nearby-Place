<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
     <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
    <style>
      html, body {
        height: 100%;
        padding: 0;
        margin: 0;
      }
      #map {
        /* configure the size of the map */
        margin-top: 20px;
        margin-left: 25%;
        margin-right: 25%;
        width: 600px;
        height: 300px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <form action="func-nearby.php" method="post">
      <p style="text-align:center;">
      <label for="">Koordinat : </label>
      <input id="lat" type="text" name="lat" value="-6.3710228">
      <input id="long" type="text" name="long" value="106.5209074">
      </p>
      <p style="text-align:center;">
      <label for="">Place Name</label>
      <input type="text" name="poi">
      </p>
      <p style="text-align:center;">
      <input type="submit" name="save" value="Save">
      </p>
    </form>
    <script>

        // Ambil Nilai Latitude dan Longitude dari Input
        var mylat = document.getElementById("lat").value;
        var mylng = document.getElementById("long").value;

        // Posisikan View Peta
        var mymap = L.map('map').setView([-6.3710228,106.5209074], 15)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 19
        }).addTo(mymap);
        mymap.invalidateSize();

        // Buat Layer group Untuk Marker Yang Dipilih 
        var markerLayer = L.layerGroup().addTo(mymap);

        // Buat Custom Icon
        var primaryIcon = L.Icon.extend({
	        options: {
    		    iconSize:     [50, 50],
    		    shadowSize:   [50, 64],
    		    iconAnchor:   [25, 44],
    		    shadowAnchor: [4, 62],
	        }
        });

        // Buat Secondary Icon
        var secondaryIcon = L.Icon.extend({
	        options: {
    		    iconSize:     [50, 50],
    		    shadowSize:   [50, 64],
    		    iconAnchor:   [25, 44],
    		    shadowAnchor: [4, 62],
	        }
        });

        // Buat Baru Primary Icon dan Secondary Icon
        var green = new secondaryIcon({iconUrl: 'green.png'});
        var red = new primaryIcon({iconUrl: 'red.png'});
        
        // Kembalikan nilai L.Icon dengan Parameter options
        L.icon = function (options) {
           return new L.Icon(options);
        };

        // Fungsi yang akan dijalankan pada fungsi dragend
        function sync(latInput,lngInput) {
            $.ajax({

                type:"POST",
                url:"func-nearby.php",
                // Data `lat` memiliki value dari parameter `latInput`
                // Data `lng` memiliki value dari parameter `lngInput`
                data:{
                  lat:latInput,
                  long:lngInput
                },
                dataType:'JSON',

                success:function (response) {
                    // Bersihkan Semua Secondary Marker pada markerLayer
                    markerLayer.clearLayers();

                    // Looping Jika data yg ditampilkan oleh JSON lebih dari 0
                    var i;
                    if (response.length > 0) {
                      for (i = 0; i < response.length; i++) {
                         marker = L.marker([response[i].lat, response[i].lng],{
                                      icon: green
                         }).addTo(markerLayer).bindPopup(response[i].place);
                      }
                    }

                    // munculkan marker sesuai current location
                    var marker = L.marker([latInput,lngInput],{
                        draggable: true,
                        icon: red
                    }).addTo(markerLayer);

                    // Buat Fungsi Lagi `dragend`
                    marker.on('dragend', function (e) {
                        document.getElementById('lat').value = marker.getLatLng().lat;
                        document.getElementById('long').value = marker.getLatLng().lng;
                        // Bersihkan Semua Secondary Marker pada markerLayer
                        markerLayer.clearLayers();
                        
                        // Panggil Fungsi `sync`
                        sync(marker.getLatLng().lat, marker.getLatLng().lng); 
                    });
                  }

              }); 
          }

        // munculkan marker sesuai current location
        var marker = L.marker([mylat,mylng],{
            draggable: true,
            icon: red
        }).addTo(markerLayer);
        
        sync(mylat, mylng);

    </script>
  </body>
</html>