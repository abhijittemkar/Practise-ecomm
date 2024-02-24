<?php
session_start();

// Check if the admin is not authenticated
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <link rel="stylesheet" href="dashboard_style.css"> <!-- Include your dashboard styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
                        font-family: 'Arial', sans-serif;
                        background-color: #f7f7f7;
                        margin: 0;
                        padding: 0;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                    }

                    header {
                        background-color: #333;
                        color: #fff;
                        text-align: center;
                        padding: 10px 0;
                        width: 100%;
                    }

        header h1 {
            margin: 0;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 550px;
            margin: 40px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
            color: #333;
        }

        input[type="file"] {
            background-color: transparent;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .logout-btn a {
            background-color: #ff0000;
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 20px;
        }

        .logout-btn a:hover {
            background-color: #cc0000;
        }
    </style>
</head>

<body>
    <header>
        <h1>Admin Dashboard - Insert Product</h1>
    </header>

    <form action="insert.php" method="post" enctype="multipart/form-data">

        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required>

        <label for="brand">Brand:</label>
        <input type="text" name="brand" required>

        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label for="country_of_origin">Country of Origin:</label>
        <input type="text" name="country_of_origin" required>

        <label for="materials">Materials:</label>
        <input type="text" name="materials" required>

        <label for="warranty">Warranty:</label>
        <input type="text" name="warranty">

        <label for="product_description">Product Description:</label>
        <textarea name="product_description" rows="4" required></textarea>

        <label for="image">Product Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" name="submit">Insert Product</button>
        <div class="logout-btn">
            <a href="admin_dashboard.php">Back to Admin Dashboard</a>
        </div>

    </form>
</body>

</html>
