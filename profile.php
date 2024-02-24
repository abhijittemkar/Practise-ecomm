<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Userlogin.php");
    exit();
}

// Connect to the database (replace with your actual connection code)
$conn = new mysqli("localhost", "root", "", "ecommerce");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE user_id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_details = mysqli_fetch_assoc($user_result);
    $username = $user_details['username'];
    $email = $user_details['email'];
    $address = $user_details['address'];
    $phone = $user_details['phone'];
    $sex = $user_details['sex'];
} else {
    // Handle the case where user details are not found
    // You may redirect the user to update their profile or handle it as appropriate for your application
    echo "User details not found. Please update your profile.";
    exit();
}

// Function to get user's previous orders
function getUserPreviousOrders($user_id, $conn)
{
    $orders = array();

    // Query to retrieve user's previous orders
    $order_query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
    $order_result = mysqli_query($conn, $order_query);

    if ($order_result && mysqli_num_rows($order_result) > 0) {
        while ($order = mysqli_fetch_assoc($order_result)) {
            $orders[] = $order;
        }
    }

    return $orders;
}

// Call the function to get user's previous orders
$previousOrders = getUserPreviousOrders($user_id, $conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="style_2.css" />
    <script src="https://kit.fontawesome.com/716afbfda6.js" crossorigin="anonymous"></script>
    <script src="script3.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

    <section id="header">
          <a href="index.php"><img src="img/amazon logo.jpg" class="logo" alt="logo" /></a>
          <div>
            <ul id="navbar">
              <li><a href="index.php">Shop</a></li>
              <li><a class="active" href="profile.php">Profile</a></li>
              <li><a href="blog.html">Blog</a></li>
              <li><a href="about.html">About</a></li>
              <li><a href="contact.html">Contact</a></li>
              <li id="lg-bag">
    <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
              </li>
              <a href="#" id="close"><i class="fa-solid fa-xmark"></i></a>
            </ul>
          </div>
          <div id="mobile">
            <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
          </div>
        </section>

<div class="container" style="max-width: 800px; margin: 50px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h1 style="text-align: center;">User Profile</h1>
    <?php if ($successMsg !== ""): ?>
        <div class="successMsg" style="color: #4caf50; background-color: #dff0d8; margin: 20px 0; padding: 10px; border-radius: 5px;"><?php echo $successMsg; ?></div>
    <?php endif; ?>
    <form action="update_profile.php" method="POST" style="display: grid; gap: 10px;">
        <label for="username" style="font-weight: bold;">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" readonly style="width: 100%; padding: 10px; box-sizing: border-box;">

        <label for="email" style="font-weight: bold;">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" style="width: 100%; padding: 10px; box-sizing: border-box;">

        <label for="address" style="font-weight: bold;">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $address; ?>" style="width: 100%; padding: 10px; box-sizing: border-box;">

        <label for="phone" style="font-weight: bold;">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" style="width: 100%; padding: 10px; box-sizing: border-box;">

        <label for="sex" style="font-weight: bold;">Sex:</label>
        <select id="sex" name="sex" style="width: 100%; padding: 10px; box-sizing: border-box;">
            <option value="male" <?php echo ($sex == 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo ($sex == 'female') ? 'selected' : ''; ?>>Female</option>
        </select>

        <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">Update Profile</button>
    </form>

    <!-- Logout button -->
    <form action="logout.php" method="POST">
        <button type="submit" style="background-color: #ff6666; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">Logout</button>
    </form>
</div>



 <!-- Previous Orders Section -->
 <h2 style="margin-top: 30px;">Previous Orders</h2>

 <?php
 if (!empty($previousOrders)) {
     foreach ($previousOrders as $order) {
         // Display order details
         echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; display: flex; align-items: center;'>";

         // Left side (order details)
         echo "<div style='flex: 1;'>";
         echo "<p style='font-weight: bold; font-size: 16px;'>Order ID: " . $order['order_id'] . "</p>";
         echo "<p>Total Amount: " . $order['total_amount'] . "₹</p>";
         echo "<p>Date: " . $order['order_date'] . "</p>";
         echo "<p>Delivery Address: " . $order['address'] . "</p>";

         // Fetch products for the current order
         $orderId = $order['order_id'];
         $orderItemsQuery = "SELECT products.*, order_items.quantity as order_quantity FROM order_items
                             JOIN products ON order_items.product_id = products.product_id
                             WHERE order_items.order_id = '$orderId'";
         $orderItemsResult = mysqli_query($conn, $orderItemsQuery);

         if ($orderItemsResult && mysqli_num_rows($orderItemsResult) > 0) {
             echo "<h3 style='margin-top: 10px;'>Order Items:</h3>";
             echo "<table style='width: 100%; border-collapse: collapse;'>";
             echo "<tr style='border-bottom: 1px solid #ccc;'>";
             echo "<th style='padding: 10px; text-align: left;'>Product</th>";
             echo "<th style='padding: 10px; text-align: left;'>Price</th>";
             echo "<th style='padding: 10px; text-align: left;'>Quantity</th>";
             echo "<th style='padding: 10px; text-align: left;'>Image</th>";
             echo "<th style='padding: 10px; text-align: left;'>Action</th>";
             echo "</tr>";
             while ($orderItem = mysqli_fetch_assoc($orderItemsResult)) {
                 echo "<tr style='border-bottom: 1px solid #ccc;'>";
                 echo "<td style='padding: 10px; text-align: left;'><strong>" . $orderItem['product_name'] . "</strong></td>";
                 echo "<td style='padding: 10px; text-align: left;'>" . $orderItem['price'] . "₹</td>";
                 echo "<td style='padding: 10px; text-align: left;'>" . $orderItem['order_quantity'] . "</td>";
                 echo '<td style="padding: 10px; text-align: left;"><img src="img/products/' . $orderItem['image'] . '" alt="product image" width="100" height="100"></td>';
                 // Add a button or link for "Buy Again" here, which can trigger the addition to the cart
                 // For simplicity, let's assume the product ID is $orderItem['product_id']
                 echo "<td style='padding: 10px; text-align: left;'><button style='background-color: #4CAF50; color: white; padding: 8px; border: none; border-radius: 4px; cursor: pointer;' onclick=\"buyAgain(" . $orderItem['product_id'] . ")\">Buy Again</button></td>";
                 echo "</tr>";
             }
             echo "</table>";
         }
         echo "</div>"; // End of left side

         echo "</div>"; // End of flex container
     }
 } else {
     echo "<p>No previous orders found.</p>";
 }
 ?>


        <script>
                function buyAgain(productId) {
                    // You can implement the logic to add the selected product to the cart
                    // For simplicity, let's assume an AJAX request to a PHP script that handles cart addition
                    // Adjust this based on your actual implementation
                    // Example AJAX request:

                    $.ajax({
                        url: 'add_to_cart.php',
                        method: 'POST',
                        data: { product_id: productId, quantity: 1 },
                        success: function(response) {
                    alert("Product with ID " + productId + " is added to the cart.");
                            console.log(response);
                        },
                        error: function(error) {
                    alert("Adding product with ID " + productId + " is already in cart.");
                            console.error(error);
                        }
                    });

                }
            </script>



        <!-- Logout button -->
        <form action="logout.php" method="POST">
            <button type="submit" style="background-color: #ff6666; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">Logout</button>
        </form>
    </div>

    <footer class="section-p1">
            <!-- Your footer content goes here -->
                  <div class="col">
                    <img src="img/amazon logo.jpg" alt="logo" class="logo1" />
                    <h4>Contacts</h4>
                    <p>
                      <strong>Address :</strong> 930 Progress Avenue, Toronto, Canada
                    </p>
                    <p><strong>Phone :</strong> +1 43434343232</p>
                    <p><strong>Hours :</strong> 10:00 to 9:00 Mon -Sat</p>
                    <div class="follow">
                      <h4>Follow Us</h4>
                      <div class="icon">
                        <i class="fab fa-facebook-f"></i>
                        <i class="fab fa-twitter"></i>
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-pinterest-p"></i>
                        <i class="fab fa-youtube"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <h4>About</h4>
                    <a href="about.html">About Us</a>
                    <a href="#">Delivery Information</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
                    <a href="contact.html">Contact Us</a>
                  </div>

                  <div class="col">
                    <h4>My Account</h4>
                    <a href="profile.php">Sign In</a>
                    <a href="cart.php">View Cart</a>
                    <a href="#">My Wishlist</a>
                    <a href="#">Track My Order</a>
                    <a href="#">Help</a>
                  </div>

                  <div class="col-install">
                    <h4>Install App</h4>
                    <p>From App Store or Google Play</p>
                    <div class="row">
                      <img src="img/pay/app.jpg" alt="pay" />
                      <img src="img/pay/play.jpg" alt="pay" />
                    </div>
                    <p>Secured Payment Gateway</p>
                    <img src="img/pay/pay.png" alt="pay" />
                  </div>
                  <div class="copyright">
                    <p>@2023 abhijittemkar3@gmail.com</p>
                  </div>
                </footer>
                    <script src="script.js"></script>

</body>
</html>
