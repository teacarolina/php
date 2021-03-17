<?php
include("../db.php");
include("User.php");

$user = new User($pdo);

if(!empty($_GET['id'])) {
    echo json_encode($user->DeleteUser($_GET['id']));
} else {
    $error = new stdClass();
    $error->message = "No ID specified";
    $error->code = "0002";
    print_r(json_encode($error));
    die();
}
?>