<?php

session_start();

# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Hier wird als php Script die Funktion Einloggen zusammengesetzt beim Prüfung
# die eingegebene  Daten mit der in der Datenbank sind


include 'Connection.php';
$_SESSION['logged_in'] = 0;
$logon = strtolower($_GET['logon']);
$logonPassword = $_GET['password'];

$numberOfRows = 0;
$query = "SELECT * FROM [dbo].[user] WHERE logon_name='$logon'";
$data = sqlsrv_query($conn, $query);

while ($row = sqlsrv_fetch_object($data)) {
    $numberOfRows++;
}

$result = array();
if ($numberOfRows == 0) {

    $registerQuery = "INSERT INTO [dbo].[user] (logon_name, password) OUTPUT INSERTED.ID VALUES('$logon', '$logonPassword')"; 
    $dataRegister = sqlsrv_query($conn, $registerQuery);

    if ($dataRegister === false) {
        $result = array('success' => false, 'error' => 'register failed');
    } else {
        $_SESSION['logon_name'] = $logon;
        
        while($row = sqlsrv_fetch_array($dataRegister, SQLSRV_FETCH_ASSOC))
        {
            $_SESSION['user_id'] = $row['ID'];
        }
        $_SESSION['logged_in'] = 1;

        $result = array('success' => true, 'logon_name' => $logon, 'user_id' => $_SESSION['user_id']);
    }
} else {
    $result = array('success' => false, 'error' => 'namedTwice');
}

header('Content-Type: application/json');
echo json_encode($result);
?>