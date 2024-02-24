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
$product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
$quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

// Check if the product is already in the cart for the user
$user_id = $_SESSION['user_id'];
$check_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$check_result = $conn->query($check_query);

if ($check_result->num_rows > 0) {
    // Product is already in the cart, update the quantity
    $update_query = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
    $conn->query($update_query);
    header("Location: product_details.php?product_id=$product_id&successMsg=Cart updated successfully");
} else {
    // Product is not in the cart, add it
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
    $conn->query($insert_query);
    header("Location: product_details.php?product_id=$product_id&successMsg=Item added to cart successfully");
}

$conn->close();
?>
