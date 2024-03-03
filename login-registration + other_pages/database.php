<?php
ini_set('display_errors', '1');

$hostName = "localhost";
$dbUser = "default";
$dbPassword = "default";
$dbName = "default";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if(!$conn)
{
    die("Something went wrong.");

}

?>