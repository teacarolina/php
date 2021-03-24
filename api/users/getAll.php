<?php
include("../db.php");
include("User.php");

$token = "";
if(isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    $error = new stdClass();
    $error->message = "No token specified";
    $error->code = "0009";
    print_r(json_encode($error));
    die();
}

$user = new User($pdo);

if($user->isTokenValid($token)) {
    $users = $user->GetAllUsers();
    print_r(json_encode($users));

} else {
    $error = new stdClass();
    $error->message = "Token isn't valid";
    $error->code = "00010";
    print_r(json_encode($error));
    die();
}
?>