<?php
session_start();

// Destroy the session
session_destroy();

// Optionally, delete the cookie:
setcookie('remember_token', '', time() - 3600, '/');


// Redirect to login page
header("location: login.php");
exit();
?>