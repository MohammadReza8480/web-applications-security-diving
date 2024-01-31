<?php
include "check_auth.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
    body{ font: 14px sans-serif; width: 660px; padding: 20px; margin: 0 auto; background-color: #262626; text-align: center;}
    .test{color: #FFFFFF; padding-top: 150px;}
</style>
<body>
    <p>
    <h1 class="test">Hello PenTester!!!!!!</h1>
    <a href="account.php" class="btn btn-info ml-3">Account</a>
    <a href="login.php" class="btn btn-danger ml-3">LOGIN</a>
    <a href="register.php" class="btn btn-warning ml-3">Sign up</a>
    </p>
</body>
</html>