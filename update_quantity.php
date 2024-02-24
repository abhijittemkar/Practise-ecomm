<?php
session_start();

// Connect to your database (use your actual database connection code)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
$isUserLoggedIn = isset($_SESSION['user_id']);

if ($isUserLoggedIn && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = max(1, $_POST['quantity']); // Ensure quantity is at least 1

    // Update the quantity in the cart table
    $update_query = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
    mysqli_query($conn, $update_query);
}
?>
