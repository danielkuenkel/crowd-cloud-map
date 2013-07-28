<?php
session_start();
if (isset($_SESSION['logon_name'])) {
    $_SESSION['loggedIn'] = 1;
} else {
    $_SESSION['loggedIn'] = 0;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Crowd Map</title>

        <style type="text/css">
            @import "css/main-map.css";
        </style>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=visualization"></script>
        <script type="text/javascript" src="js/jquery-2.0.0.js" charset="utf-8"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script type="text/javascript" src="js/ajax.js" charset="utf-8"></script>
        <script type="text/javascript">
            $(function() {
                $(document).tooltip();
            });

            $(document).ready(function() {
                checkSession();
            });
            $(window).load(function() {
                $("#address").focusout(function() {
                    var trimmedString = document.getElementById("address").value.replace(/ /g, '');
                    if (trimmedString.length == 0) {
                        document.getElementById("address").value = "SEARCH ADDRESS";
                    }
                });

                $("#address").keyup(function(event) {
                    if (event.keyCode == 13) {
                        $("#searchButton").click();
                    }
                })
            });
            function checkSession()
            {
                loggedIn = <?php echo $_SESSION['loggedIn'] ?>;
                if (loggedIn == 1)
                {
                    initialize();
                    loggedIn = true;
                }
                else
                {
                    gotoWelcome();
                }
            }

            var map, geocoder, pointarray, heatmap, showHeatmap, opacity, addressMarker, geoMarker;
            var radius = 30;
            function initialize() {
                geocoder = new google.maps.Geocoder();

                // Try HTML5 geolocation
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var pos = new google.maps.LatLng(position.coords.latitude,
                                position.coords.longitude);
                        initializeMap(15, pos, google.maps.MapTypeId.ROADMAP, true);
                    }, function() {
                        handleNoGeolocation(true);
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleNoGeolocation(false);
                }
            }


            function handleNoGeolocation(error) {
                if (error === false)
                {
                    alert("Browser doesn't support Geolocation");
                    initializeMap(3, new google.maps.LatLng(50.847573, 9.843750), google.maps.MapTypeId.ROADMAP, false);
                }
                else {
                    alert("HTML5 gelocation failed");
                    initializeMap(3, new google.maps.LatLng(50.847573, 9.843750), google.maps.MapTypeId.ROADMAP, false);
                }
            }

            function initializeMap(zoom, position, mapType, marker) {

                var mapOptions = {
                    zoom: zoom,
                    center: position,
                    mapTypeId: mapType
                };
                map = new google.maps.Map(document.getElementById('map-canvas'),
                        mapOptions);

                if (marker) {
                    markCurrentLocation(position);
                }

                refreshHeatMap();
            }

            function markCurrentLocation(position) {
                if (geoMarker)
                {
                    geoMarker.setMap(null);
                }
                var image = '../img/maps-marker.png';
                geoMarker = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: image});
            }

            function toggleHeatmap() {
                showHeatmap = heatmap.getMap() ? null : map;
                heatmap.setMap(showHeatmap);
            }

            function changeRadius() {
                radius = heatmap.get('radius') ? null : 30;
                heatmap.setOptions({radius: radius});

                if (radius === 30) {
                    $('#toggleRadiusButton').removeClass('sel');
                }
                else
                {
                    $('#toggleRadiusButton').addClass('sel');
                }
            }

            function changeOpacity() {
                opacity = heatmap.get('opacity') ? null : 0.3;
                heatmap.setOptions({opacity: opacity});

                if (opacity === 0.3) {
                    $('#toggleOpacityButton').addClass('sel');
                }
                else
                {
                    $('#toggleOpacityButton').removeClass('sel');
                }
            }

            function showCurrenctLocation() {
                if (navigator.geolocation)
                {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var pos = new google.maps.LatLng(position.coords.latitude,
                                position.coords.longitude);
                        showPosition(pos);
                    }, function() {
                        handleNoGeolocation(true);
                    });
                }
                else {
                    handleNoGeolocation(false);
                }

            }

            function showPosition(position)
            {
                markCurrentLocation(position);
                map.setCenter(position);
                map.setZoom(15);
            }

            function trackCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        request("GET", "service/TrackGeolocation.php?lat=" + position.coords.latitude + "&long=" + position.coords.longitude, true, "trackGeolocation");
                    }, function() {
                        handleNoGeolocation(true);
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleNoGeolocation(false);
                }
            }

            function codeAddress() {
                var address = document.getElementById('address').value;
                var trimmedString = document.getElementById("address").value.replace(/ /g, '');
                if (trimmedString.toLowerCase() != "address") {
                    geocoder.geocode({'address': address}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            map.setCenter(results[0].geometry.location);
                            if (addressMarker)
                            {
                                addressMarker.setMap(null);
                            }
                            addressMarker = new google.maps.Marker({
                                map: map,
                                position: results[0].geometry.location,
                                draggable: true
                            });
                        } else {
                            alert('Geocode was not successful for the following reason: No results for search string');
                        }
                    });
                }
            }

            function onFocusAddress() {
                var trimmedString = document.getElementById("address").value.replace(/ /g, '');
                if (trimmedString.toLowerCase() == "searchaddress")
                {
                    document.getElementById("address").value = "";
                }
            }
        </script>
    </script>
</head>
<body>
    <div id="map-content">
        <div id="navigationBar">
            <div id="mapsPanel">
                <button id="searchButton" onclick="codeAddress()"></button>
                <input type="text" class="inputField" id="address" value="SEARCH ADDRESS" onfocus="onFocusAddress()" title="Put in search string, press ENTER!">
                <!--<button class="toggleButton" onclick="toggleHeatmap()">TH</button>-->
                <button class="toggleButton" id="toggleRadiusButton" onclick="changeRadius()" title="Change heat map bubble radius"></button>
                <button class="toggleButton" id="toggleOpacityButton" onclick="changeOpacity()" title="Change heat map bubble opacity"></button>
            </div>
            <div id="navButtons">
                <input class="mapNavigationButton" id="currentLocationButton" type="submit" value="" onclick="showCurrenctLocation()" title="Show the current location" />
                <input class="mapNavigationButton" id="trackLocationButton" type="submit" value="" onclick="trackCurrentLocation()" title="Track your current location"/>
                <input class="mapNavigationButton" id="helpButton" type="submit" value="" onclick="gotoAbout()" title="Give me some more information about this website" />
                <input class="mapNavigationButton" id="logoutButton" type="submit" value="" onclick="logout()" title="Sign out"/>
            </div>
        </div>
        <div id="map-canvas"></div>
    </div>
</body>
</html>
