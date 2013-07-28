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
        <title>Crowd Map</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
        <style type="text/css">
            @import "css/main-about.css";
        </style>

        <script type="text/javascript" src="js/jquery-2.0.0.js" charset="utf-8"></script>
        <script type="text/javascript" src="js/ajax.js" charset="utf-8"></script>
        <script type="text/javascript">
            $(window).load(function() {
            });

            function checkSession()
            {
                loggedIn = <?php echo $_SESSION['loggedIn'] ?>;
                if (loggedIn == 1)
                {
                    loggedIn = true;
                    gotoCrowdMap();
                }
                else
                {
                    gotoWelcome();
                }
            }

        </script>
    </head>

    <body>

        <div id="content">
            <div id="logo">
            </div>
            <div id="seperator">
            </div>
            <input id="backButton" type="submit" value="" onclick="checkSession()" />
            <div id="aboutContentWrapper">
                <div id="aboutContent">
                    <div class="entry">
                        <div class="entryTop"></div>
                        <div class="entryContent">
                            <p class="entryHeadline">
                                what is crowd map?
                            </p>
                            <img class="entryImage" src="img/info-map.jpg" width="620" height="360"/>
                            <p class="entryText">
                                Crowd Map is an interactive heatmap. The service follows the principles of crowdsourcing. 
                                Users contribute their data to other users. Thus crowd map allows to create a map for users who want to find interesting locations in foreign places. 
                                The data comes from knowledgeable local users. Step by step creating a comprehensive collection of worlds amazing places that are waiting for your visit.
                            </p>
                        </div>
                        <div class="entryBottom"></div>
                    </div>
                    <div class="entry">
                        <div class="entryTop"></div>
                        <div class="entryContent">
                            <p class="entryHeadline">
                                your current location
                            </p>

                            <div class="entryImageInner" id="imageCurrentLocation"></div>

                            <p class="entryText">
                                You can display in your browser your current location on a map. The current position is performed in the browser with WiFi access point triangulation. This method will only work for known WiFi access points in the immediate vicinity of urban areas and is somewhat less accurate than GPS.
                            </p>
                            <p class="entryText">
                                If you are using an iOS device, a more accurate determination of your location is possible (GPS chip's integrated in your device). As for the browser application with the iOS app you can find interesting places in your area.
                            </p>
                        </div>
                        <div class="entryBottom"></div>
                    </div>
                    <div class="entry">
                        <div class="entryTop"></div>
                        <div class="entryContent">
                            <p class="entryHeadline">
                                track your current location

                            </p>
                            <div class="entryImageInner" id="imageTrackLocation"></div>

                            <p class="entryText">
                                Once you've found your current location (and this would be interesssant for other users), you can provide to other users now. This is possible in the browser and in the iOS app. Keep in mind that you can track a place only up to two times a day, so this feature is not abused.
                            </p>
                            <p class="entryText">
                                And do not worry! The data is stored anonymously so that a conclusion is excluded to the respective users.
                            </p>
                        </div>
                        <div class="entryBottom"></div>
                    </div>
                    <div class="entry">
                        <div class="entryTop"></div>
                        <div class="entryContent">
                            <p class="entryHeadline">
                                additional features

                            </p>
                            <div class="entryImageInner" id="imageAdditionalFeatures"></div>

                            <p class="entryText">
                                With these other useful features the web application is completed.
                            </p>
                            <p class="entryText">
                                You can search for any location and have a look, for example, before a trip, what interesting places are available on site. So you can plan your trip better and are well informed about the locations.
                            </p>
                            <p class="entryText">
                                Furthermore, you can change the appearance of the heat map. You can change the opacity and the radius of the dots.
                            </p>
                        </div>
                        <div class="entryBottom"></div>
                    </div>
                </div>
            </div>
        </div>

    </body>

</html>