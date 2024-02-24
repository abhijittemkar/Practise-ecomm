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

// Fetch completed orders
$sql = "SELECT * FROM orders WHERE order_status = 'delivered'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content and stylesheets -->
</head>

<body>
    <h1>View Completed Orders</h1>

    <!-- Back to Dashboard button -->
    <a href="admin_dashboard.php">
        <button style="background-color: #008CBA; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">
            Back to Dashboard
        </button>
    </a>

    <!-- Completed Orders listing -->
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
            <!-- Add more columns as needed -->
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
                // Add more columns as needed
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="8">No completed orders found.</td></tr>';
        }
        ?>
    </table>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
