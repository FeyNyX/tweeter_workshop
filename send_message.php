<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(strlen($_POST['message']) > 0){
        $createdMessage = Message::createMessage($_POST['sender'], $_POST['receiver'], $_POST['message']);
        if($createdMessage != false){
            echo "<br><br>Message sent!";
        }
        header("Refresh:2; url=mailbox.php");
    }
}

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['userId'])) {
        $userIdToShow = $_GET['user'];
        $userToShow = User::getUserById($userIdToShow);
    } else {
        $userToShow = $myUser;
    }
    $userIdToShow = $_GET['userId'];
    $userToShow = User::getUserById($userIdToShow);
    if ($userToShow != false) {
        echo("<br>Strona usera {$userToShow->getEmail()}.<br><br>");
        if ($myUser->getId() != $userIdToShow) {
            echo("
                <form action='send_message.php?userId={$_GET['userId']}' method='POST'>
                    <textarea name='message' placeholder='message text'></textarea>
                    <br>
                    <input type='hidden' name='sender' value='{$myUser->getId()}'>
                    <input type='hidden' name='receiver' value='{$userIdToShow}'>
                    <input type='submit' value='Send a message to {$userToShow->getEmail()}.'>
                </form>
                ");
        }
    }
}