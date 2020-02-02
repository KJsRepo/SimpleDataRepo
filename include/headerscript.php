<?php

include("myLib.php");
include("dbinfo.php");
include("globals.php");

$conn = new mysqliPlus($DBINFO['host'], $DBINFO['username'], $DBINFO['password'], $DBINFO['dbname']);

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
}



?>
