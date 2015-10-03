<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['tweet'])) {
        Tweet::createTweet($myUser->getId(), $_POST['tweet']);
    }
    if(isset($_POST['comment'])) {
        Comment::createComment($myUser->getId(), $_POST['tweet_id'], $_POST['comment']);
    }
}

echo("<br>Witaj {$myUser->getEmail()}<br>");
echo("<a href='logout.php'>Log out.</a>");

?>

<form action="main.php" method="post">
    <input type="text" name="tweet" placeholder="tweet text">
    <input type="submit" value="Opublikuj Tweet">
</form>

<?php
$allTweets = Tweet::loadAllTweets();
foreach($allTweets as $tweet){
    $tweetComments = Tweet::loadAllCommentsOfTweet($tweet->getId());
    $tweetCreatorId = $tweet->getUserId();
    $tweetCreator = User::getUserById($tweetCreatorId);
    echo("Tweet by user {$tweetCreator->getEmail()}:<br>");
    echo("Text: {$tweet->getText()}<br>");
    echo("<a href='show_tweet.php?tweet_id={$tweet->getId()}'>Show Tweet.</a><br>");
    if($tweetComments != false) {
        foreach ($tweetComments as $comment) {
            $commentCreatorId = $comment->getUserId();
            $commentCreator = User::getUserById($commentCreatorId);
            echo("Comment by user {$commentCreator->getEmail()}: {$comment->getText()}<br>");
        }
    }
    echo("
         <form action='main.php' method='post'>
         <input type='text' name='comment' placeholder='comment text'>
         <input type='hidden' name='tweet_id' value='{$tweet->getId()}'>
         <input type='submit' value='Opublikuj komentarz'>
         </form>
         ");

    echo("<br>");
}

$conn->close();
$conn = null;
?>