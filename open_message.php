<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['messageId'])) {
        $messageToShow = Message::getMessageById($_GET['messageId']);
        if($messageToShow->getReceiverId() == $myUser->getId()) {
            $messageToShow->setIsRead();
            $sql = ("UPDATE Messages SET is_read=1 WHERE message_id={$_GET['messageId']}");
            $conn->query($sql);
        }

        $messageSenderId = $messageToShow->getSenderId();
        $messageSender = User::getUserById($messageSenderId);

        $messageReceiverId = $messageToShow->getReceiverId();
        $messageReceiver = User::getUserById($messageReceiverId);

        echo("<br><br><a href='mailbox.php'>Back to the MailBox...</a>");
        echo("<h2>Message:</h2>");
        echo("Sender: {$messageSender->getEmail()}<br>");
        echo("Receiver: {$messageReceiver->getEmail()}<br>");
        echo("Date: {$messageToShow->getCreationDate()}<br>");
        echo("Text: {$messageToShow->getText()}<br>");
        echo("<a href='send_message.php?userId={$messageSenderId}'>Reply...</a><br>");
        echo("<br><br>");
    }
}