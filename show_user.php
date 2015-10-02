<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(isset($_GET['userId'])){
        $userIdToShow = $_GET['user'];
        $userToShow = User::getUserById($userIdToShow);
    } else {
        $userToShow = $myUser;
    }
    $userIdToShow = $_GET['userId'];
    $userToShow = User::getUserById($userIdToShow);
    if($userToShow != false){
        echo("<br>Stron usera {$userToShow->getEmail()}");
    } else {
        echo("<br>Nie ma takiego usera.");
    }
}
?>