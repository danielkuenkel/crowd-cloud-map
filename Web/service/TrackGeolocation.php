
<?php

session_start();


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include 'Connection.php';

$LAT = $_GET["lat"];
$LONG = $_GET["long"];
$USERID = $_SESSION['user_id'];

$currentTimestamp = time();
$year = date("Y", $currentTimestamp);
$month = date("m", $currentTimestamp);
$day = date("d", $currentTimestamp);
$checkStampEarly = mktime(0, 0, 0, $month, $day, $year);
$checkStamLate = mktime(23, 59, 59, $month, $day, $year);

$checkQuery = "SELECT * FROM time_location WHERE user_id=$USERID AND time_located>=$checkStampEarly AND time_located<=$checkStamLate";
$checkData = sqlsrv_query($conn, $checkQuery);

$numberOfRows = 0;
while ($row = sqlsrv_fetch_object($checkData)) {
    $numberOfRows++;
}

if ($numberOfRows < 2) {
    $trackQuery = "INSERT INTO location (lat, long) VALUES ($LAT, $LONG)";
    $trackData = sqlsrv_query($conn, $trackQuery);
    
    $trackQueryTime = "INSERT INTO time_location (user_id, time_located) VALUES($USERID, $currentTimestamp)";
    $trackTimeData = sqlsrv_query($conn, $trackQueryTime);

    if ($trackData === false && $trackTimeData === false) {
        $result = array('success' => false, 'errors' => sqlsrv_errors());
    } else {
        $result = array('success' => true);
    }
} else {
    $result = array('success' => false, 'tracks' => $numberOfRows);
}
header('Content-Type: application/json');
echo json_encode($result);
?>
