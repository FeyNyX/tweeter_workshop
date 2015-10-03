<?php
require_once('user.php');
require_once("tweet.php");
require_once("comment.php");

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
Tweet::setConnection($conn);
Comment::setConnection($conn);

?>