<?php
session_start();
include "check_auth.php";

if (!isset($_SESSION['username'])) {
    header("location: login.php"); // Redirect to login page
    exit();
}

// Database connection
include("db_connect.php");

// Define an array of Magic Bytes for common image formats
$magicBytes = [
    "jpeg" => "\xFF\xD8\xFF",
    "png" => "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
    "gif" => "GIF",
];

// check if uploaded file is an image based on Magic Bytes
function isImageUploaded() {
    global $magicBytes;

    // Check if uploaded file is a valid image
    $tmpFilePath = $_FILES['profile_picture']['tmp_name'];
    $file = fopen($tmpFilePath, "rb");
    if (!$file) {
        return false; // Unable to open file
    }

    // Read the first few bytes of the file
    $bytes = fread($file, 8);
    fclose($file);

    // Check if any Magic Bytes match
    foreach ($magicBytes as $format => $magicByte) {
        if (strncmp($bytes, $magicByte, strlen($magicByte)) === 0) {
            return true; // File is an image
        }
    }
    return false; // No Magic Bytes matched
}

function deletePicture() {
    global $username, $conn;
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row['profile_picture'] !== "default_profile.png") {
    system("rm -rf uploads/".$row['profile_picture']);
    if (!file_exists("uploads/test.png")) {
            // File deleted successfully
            // Update database entry if needed (prepared statements!)
            $sql = "UPDATE users SET profile_picture='default_profile.png' WHERE username='$username'";
            mysqli_query($conn, $sql);
            $_SESSION['success'] = "Profile picture deleted successfully. <a href='profile.php'>profile</a>";
        } else {
            // Handle deletion error gracefully
            $_SESSION['error'] = "Error deleting image.";
        }
    } else {
        $_SESSION['error'] = "You don't have picture.";
    }
}

$username = $_POST['username'];

// Update email in the database
if (isset($_POST['submit'])) {
    // Check for empty fields
    if (empty($_POST['email']) && empty($_FILES['profile_picture'])) {
        $_SESSION['error'] = "Please fill this form to edit account.";
        header("location: edit.php");
        exit();
    }
    if ($_POST['email']) {
        $new_email = $_POST['email'];
        $sql = "UPDATE users SET email='$new_email' WHERE username='$username'";
        mysqli_query($conn, $sql);
        $_SESSION['success'] = "Email updated successfully! <a href='profile.php'>profile</a>";
    }
    // Check if a file was uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Validate size and image formats
        $filename =  $_FILES['profile_picture']['name'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (isImageUploaded() && $_FILES['profile_picture']['size'] <= $max_size) {
            deletePicture(); // Delete the previous file
            // Move the uploaded file to the desired directory
            $target_dir = 'uploads/'; // Replace with your desired upload directory
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_dir . $filename);

            // Update the user's profile picture in the database
            $sql = "UPDATE users SET profile_picture='$filename' WHERE username='$username'";
            mysqli_query($conn, $sql);
            // Display a success message
            $_SESSION['success'] = "Profile picture updated successfully! <a href='profile.php'>profile</a>";
            unset($_SESSION['error']);
        } else {
            // Display an error message
            $_SESSION['error'] = "Invalid file type or size. Please upload a JPG, JPEG, PNG or GIF image smaller than 5MB.";
        }
    }
    if (($_POST['email']) && $_FILES['profile_picture']['error'] == 0) {
        $_SESSION['success'] = "Email and profile picture updated successfully. <a href='profile.php'>profile</a>";
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
        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
        <input type="email" name="email" placeholder="New Email" class="form-control"><br><br>
        <h3>Profile picture: </h3>
        <div class="custom-file">
            <label class="custom-file-label" for="profile_picture">Choose file</label>
            <input type="file" class="custom-file-input" name="profile_picture" id="profile_picture"><br><br>
        </div>
        <button type="submit" name="submit" class="btn btn-success btn-sm">Save Changes</button>
        <button type="submit" name="delete_picture" class="btn btn-danger ml-3 btn-sm">Delete Picture</button><br><br>
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