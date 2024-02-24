<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if (isset($_POST['submit'])) {
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

    // Sanitize and validate inputs
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = floatval($_POST['price']);
    $country_of_origin = mysqli_real_escape_string($conn, $_POST['country_of_origin']);
    $materials = mysqli_real_escape_string($conn, $_POST['materials']);
    $warranty = mysqli_real_escape_string($conn, $_POST['warranty']);
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);

    // Upload image
    $target_dir = "img/products/";
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    // Insert the product details into the database
    $sql = "INSERT INTO products (brand, product_name, price, country_of_origin, materials, warranty, product_description, image)
            VALUES ('$brand', '$product_name', $price, '$country_of_origin', '$materials', '$warranty', '$product_description', '')";

    if ($conn->query($sql) === TRUE) {
        // Get the auto-incremented product ID
        $product_id = mysqli_insert_id($conn);

        // Generate a unique filename based on product ID
        $generated_filename = "f" . $product_id . ".$imageFileType";
        $target_file = $target_dir . $generated_filename;

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
        } else {
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Check if $uploadOk is set to 0 by an error
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";

                        // Update the product record with the correct filename
                        $update_sql = "UPDATE products SET image = '$generated_filename' WHERE product_id = $product_id";
                        $conn->query($update_sql);

                        echo "Product details updated successfully.";
                        header("Location: admin_dashboard.php");
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
