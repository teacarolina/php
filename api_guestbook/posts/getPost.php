<?php
include("../db.php");
include("Post.php");

$post = new Post($pdo);

if(!empty($_GET['id'])) {
    $entriesData = $post->GetPost($_GET['id']);
    print_r(json_encode($entriesData));
} else {
    $error = new stdClass();
    $error->message = "No ID specified";
    $error->code = "0002";
    print_r(json_encode($error));
    die();
}
?>