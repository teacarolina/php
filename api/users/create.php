<?php
include("../db.php");
include("User.php");

$user = new User($pdo);
$user->CreateUser("Anders", "testar");
?>