/* 
 * author: daniel kuenkel
 */
loggedIn = false;

function request(method, url, async, requestId) {

    var req = getRequest();

    req.onreadystatechange = function()
    {
        switch (req.readyState) {
            case 4:
                if (req.status != 200) {
                    alert("Error:" + req.status);
                } else {
                    var text = req.responseText;

                    try { // code for IE
                        var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
                        xmlDoc.async = "false";
                        xmlDoc.loadXML(text);
                    } catch (error) { // code for Mozilla, Firefox, Opera, etc.
                        try {
                            var jsonObject = jQuery.parseJSON(text);
                        } catch (error) {
                            alert(error.message);
                            return;
                        }
                    }

                    switch (requestId)
                    {
                        case "loginRequest":
                            handleLoginRequest(jsonObject);
                            break;
                        case "registerRequest":
                            handleRegisterRequest(jsonObject);
                            break;
                        case "trackGeolocation":
                            handleTrackGeolocationResponse(jsonObject);
                            break;
                        case "getGeolocations":
                            handleGetGeolocationsResponse(jsonObject);
                            break;
                    }

                }
                break;

            default:
                return false;
                break;
        }
    };

    //request ist asynchron
    req.open(method, url, async);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(null);
}



function getRequest()
{
    var req = null;
    try {
        req = new XMLHttpRequest();
    }
    catch (ms) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (nonms) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (failed) {
                req = null;
            }
        }
    }
    if (req == null) {
        alert("Error creating request object!");
    }
    return req;
}



// request response handling
function handleLoginRequest(jsonObject) {

    if (jsonObject.success === true)
    {
        loggedIn = true;
        gotoCrowdMap();
    }
    else
    {

    }
}

function handleRegisterRequest(jsonObject) {
    if (jsonObject.success === true)
    {
        loggedIn = true;
        gotoCrowdMap();
    }
    else
    {
        if (jsonObject.error === "namedTwice")
        {
            alert(jsonObject.error);
        }
        else
        {
            alert(jsonObject.error);
        }
    }
}

function handleTrackGeolocationResponse(jsonObject) {

    if (jsonObject.success === true)
    {
        getGeolocations();
    }
    else if(jsonObject.success === false && jsonObject.tracks >= 2)
    {
        alert("Maximum number of tracks reached");
    }
}

function refreshHeatMap(){
    getGeolocations();
    // refresh heatmap every 30 seconds
    setTimeout(refreshHeatMap, 30000);
}

function getGeolocations() {
    request("GET", "service/GetGeolocations.php", true, "getGeolocations");
}


function handleGetGeolocationsResponse(jsonObject) {
    var heatMapData = new Array();
    for (var i = 0; i < jsonObject.length; i++) {
        var object = jsonObject[i];
        heatMapData[i] = new google.maps.LatLng(object.lat, object.long);
    }

    if (heatMapData.length > 0) {
        if (heatmap)
        {
            heatmap.setMap(null);
        }
        else {
            showHeatmap = map;
        }
        var pointArray = new google.maps.MVCArray(heatMapData);
        heatmap = new google.maps.visualization.HeatmapLayer({
            data: pointArray
        });
        heatmap.setMap(showHeatmap);
        heatmap.setOptions({radius: radius});
        heatmap.setOptions({opacity: opacity});
    }
}


// goto functions
function gotoCrowdMap() {
    window.location.href = "crowdMap.php";
}

function gotoWelcome() {
    window.location.href = "welcome.php";
}

function gotoAbout() {
    window.location.href = "about.php";
}

function logout() {
    window.location.href = "service/Logout.php";
}