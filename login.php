<?php
//login to the guestbook using registered users in the DB
$dsn = "mysql:host=localhost;dbname=guestbook";
$user = "root";
$password = "";
$pdo = new PDO($dsn, $user, $password);

if(isset($_GET['action'])) {
    $action = $_GET['action'];
}

if(isset($action) && $action == "access") {
    $stm = $pdo->query("SELECT id, username, password FROM users");
    while($row = $stm->fetch()) {
       if($_POST['username'] == $row['username'] && $_POST['password'] == $row['password']) {
            session_start();
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['password'] = $_POST['password'];
            header("location:guestbook.php");
    } else {
       echo "Something went wrong";
   }
}
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    </head>
<body>
    <h4>Login</h4>
    <form action="login.php?action=access" method="POST">
    <label for="name">Username</label><br>
    <input type="text" placeholder="Username" name="username"><br>
    <label for="email">Password</label><br>
    <input type="password" placeholder="Password" name="password"><br>
    <input type="submit" value="Login">
    </form>
    <p>No account yet? <a href="signup.php">Register here</a></p>
</body>
</html>