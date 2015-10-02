<?php
require_once('user.php');

$userName = '';
$host = '';
$dbName = '';
$password = '';

$conn = new mysqli($host, $userName, $password, $dbName);

if($conn == false){
    die("Cannot connect to database");
} else {
    echo("Connection successful.");
}

User::setConnection($conn);

?>