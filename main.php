<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Tweet::createTweet($myUser->getId(), $_POST['tweet']);
}

echo("<br>Witaj {$myUser->getEmail()}");


?>

<form action="main.php" method="post">
    <input type="text" name="tweet" placeholder="tweet text">
    <input type="submit" value="Opublikuj Tweet">
</form>

<?php
$allTweets = Tweet::loadAllTweets();
foreach($allTweets as $tweet){
    echo("Tweet:<br>");
    echo("Text: {$tweet->getText()}<br>");
    echo("<a href='show_tweet.php?tweet_id={$tweet->getId()}'>Show Tweet.</a><br>");
}

$conn->close();
$conn = null;
?>