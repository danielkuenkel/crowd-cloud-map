<?php

session_start();

include 'Connection.php';
$_SESSION['logged_in'] = 0;
$logon = strtolower($_GET['logon']);
$logonPassword = $_GET['password'];

$numberOfRows = 0;
$query = "SELECT * FROM [dbo].[user] WHERE logon_name='$logon' AND password='$logonPassword'";
$data = sqlsrv_query($conn, $query);

$userArray = array();
while ($row = sqlsrv_fetch_object($data)) {
    $userArray[$numberOfRows] = array($row->logon_name, $row->ID);
    $numberOfRows++;
}

$result = array();
if ($numberOfRows == 1) {
    $array = $userArray[0];
    $_SESSION['logon_name'] = $array[0];
    $_SESSION['user_id'] = $array[1];
    $_SESSION['logged_in'] = 1;
    $result = array('success' => true, 'logon_name' => $array[0], 'user_id' => $array[1]);
} else {
    $result = array('success' => false);
}

header('Content-Type: application/json');
echo json_encode($result);
?>