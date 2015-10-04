<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user']) == false){
    header("location: login.php");
}

$myUser = $_SESSION['user'];

$receivedMessages = Message::loadAllMessagesAsReceiver($myUser->getId());
echo("<h1>MailBox</h1>");
echo("<h3>User: {$myUser->getEmail()}</h3>");
echo("<hr>");
echo("<h2>Received:</h2>");
foreach($receivedMessages as $receivedMessage){
    $messageSenderId = $receivedMessage->getSenderId();
    $messageSender = User::getUserById($messageSenderId);
    switch($receivedMessage->getIsRead()){
        case 0:
            $read = "Nope.";
            break;
        case 1:
            $read = "YES!";
            break;
        default:
            $read = "Something went wrong...";
    }
    echo("Sender: {$messageSender->getEmail()}<br>");
    echo("Date: {$receivedMessage->getCreationDate()}<br>");
    echo("Is read?: {$read}<br>");
    echo("<a href='open_message.php?messageId={$receivedMessage->getMessageId()}'>Show the message...</a><br>");
    echo("<br><br>");
}

$sentMessages = Message::loadAllMessagesAsSender($myUser->getId());
echo("<hr>");
echo("<h2>Sent:</h2>");
foreach($sentMessages as $sentMessage){
    $messageReceiverId = $sentMessage->getReceiverId();
    $messageReceiver = User::getUserById($messageReceiverId);
    switch($sentMessage->getIsRead()){
        case 0:
            $read = "Nope.";
            break;
        case 1:
            $read = "YES!";
            break;
        default:
            $read = "Something went wrong...";
    }
    echo("Receiver: {$messageReceiver->getEmail()}<br>");
    echo("Date: {$sentMessage->getCreationDate()}<br>");
    echo("Is read?: {$read}<br>");
    echo("<a href='open_message.php?messageId={$sentMessage->getMessageId()}'>Show the message...</a><br>");
    echo("<br><br>");
}

$conn->close();
$conn = null;
?>