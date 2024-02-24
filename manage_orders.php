<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "ecommerce");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an action is specified
if (isset($_GET['action']) && isset($_GET['order_id'])) {
    $action = $_GET['action'];
    $order_id = $_GET['order_id'];

    // Handle different actions
    switch ($action) {
        case 'dispatched':
            markDispatched($conn, $order_id);
            break;
        case 'delivered':
            markDelivered($conn, $order_id);
            break;
        case 'returned':
            markReturned($conn, $order_id);
            break;
        // Add more actions as needed
        default:
            // Handle invalid action
            echo "Invalid action.";
            break;
    }
}

// Function to mark an order as dispatched
function markDispatched($conn, $order_id) {
    $update_query = "UPDATE orders SET order_status = 'dispatched' WHERE order_id = '$order_id'";
    if ($conn->query($update_query)) {
        echo "Order marked as dispatched successfully.";
    } else {
        echo "Error marking order as dispatched: " . $conn->error;
    }
}

// Function to mark an order as delivered
function markDelivered($conn, $order_id) {
    $update_query = "UPDATE orders SET order_status = 'delivered' WHERE order_id = '$order_id'";
    if ($conn->query($update_query)) {
        echo "Order marked as delivered successfully.";
    } else {
        echo "Error marking order as delivered: " . $conn->error;
    }
}

// Function to mark an order as returned
function markReturned($conn, $order_id) {
    $update_query = "UPDATE orders SET order_status = 'returned' WHERE order_id = '$order_id'";
    if ($conn->query($update_query)) {
        echo "Order marked as returned successfully.";
    } else {
        echo "Error marking order as returned: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
