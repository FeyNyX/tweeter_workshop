<?php
require_once("src/connection.php");
session_start();

$error = 'Zły login lub hasło.';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user = User::logIn($_POST['mail'], $_POST['password']);
    if($user != false){
        $_SESSION['user'] = $user;
        header("location: main.php");
    }
    echo("");
}
?>



<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tweeter</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
    <form action="login.php" method="post">
        <input type="text" name="mail" placeholder="Enter email">
        <input type="password" name="password" placeholder="Enter password">
        <input type="submit" value="login">
    </form>
    <div class='alert alert-danger'>
        <?php
        echo $error;
        ?>
    </div>
</body>
</html>