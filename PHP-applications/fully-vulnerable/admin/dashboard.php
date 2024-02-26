<?php
session_start();

if ($_SERVER['HTTP_X_FORWARDED_FOR'] == "127.0.0.1"){
    $_SESSION['username'] = $username;
    $_SESSION['admin'] = "True";

}
    

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== "True") {
    http_response_code(403);
    die('Forbidden');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<style>
        body{ font: 30px sans-serif; width: 1000px; padding: 20px; margin: 0 auto; padding-top: 150px; background-color: #262626; color:#FFFFFF}
    </style>
<body>
    <h1>Welcome to admin panel!!!💥🎉</h1>
</body>
</html>

