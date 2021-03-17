<?php
include("../db.php");
include("User.php");

//deklarerar i förvög
$id = "";
$username = "";
$password = "";

//id måste finnas och vara satt
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $error = new stdClass();
    $error->message = "Id not specified";
    $error->code = "0004";
    echo json_encode($error);
    die();
}

if(isset($_GET['username'])) {
    $username = $_GET['username'];
}

if(isset($_GET['password'])) {
    $password = $_GET['password'];
}

$user = new User($pdo);
echo json_encode($user->UpdateUser($id, $username, $password));
?>