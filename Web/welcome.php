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
            @import "css/main-welcome.css";
        </style>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

        <script type="text/javascript" src="js/jquery-2.0.0.js" charset="utf-8"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
        <script type="text/javascript" src="js/ajax.js" charset="utf-8"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script type="text/javascript">
            var state = "signIn";
            
            $(function() {
                $(document).tooltip();
            });
            
            $(window).load(function() {
                checkSession();

                $("#loginUser").focusout(function() {
                    var trimmedString = document.getElementById("loginUser").value.replace(/ /g, '');
                    if (trimmedString.length > 0) {
                        document.getElementById("loginUser").value = trimmedString.toString();
                    }
                    else {
                        if (state == "signIn") {
                            document.getElementById("loginUser").value = "Username";
                        }
                        else {
                            document.getElementById("loginUser").value = "TYPE IN A UNIQUE USERNAME";
                        }
                    }
                });

                $("#loginPassword").focusout(function() {
                    var trimmedString = document.getElementById("loginPassword").value.replace(/ /g, '');
                    if (trimmedString.length > 0) {
                        document.getElementById("loginPassword").value = trimmedString;
                    }
                    else {
                        document.getElementById("loginPassword").value = "Password";
                    }
                });

                $("#loginUser").keyup(function(event) {
                    if (event.keyCode == 13) {
                        if (state == "signIn") {
                            $("#loginButton").click();
                        }
                        else {
                            $("#registerButton").click();
                        }
                    }
                })
                
                $("#loginPassword").keyup(function(event) {
                    if (event.keyCode == 13) {
                        if (state == "signIn") {
                            $("#loginButton").click();
                        }
                        else {
                            $("#registerButton").click();
                        }
                    }
                })
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

                }
            }

            function onFocusUsername() {
                var trimmedString = document.getElementById("loginUser").value.replace(/ /g, '');
                if (trimmedString.toLowerCase() == "username")
                {
                    document.getElementById("loginUser").value = "";
                }
                else if (trimmedString.toLowerCase() == "typeinauniqueusername")
                {
                    document.getElementById("loginUser").value = "";
                }
            }

            function onFocusPassword() {
                var trimmedString = document.getElementById("loginPassword").value.replace(/ /g, '');
                if (trimmedString.toLowerCase() == "password")
                {
                    document.getElementById("loginPassword").value = "";
                }
            }

            function loginUser() {
                var trimmedUser = document.getElementById("loginUser").value.replace(/ /g, '');
                var trimmedPass = document.getElementById("loginPassword").value.replace(/ /g, '');
                if (state == "signIn" && trimmedUser.toLowerCase() != "username" && trimmedPass.toLowerCase() != "password") {
                    var logon = document.getElementById("loginUser").value;
                    var password = CryptoJS.SHA256(document.getElementById("loginPassword").value);
                    request("GET", "service/LoginRequest.php?logon=" + logon + "&password=" + password, true, "loginRequest");
                }
                else if (state != "signIn") {
                    toggleState();
                }
            }

            function registerUser() {
                var trimmedUser = document.getElementById("loginUser").value.replace(/ /g, '');
                var trimmedPass = document.getElementById("loginPassword").value.replace(/ /g, '');
                if (state == "signUp" && trimmedUser.toLowerCase() != "typeinauniqueusername" && trimmedPass.toLowerCase() != "password") {
                    var logon = document.getElementById("loginUser").value;
                    var password = CryptoJS.SHA256(document.getElementById("loginPassword").value);
                    request("GET", "service/RegisterRequest.php?logon=" + logon + "&password=" + password, true, "registerRequest");
                }
                else if (state != "signUp") {
                    toggleState();
                }
            }

            function toggleState() {
                switch (state) {
                    case "signIn":
                        state = "signUp";
                        document.getElementById('seperatorTop').style.display = "none";
                        document.getElementById('seperatorBottom').style.display = "inline-block";
                        document.getElementById("loginUser").value = "TYPE IN A UNIQUE USERNAME";
                        document.getElementById("loginPassword").value = "Password";
                        break;
                    default:
                        state = "signIn";
                        document.getElementById('seperatorTop').style.display = "inline-block";
                        document.getElementById('seperatorBottom').style.display = "none";
                        document.getElementById("loginUser").value = "Username";
                        document.getElementById("loginPassword").value = "Password";
                        break;
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
            <div id="loginRegisterForm">
                <input class="welcomeButton" id="registerButton" type="submit" value="" onclick="registerUser()" />
                <div id="seperatorTop"></div>
                <p>
                <div id="loginInputFields">
                    <form id="form" action="javascript:loginUser();">
                        <input type="text" class="inputField" id="loginUser" value="Username" onfocus="onFocusUsername()">
                        <input type="password" class="inputField" id="loginPassword" value="Password" onfocus="onFocusPassword()">
                    </form>
                </div>
                <div id="seperatorBottom" style="display: none;"></div>
                </p>
                <input class="welcomeButton" id="loginButton" type="submit" value="" onclick="loginUser()" />
                <div class="smallSeperator"></div>
                <input id="moreInformationButton" type="submit" value="" onclick="gotoAbout()" />
            </div>
        </div>

    </body>

</html>