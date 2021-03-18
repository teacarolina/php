<?php
include("../db.php");
include("Post.php");

$post = new Post($pdo);
$post->CreatePost("Emilia", "emilia@testing.com", "Jag testar en ny sak för att se om det fungerar");
?>