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
        echo("<br>Strona usera {$userToShow->getEmail()}<br><br>");
        $userTweets = User::getAllTweets($userIdToShow);
        foreach($userTweets as $tweet){
            echo("Tweet:<br>");
            echo("Text: {$tweet->getText()}<br>");
            echo("<a href='show_tweet.php?tweet_id={$tweet->getId()}'>Show Tweet.</a><br>");
        }
    } else {
        echo("<br>Nie ma takiego usera.");
    }
}
?>