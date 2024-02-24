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

// Retrieve cart items for the logged-in user
if ($isUserLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT products.*, cart.quantity as cart_quantity FROM cart JOIN products ON cart.product_id = products.product_id WHERE cart.user_id = '$user_id'";
    $cart_result = mysqli_query($conn, $cart_query);

    // Calculate subtotal based on cart items
    $subtotal = 0;
    while ($cart_item = mysqli_fetch_assoc($cart_result)) {
        $subtotal += $cart_item['cart_quantity'] * $cart_item['price'];
    }

    // Calculate tax (you can adjust the tax rate as needed)
    $tax_rate = 0.1; // 10%
    $tax = $subtotal * $tax_rate;

    // Calculate total including tax
    $total = $subtotal + $tax;

    // Return JSON response
    echo json_encode(['subtotal' => $subtotal, 'tax' => $tax, 'total' => $total]);
}
?>
