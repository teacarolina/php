<?php
include("../db.php");
include("Post.php");

$post = new Post($pdo);
$post = $post->GetAllPosts();

echo "<pre>";
print_r(json_encode($post));
echo "</pre>";
?>