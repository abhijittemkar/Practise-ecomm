<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location: UserLogin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "ecommerce");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the form
$user_id = $_SESSION['user_id'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$sex = $_POST['sex'];;

// Update user profile
// Update user information in the database
$sql = "UPDATE users SET email='$email', address='$address', phone='$phone', sex='$sex' WHERE user_id='$user_id'";

if ($conn->query($sql) === TRUE) {
    $successMsg = "Profile updated successfully.";
            header("Location: profile.php?successMsg=" . urlencode($successMsg));

} else {
    echo "Error updating profile: " . $conn->error;
    header("location: profile.php");
}

// Close the database connection
$conn->close();
?>


