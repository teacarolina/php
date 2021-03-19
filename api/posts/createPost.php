<?php
include("../db.php");
include("Post.php");

$post = new Post($pdo);
$post->CreatePost("name", "email", "message");
?>