<?php
session_start();
include "check_auth.php";

if (!isset($_SESSION['username'])) {
    header("location: login.php"); // Redirect to login page
    exit();
}

// Database connection
include("db_connect.php");

function deletePicture() {
    global $username, $conn;
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row['profile_picture'] !== "default_profile.png") {
        $file_path = "uploads/".$row['profile_picture'];
        if (unlink($file_path)) {
            // File deleted successfully
            // Update database entry if needed (prepared statements!)
            $sql = "UPDATE users SET profile_picture='default_profile.png' WHERE username='$username'";
            mysqli_query($conn, $sql);
            $_SESSION['success'] = "Profile picture deleted successfully. <a href='profile.php'>profile</a>";
        } else {
            // Handle deletion error gracefully
            $_SESSION['error'] = "Error deleting image.";
        }
    }
}

$username = $_SESSION['username'];

// Update email in the database
if (isset($_POST['submit'])) {
    // Check for empty fields
    if (empty($_POST['email']) && empty($_FILES['profile_picture'])) {
        $_SESSION['error'] = "Please fill this form to edit account.";
        header("location: edit.php");
        exit();
    }
    $test = $_POST['email'];
    if ($_POST['email']) {
        $new_email = $_POST['email'];
        $sql = "UPDATE users SET email='$new_email' WHERE username='$username'";
        mysqli_query($conn, $sql);
        $_SESSION['success'] = "Email updated successfully! <a href='profile.php'>profile</a>";
    }
    // Check if a file was uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Validate file type and size
        $allowed_types = array('jpg', 'jpeg', 'png');
        $file_type = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        $max_size = 5 * 1024 * 1024; // 5MB

        if (in_array($file_type, $allowed_types) && $_FILES['profile_picture']['size'] <= $max_size) {
            deletePicture(); // Delete the previous file
            // Generate a unique filename
            $new_filename = uniqid() . '.' . $file_type;

            // Move the uploaded file to the desired directory
            $target_dir = 'uploads/'; // Replace with your desired upload directory
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_dir . $new_filename);

            // Update the user's profile picture in the database
            $sql = "UPDATE users SET profile_picture='$new_filename' WHERE username='{$_SESSION['username']}'";
            mysqli_query($conn, $sql);

            // Display a success message
            $_SESSION['success'] = "Profile picture updated successfully! <a href='profile.php'>profile</a>";
        } else {
            // Display an error message
            $_SESSION['error'] = "Invalid file type or size. Please upload a JPG, JPEG, or PNG image smaller than 5MB.";
        }
    }
}

if (isset($_POST['delete_picture'])) {
    deletePicture();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
    body{ font: 14px sans-serif; width: 660px; padding: 20px; margin: 0 auto; padding-top: 100px; background-color: #262626; }
    .wrapper{ width: 360px; padding: 20px; color: #FFFFFFFF; border:3px solid lightblue;}
</style>
<body>

<h1>Edit Account</h1>

<div class="wrapper">
    <form method="post" action="edit.php" enctype="multipart/form-data">
        <h3>Email:</h3>
        <input type="email" name="email" placeholder="New Email" class="form-control"><br><br>
        <h3>Profile picture: </h3>
        <div class="custom-file">
            <label class="custom-file-label" for="profile_picture">Choose file</label>
            <input type="file" class="custom-file-input" name="profile_picture" id="profile_picture"><br><br>
        </div>
        <button type="submit" name="submit" class="btn btn-success btn-sm">Save Changes</button>
    </form>
    <form method="GET" action="edit.php">
        <button type="submit" name="delete_picture" class="btn btn-danger ml-3 btn-sm">Delete Picture</button>
    </form>
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $_SESSION['error']; ?>
        </div>
    <?php unset($_SESSION['error']); } ?>

    <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> <?php echo $_SESSION['success']; ?>
        </div>
    <?php unset($_SESSION['success']); } ?>
</div>
</body>
</html>
