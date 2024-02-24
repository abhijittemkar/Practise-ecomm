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

// Check if the product ID is provided
if (!isset($_GET['product_id'])) {
    echo "Product ID not provided.";
    exit();
}

$product_id = $_GET['product_id'];

// Fetch product data from the database
$sql = "SELECT * FROM products WHERE product_id='$product_id'";
$result = $conn->query($sql);

// Check if product data is retrieved
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Fetch other product details as needed
    $brand = $row['brand'];
    $product_name = $row['product_name'];
    $price = $row['price'];
    $country_of_origin = $row['country_of_origin'];
    $materials = $row['materials'];
    $warranty = $row['warranty'];
    $product_description = $row['product_description'];
    $image = $row['image'];
} else {
    echo "Product not found.";
    exit();
}

if (isset($_POST['delete_product'])) {

    // Delete product logic goes here
    $delete_sql = "DELETE FROM products WHERE product_id='$product_id'";
    $delete_result = $conn->query($delete_sql);

    if ($delete_result) {
        // Redirect to a page or display a success message
        header('Location: products_list.php');
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
        exit();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
  <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .dashboard-container {
            max-width: 950px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

         .image-preview {
                    max-width: 200px; /* Adjust the max-width as needed */
                    margin-top: 10px;
                }

                .image-preview img {
                    max-width: 100%; /* Ensure the image does not exceed its container's width */
                    height: auto; /* Maintain the aspect ratio */
                }
        h1 {
            text-align: center;
        }

        form {
            display: grid;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;


        }
    </style>
    <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this product?");
            }
        </script>
    </head>
<body>
<div class = "container" >
    <h1>Edit Product</h1>

    <form action="update_product.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" value="<?php echo $brand; ?>">

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product_name; ?>">

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo $price; ?>">

        <label for="country_of_origin">Country of Origin:</label>
        <input type="text" id="country_of_origin" name="country_of_origin" value="<?php echo $country_of_origin; ?>">

        <label for="materials">Materials:</label>
        <input type="text" id="materials" name="materials" value="<?php echo $materials; ?>">

        <label for="warranty">Warranty:</label>
        <input type="text" id="warranty" name="warranty" value="<?php echo $warranty; ?>">

        <label for="product_description">Product Description:</label>
        <textarea id="product_description" name="product_description"><?php echo $product_description; ?></textarea>



        <!-- Display existing image -->
                    <?php if (!empty($image)): ?>
                        <div class="image-preview">
                            <label for="existingImage">Existing Image:</label>
                            <img src="img/products/<?php echo $image; ?>" alt="Existing Image">
                        </div>
                    <?php endif; ?>

        <!-- Add a new input field for image upload -->
            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image">

        <button type="submit">Update Product</button>

        <!-- Delete button -->
            <button type="submit" name="delete_product" style="background-color: #ff6666; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;" formnovalidate onclick="return confirmDelete()">Delete Product</button>
    </form>
</div>
</body>
</html>
