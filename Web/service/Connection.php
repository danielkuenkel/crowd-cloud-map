<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    $server = "tcp:g507cqqawl.database.windows.net,1433";
    $username = "ccm-user";
    $password = "hqtjIHRvCd1A2iaYuLVa";
    $database = "ccm";

    $conn = sqlsrv_connect($server, array("UID"=>$username, "PWD"=>$password, "Database"=>$database));

    if($conn === false){
        die(print_r(sqlsrv_errors()));
    }
?>
