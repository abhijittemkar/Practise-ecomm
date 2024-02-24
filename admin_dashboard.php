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

$successMsg = isset($_GET['succ1essMsg']) ? $_GET['successMsg'] : '';

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = empty($search) ? '' : "WHERE brand LIKE '%$search%' OR product_name LIKE '%$search%'";

// Fetch products from the database
$sql = "SELECT * FROM products $whereClause";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add your CSS stylesheets and other dependencies here -->
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <h1>Welcome to the Admin Dashboard</h1>

        <div class="dashboard-options">
            <!-- Search form -->
            <form method="GET" action="admin_dashboard.php">
                <input type="text" name="search" placeholder="Search products">
                <button type="submit">Search</button>
            </form>

            <?php if ($successMsg !== "") : ?>
                <div class="successMsg"><?php echo $successMsg; ?></div>
            <?php endif; ?>

            <div class="action-buttons">
                <!-- Add New Product button -->
                <a href="insert_product.php" class="dashboard-button">
                    <i class="fas fa-plus"></i> Add New Product
                </a>

                <!-- View Pending Orders button -->
                <a href="view_pending_orders.php" class="dashboard-button">
                    <i class="fas fa-clock"></i> View Pending Orders
                </a>

                <!-- View Dispatched Orders button -->
                <a href="view_dispatched_orders.php" class="dashboard-button">
                    <i class="fas fa-truck"></i> View Dispatched Orders
                </a>

                <!-- View Completed Orders button -->
                <a href="view_completed_orders.php" class="dashboard-button">
                    <i class="fas fa-check-circle"></i> View Completed Orders
                </a>

                <!-- Refresh button -->
                <button onclick="refreshPage()" class="dashboard-button">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>

                <!-- Logout button -->
                <a href="logout.php" class="dashboard-button logout-button">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Product listing -->
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Brand</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Country of Origin</th>
                    <th>Materials</th>
                    <th>Warranty</th>
                    <th>Product Description</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['product_id'] . '</td>';
                            echo '<td>' . $row['brand'] . '</td>';
                            echo '<td>' . $row['product_name'] . '</td>';
                            echo '<td>' . $row['price'] . '</td>';
                            echo '<td>' . $row['country_of_origin'] . '</td>';
                            echo '<td>' . $row['materials'] . '</td>';
                            echo '<td>' . $row['warranty'] . '</td>';

                            // Display a truncated version of the product description with a "View More" link
                            echo '<td>';
                            echo '<div class="product-description">';
                            echo substr($row['product_description'], 0, 100); // Displaying first 100 characters
                            if (strlen($row['product_description']) > 100) {
                                echo '... <a href="#" class="view-more" data-description="' . htmlspecialchars($row['product_description']) . '">View More</a>';
                            }
                            echo '</div>';
                            echo '</td>';

                            echo '<td><img src="img/products/' . $row['image'] . '" alt="product image" width="100" height="100"></td>';

                            // Edit button
                            echo '<td><a href="edit_product.php?product_id=' . $row['product_id'] . '"><i class="fas fa-edit"></i> Edit</a></td>';

                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10">No products found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click event listeners to "View More" links
            var viewMoreLinks = document.querySelectorAll('.view-more');
            viewMoreLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    var description = this.getAttribute('data-description');
                    alert(description); // Replace with your preferred way of displaying the full description
                });
            });
        });
    </script>
    <script>
        // JavaScript function to refresh the page
        function refreshPage() {
            location.reload();
        }
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
