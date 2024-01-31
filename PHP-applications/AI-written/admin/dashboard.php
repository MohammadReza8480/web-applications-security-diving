<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: index.php"); // Redirect to non-admin page
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