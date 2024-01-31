<?php
include "check_auth.php";

session_start();

if (!isset($_SESSION['username'])) {
    header("location: login.php"); // Redirect to login page
    exit();
}

?>

<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
        body{ font: 14px sans-serif; width: 800px; padding: 25px; margin: 0 auto; padding-top: 100px; background-color: #262626; color:#FFFF; }
</style>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <div>
        <a href="profile.php" class="btn btn-info ml-3">View Profile</a>
        <a href="change_password.php" class="btn btn-warning ml-3">Change Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    <div>
</body>
</html>