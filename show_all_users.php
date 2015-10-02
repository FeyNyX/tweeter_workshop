<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

$allUsers = User::getAllUsers();
foreach($allUsers as $user){
    echo("{$user->getEmail()}");
    echo("<a href='show_user.php?userId={$user->getId()}'>Show</a>");
    echo("<br>");
}