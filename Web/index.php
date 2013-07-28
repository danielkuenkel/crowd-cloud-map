<?php
$sessionId = session_id();
if(empty($sessionId)) session_start();
echo "SID: ".SID."<br>session_id(): ".session_id()."<br>COOKIE: ".$_COOKIE["PHPSESSID"];
if (isset($_SESSION['logon_name'])) {
    $_SESSION['loggedIn'] = 1;
    header("Location: crowdMap.php");
} else {
    $_SESSION['loggedIn'] = 0;
    header("Location: welcome.php");
}
?>