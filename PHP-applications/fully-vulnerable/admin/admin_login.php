<?php
// Initialize session
session_start();

// Database connection
include("../db_connect.php");


// Check for form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs (add more validation as needed)
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Retrieve matching user from database
        $sql = "SELECT * FROM users WHERE username='$username' AND admin=TRUE";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            $hashed_password = sha1($_POST['password']);

            if ($row['password'] == $hashed_password) {
                // Successful login
                $_SESSION['admin'] = true;
                $_SESSION['username'] = $username;
                if (isset($_POST['remember'])) {
                    // Generate a unique token
                    $info = array("username" => $username, "email" => $row['email'], "admin" => $row['admin'], "picture" => $row['profile_picture']);
                    $token = base64_encode(serialize($info));
                
                    // Set cookie with long expiration time
                    setcookie('remember_token', $token, time() + 60 * 60 * 24 * 7, '/'); // Expires in 7 days
                }            
                header("location: dashboard.php"); // Redirect to admin dashboard
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Invalid username";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; width: 660px; padding: 20px; margin: 0 auto; padding-top: 100px; background-color: #262626;}
        .wrapper{ margin: 0 auto; color:#FFFFFF;}
    </style>
</head>
<body>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <div class="wrapper">    
    <form method="post" action="admin_login.php">
    <h1>Admin Login</h1>
    <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required class="form-control" placeholder="Username">
                <span class="invalid-feedback"></span>
            </div>    
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required class="form-control" placeholder="********">
                <span class="invalid-feedback"></span>
            </div>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-danger" value="Login">
            </div>
            <a href="forgot_password.php">Forgot Password?</a>
    </form>
    </div>
</body>
</html>
