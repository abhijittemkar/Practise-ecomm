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

// Fetch pending orders
$sql = "SELECT * FROM orders WHERE order_status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content and stylesheets -->
</head>

<body>
    <h1>View Pending Orders</h1>

    <!-- Back to Dashboard button -->
    <a href="admin_dashboard.php">
        <button style="background-color: #008CBA; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">
            Back to Dashboard
        </button>
    </a>

    <!-- Pending Orders listing -->
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Amount</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Email</th>
            <th>Payment Method</th> <!-- New column for payment method -->
            <th>Order Date</th>
            <th>Action</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['order_id'] . '</td>';
                echo '<td>' . $row['user_id'] . '</td>';
                echo '<td>' . $row['total_amount'] . '</td>';
                echo '<td>' . $row['phone'] . '</td>';
                echo '<td>' . $row['address'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['payment_method'] . '</td>'; // Display payment method
                echo '<td>' . $row['order_date'] . '</td>';

                // Add action links for each order
                echo '<td>';
                echo '<a href="manage_orders.php?action=dispatched&order_id=' . $row['order_id'] . '">Mark Dispatched</a> | ';
                echo '<a href="manage_orders.php?action=delivered&order_id=' . $row['order_id'] . '">Mark Delivered</a> | ';
                echo '<a href="manage_orders.php?action=returned&order_id=' . $row['order_id'] . '">Mark Returned</a>';
                echo '</td>';

                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="9">No pending orders found.</td></tr>';
        }
        ?>
    </table>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
