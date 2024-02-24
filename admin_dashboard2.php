

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

$successMsg = isset($_GET['successMsg']) ? $_GET['successMsg'] : '';

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
    <!-- Include your CSS stylesheets and other dependencies here -->
</head>
<body>
    <h1>Welcome to the Admin Dashboard</h1>

    <!-- Search form -->
    <form method="GET" action="admin_dashboard.php" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search products">
        <button type="submit">Search</button>
    </form>

<?php if ($successMsg !== ""): ?>
            <div class="successMsg" style="color: #4caf50;background-color: #dff0d8;margin: 20px 0;padding: 10px;border-radius: 5px;"><?php echo $successMsg; ?></div>
        <?php endif; ?>


    <!-- Button to add new product -->
    <a href="insert_product.php">
        <button style="background-color: #4CAF50; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">
            Add New Product
        </button>
    </a>

    <!-- Back to All Products button -->
    <?php if (!empty($search)) : ?>
        <a href="admin_dashboard.php">
            <button style="background-color: #008CBA; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">
                Back to All Products
            </button>
        </a>
    <?php endif; ?>

    <!-- Refresh button -->
        <button onclick="refreshPage()" style="background-color: #007bff; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">
            Refresh
        </button>

        <script>
                // JavaScript function to refresh the page
                function refreshPage() {
                    location.reload();
                }
            </script>

      <!-- Logout button -->
        <a href="logout.php">
            <button style="background-color: #FF0000; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; float: right;">
                Logout
            </button>
        </a>

    <!-- Product listing -->
    <table border="1">
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
        </tr>

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
                echo '<td>' . $row['product_description'] . '</td>';
                echo '<td><img src="img/products/' . $row['image'] . '" alt="product image" width="100" height="100"></td>';

                // Edit button
                            echo '<td><a href="edit_product.php?product_id=' . $row['product_id'] . '">Edit</a></td>';


                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="10">No products found.</td></tr>';
        }
        ?>

    </table>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
