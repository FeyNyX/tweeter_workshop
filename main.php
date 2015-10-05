<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tweeter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <style type="text/css">
        body {
            background: #f5f8fa !important;
            font-family: 'Raleway', sans-serif !important;
        }
        .jumbotron {
            background-image: url(http://www.pptbackgroundstemplates.com/backgrounds/clouds-illustration-backgrounds.jpg) !important;
            background-position: bottom !important;
        }
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
        #twee {
            color: white !important;
        }
        #comm {
            color: #31708f !important;
        }
        .modal-header {
            background-color: #337ab7 !important;
        }
        #modal-well {
            font-size: larger;
        }
    </style>
</head>
<body>
<?php
require_once("src/connection.php");
require_once("src/functions.php");
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
                    <li class="active"><a href="#"><span class='glyphicon glyphicon-home'></span> Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="mailbox.php"><span class='glyphicon glyphicon-envelope'></span> MailBox</a></li>
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
        <p><?php echo("Welcome {$myUser->getEmail()}") ?>.</p>
        <hr>
        <h4>What's on your mind?</h4>
        <form role='form' action="main.php" method="post">
            <div class='form-group'>
                <input class='form-control' id='inputdefault' type="text" name="tweet" placeholder="...">
                <br>
                <button class='btn btn-primary' type="submit" data-toggle="tooltip" data-placement="bottom" title="Yay!"><span class='glyphicon glyphicon-bullhorn'></span> Tweet this!</button>
            </div>
        </form>
        </div>
</div>


<?php
$allTweets = Tweet::loadAllTweets();
$modalCountdown = count($allTweets);
echo('<div class="container">
        <h2>Tweets <span class="badge">' . count($allTweets) . '</span></h2>
        <div class="panel-group">');
foreach($allTweets as $tweet){
    $tweetComments = Tweet::loadAllCommentsOfTweet($tweet->getId());
    $tweetCreatorId = $tweet->getUserId();
    $tweetCreator = User::getUserById($tweetCreatorId);
    $tweetCreatorName = substr($tweetCreator->getEmail(), 0, strpos($tweetCreator->getEmail(), "@"));
    $tweetId = $tweet->getId();
        echo('<div class="panel panel-primary">
                <div class="panel-heading">');
    echo(time_elapsed_string($tweet->getCreationDate()) . " by <a data-toggle='tooltip' data-placement='right' title='{$tweetCreator->getEmail()}' id='twee' href='show_user.php?userId={$tweetCreatorId}'>{$tweetCreatorName}</a></div>");
                echo('<div class="panel-body">');
    echo("<div class='well'>{$tweet->getText()}</div>");
    echo("
        <button type='button' class='btn btn-primary btn-md' data-toggle='modal' data-target='#myModal" . $modalCountdown . "'><span class='glyphicon glyphicon-modal-window'></span> &nbsp;See entire text.</button>

        <div id='myModal" . $modalCountdown . "' class='modal fade' role='dialog'>
          <div class='modal-dialog'>

            <div class='modal-content'>
              <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                <h4 class='modal-title'>Tweet #{$tweet->getId()}</h4>" .
                time_elapsed_string($tweet->getCreationDate()) . " (" . $tweet->getCreationDate() . ")
              </div>
              <div class='modal-body'>
                <a href='show_user.php?userId={$tweetCreatorId}'>{$tweetCreator->getEmail()}</a> tweeted:
                <div id='modal-well' class='well'>{$tweet->getText()}</div>
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-info' data-dismiss='modal'>Close</button>
              </div>
            </div>

          </div>
        </div>
        <hr>
        ");
    $modalCountdown--;
    echo("
         <form role='form' action='main.php' method='post'>
            <div class='form-group'>
                <input class='form-control' id='inputdefault' type='text' name='comment' placeholder='...'>
                <input type='hidden' name='tweet_id' value='{$tweet->getId()}'>
                <br>
                <button class='btn btn-info' type='submit'><span class='glyphicon glyphicon-comment'></span> &nbsp;Comment this.</button>
            </div>
         </form>
         ");
    if($tweetComments != false) {
        echo('<div class="container">
                <h4>Comments <span class="badge">' . count($tweetComments) . '</span></h4>
                <div class="panel-group">');
        foreach ($tweetComments as $comment) {
            $commentCreatorId = $comment->getUserId();
            $commentCreator = User::getUserById($commentCreatorId);
            $commentCreatorName = substr($commentCreator->getEmail(), 0, strpos($commentCreator->getEmail(), "@"));
            echo('<div class="panel panel-info" style="max-width: 40%">
                    <div class="panel-heading">');
            echo("Commented " . time_elapsed_string($comment->getCreationDate()) . " by <a data-toggle='tooltip' data-placement='right' title='{$commentCreator->getEmail()}' id='comm' href='show_user.php?userId={$commentCreatorId}'>{$commentCreatorName}</a></div>
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
