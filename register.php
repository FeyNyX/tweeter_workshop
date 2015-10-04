<?php
require_once("src/connection.php");
session_start();

if(isset($_SESSION['user'])){
    header("location: main.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $newUser = User::register($_POST['email'], $_POST['password1'], $_POST['password2'], $_POST['description']);
    if($newUser != false){
        $_SESSION['user'] = $newUser;
        header("location: main.php");
    }
}

?>

<form method="post" action="register.php">
    <input type="text" name="email" placeholder="Enter your email">
    <input type="password" name="password1" placeholder="Password">
    <input type="password" name="password2" placeholder="Repeat password">
    <input type="text" name="description" placeholder="Napisz cos o sobie">
    <input type="submit" value="register">
</form>
