<?php
include("../db.php");
include("Post.php");

$entries_id = "";
$user_name = "";
$user_email = "";
$message = ""; 

if(isset($_GET['id'])) {
    $entries_id = $_GET['id'];
} else {
    $error = new stdClass();
    $error->message = "Id not specified";
    $error->code = "0004";
    echo json_encode($error);
    die();
}

if(isset($_GET['name'])) {
    $user_name = $_GET['name'];
}

if(isset($_GET['email'])) {
    $user_email = $_GET['email'];
}

if(isset($_GET['message'])) {
    $message = $_GET['message'];
}

$post = new Post($pdo);
echo json_encode($post->UpdatePost($entries_id, $user_name, $user_email, $message));
?>