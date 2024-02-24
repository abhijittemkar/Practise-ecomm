<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Userlogin.php");
    exit();
}

// Connect to the database (replace with your actual connection code)
$conn = new mysqli("localhost", "root", "", "ecommerce");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize the inputs
$product_id = mysqli_real_escape_string($conn, $_GET['product_id']);

// Delete the product from the cart
$user_id = $_SESSION['user_id'];
$delete_query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$conn->query($delete_query);

header("Location: cart.php?successMsg=Item removed from cart successfully");

$conn->close();
?>
