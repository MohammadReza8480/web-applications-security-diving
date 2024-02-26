<?php
session_start();
include "check_auth.php";

if (!isset($_SESSION['username'])) {
    header("location: login.php"); // Redirect to login page
    exit();
}

header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET");

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: null");
}

// Retrieve user data
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
        body{ font: 17px sans-serif; width: 800px; padding: 25px; margin: 0 auto; padding-top: 100px; background-color: #262626; color:#FFFF; }
</style>
<body>
<div class="a">
<h1>Welcome, <?php echo $row['username']; ?>!</h1>
<img src='uploads/<?php echo $row['profile_picture']?>' alt='Profile Picture' class="rounded-circle" width="150" height="150">
<br><br>
<p>Your profile information:</p>
<ul>
    <li>Username: <?php echo $row['username']; ?></li>
    <li>Email: <?php echo $row['email']; ?></li>
</ul>
<a href="edit.php" class="btn btn-warning">Edit Account</a>
</div>
</body>
</html>