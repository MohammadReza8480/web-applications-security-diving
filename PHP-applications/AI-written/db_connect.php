<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$database = "testapp";
$username = "test";
$password = "test";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// $sql = "SELECT * FROM users";
// $result = mysqli_query($conn, $sql);
// while ($row = mysqli_fetch_assoc($result)) {
//     echo "<table>";
//     echo "<tr><th>username</th><th>email</th><th>admin</th></tr>"; // Add headers for clarity

//     // Loop through rows and display data
//     echo "<tr>";
//     echo "<td>" . $row['username'] . "</td>";
//     echo "<td>" . $row['email'] . "</td>";
//     echo "<td>" . $row['admin'] . "</td>";
//     // ... (repeat for all columns)
//     echo "</tr>";

//     echo "</table>";
// }
?>
