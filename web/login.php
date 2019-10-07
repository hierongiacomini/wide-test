<?php
session_start();
include(dirname(__FILE__) . "/database.php");

if(empty($_POST['username']) || empty($_POST['password'])){
    header('Location: /');
    exit();
}


$username = $db->quote($_POST['username']);
$password = $db->quote($_POST['password']);

$query = "SELECT user_name FROM users WHERE user_name = {$username} and user_password = md5({$password})";
$stmt = $db->query($query);
if (!($stmt->rowCount())){
    $_SESSION['wrong_authentication'] = true;
    header('Location: /');
}
else {
    $_SESSION['username'] = $username;
    header('Location: /app/');
}
$stmt->closeCursor();
exit();
