<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

if($_SERVER['REQUEST_METHOD'] == "GET"){
    $tweetIdToShow = $_GET['tweet_id'];
    $tweetToShow = Tweet::getTweetById($tweetIdToShow);
    $creatorId = $tweetToShow->getUserId(); // user that created the tweet (his/her id)
    $creator = User::getUserById($creatorId); // user that created the tweet
    if($tweetToShow != false){
        echo("<br><br>Wpis o numerze {$tweetToShow->getId()} dodany przez usera {$creator->getEmail()} o czasie {$tweetToShow->getCreationDate()}:<br>");
        echo("{$tweetToShow->getText()}");
    } else {
        echo("<br>Nie ma takiego wpisu.");
    }
}
?>