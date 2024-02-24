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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $brand = $_POST['brand'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $country_of_origin = $_POST['country_of_origin'];
    $materials = $_POST['materials'];
    $warranty = $_POST['warranty'];
    $product_description = $_POST['product_description'];

    // Check if a new image file is provided
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "img/products/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $generated_filename = "f" . $product_id . ".$imageFileType";
        $target_file = $target_dir . $generated_filename;

        // Check file size, type, and upload
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
        } else {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        // Update the product record with the new filename
        $update_sql = "UPDATE products SET image = '$generated_filename' WHERE product_id = '$product_id'";
        if (!$conn->query($update_sql)) {
            echo "Error updating product image: " . $conn->error;
        }
    }

    // Check if the delete button is pressed
    if (isset($_POST['delete_product'])) {
        // Delete product logic goes here
        $delete_sql = "DELETE FROM products WHERE product_id='$product_id'";
        $delete_result = $conn->query($delete_sql);

        if ($delete_result) {
            // Redirect to a page or display a success message
            header('Location: admin_dashboard.php?successMsg=Product deleted successfully');
            exit();
        } else {
            echo "Error deleting product: " . $conn->error;
            exit();
        }
    }

    // Update the remaining product details in the database
    $sql = "UPDATE products SET brand='$brand', product_name='$product_name', price='$price',
            country_of_origin='$country_of_origin', materials='$materials', warranty='$warranty',
            product_description='$product_description' WHERE product_id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        $successMsg = "The product has been updated successfully.";
        header("Location: admin_dashboard.php?successMsg=" . urlencode($successMsg));
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
