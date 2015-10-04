<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tweeter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style type="text/css">
        body { background: #f5f8fa !important; }
        .jumbotron { background: #BFE0EC !important; }
        hr {
            height: 2px;
            margin-left: auto;
            margin-right: auto;
            background-color:#0084B4;
            color:#0084B4;
            border: 0 none;
            margin-top: 35px;
            margin-bottom:15px;
        }
    </style>
</head>
<body>
<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];
?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Tweeter</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="mailbox.php">MailBox</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="show_user.php?userId=<?php echo("{$myUser->getId()}") ?>"><span class="glyphicon glyphicon-user"></span> My profile</a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['tweet'])) {
        Tweet::createTweet($myUser->getId(), $_POST['tweet']);
    }
    if(isset($_POST['comment'])) {
        Comment::createComment($myUser->getId(), $_POST['tweet_id'], $_POST['comment']);
    }
}

?>
<div class="container">
    <div class="jumbotron">
        <h1>Tweeter</h1>
        <p><?php echo("Welcome {$myUser->getEmail()}") ?>!</p>
        <hr>
        <h4>What's on your mind?</h4>
        <form role='form' action="main.php" method="post">
            <div class='form-group'>
                <input class='form-control' id='inputdefault' type="text" name="tweet" placeholder="...">
                <br>
                <button class='btn btn-primary' type="submit">Tweet this.</button>
            </div>
        </form>
        </div>
</div>


<?php
$allTweets = Tweet::loadAllTweets();
echo('<div class="container">
        <h2>Tweets</h2>
        <div class="panel-group">');
foreach($allTweets as $tweet){
    $tweetComments = Tweet::loadAllCommentsOfTweet($tweet->getId());
    $tweetCreatorId = $tweet->getUserId();
    $tweetCreator = User::getUserById($tweetCreatorId);
        echo('<div class="panel panel-primary">
                <div class="panel-heading">');
    echo("{$tweetCreator->getEmail()} wrote:</div>");
                echo('<div class="panel-body">');
    echo("<div class='well'>{$tweet->getText()}</div>");
    echo("<button class='btn btn-primary' type='button' a href='show_tweet.php?tweet_id={$tweet->getId()}'>See entire text...</button><hr>");
    echo("
         <form role='form' action='main.php' method='post'>
            <div class='form-group'>
                <input class='form-control' id='inputdefault' type='text' name='comment' placeholder='...'>
                <input type='hidden' name='tweet_id' value='{$tweet->getId()}'>
                <br>
                <button class='btn btn-info' type='submit'>Comment this.</button>
            </div>
         </form>
         ");
    if($tweetComments != false) {
        echo('<div class="container">
                <h4>Comments</h4>
                <div class="panel-group">');
        foreach ($tweetComments as $comment) {
            $commentCreatorId = $comment->getUserId();
            $commentCreator = User::getUserById($commentCreatorId);
            echo('<div class="panel panel-info" style="max-width: 40%">
                    <div class="panel-heading">');
            echo("{$commentCreator->getEmail()} commented:</div>
                    <div class='panel-body'>{$comment->getText()}</div></div>");
        }
        echo('</div></div>');
    }

    echo("</div></div><br>");
}

$conn->close();
$conn = null;
?>
</body>
</html>
