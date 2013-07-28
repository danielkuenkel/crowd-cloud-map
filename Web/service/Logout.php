<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
session_destroy();
header("Location: http://crowdcloudmap.azurewebsites.net");
?>
