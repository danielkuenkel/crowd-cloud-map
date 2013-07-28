<?php
include 'Connection.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    $query = "SELECT * FROM location";
    $data = sqlsrv_query($conn, $query);

    
    $result = array();
    do {
        while ($row = sqlsrv_fetch_array($data, SQLSRV_FETCH_ASSOC)){
            $result[] = $row;
        }
    }while ( sqlsrv_next_result($data) );
    
//    $result = array('locations' => $structure);
    header('Content-Type: application/json');
    echo json_encode($result);
?>
