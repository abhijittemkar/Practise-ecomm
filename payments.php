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

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$cart_query = "SELECT products.*, cart.quantity as cart_quantity FROM cart JOIN products ON cart.product_id = products.product_id WHERE cart.user_id = '$user_id'";
$cart_result = mysqli_query($conn, $cart_query);

// Check if the cart is not empty
if ($cart_result && mysqli_num_rows($cart_result) > 0) {
    // Calculate subtotal based on cart items
    $subtotal = 0;
    while ($cart_item = mysqli_fetch_assoc($cart_result)) {
        $subtotal += $cart_item['cart_quantity'] * $cart_item['price'];
    }

    // Calculate tax (adjust the tax rate as needed)
    $tax_rate = 0.1; // 10%
    $tax = $subtotal * $tax_rate;

    // Calculate total including tax
    $total = $subtotal + $tax;

    // Fetch user details
    $user_query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $user_result = mysqli_query($conn, $user_query);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_details = mysqli_fetch_assoc($user_result);
        $phone = $user_details['phone'];
        $address = $user_details['address'];
        $email = $user_details['email'];
    } else {
        // Handle the case where user details are not found
        // You may redirect the user to update their profile or handle it as appropriate for your application
        echo "User details not found. Please update your profile.";
        exit();
    }

    // Store order details in the session
    $_SESSION['order_details'] = array(
        'subtotal' => $subtotal,
        'tax' => $tax,
        'total' => $total
    );
} else {
    // Redirect to cart page with a message if the cart is empty
    header("Location: cart.php?errorMsg=Your cart is empty. Shop now!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style_2.css" />
    <title>Payment Page</title>
    <script src="https://kit.fontawesome.com/716afbfda6.js" crossorigin="anonymous"></script>
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="img/amazon logo.jpg" class="logo" alt="logo" /></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Shop</a></li>
                <li><a href="index.html">Profile</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li id="lg-bag">
                    <a class="active" href="cart.html"><i class="fa-solid fa-bag-shopping"></i></a>
                </li>
                <a href="#" id="close"><i class="fa-solid fa-xmark"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.html"><i class="fa-solid fa-bag-shopping"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

  <style>
          body {
              font-family: Arial, sans-serif;
              background-color: #f4f4f4;
              margin: 0;
              padding: 0;
          }

          #order-summary {
              background-color: #fff;
              border: 1px solid #ccc;
              border-radius: 5px;
              padding: 20px;
              margin-top: 20px;
          }

          table {
              width:40%;
              border-collapse: collapse;
              margin-top: 10px;
          }

          table, th, td {
              border: 1px solid #ddd;
          }

          th, td {
              padding: 10px;
              text-align: left;
          }

          th {
              background-color: #f2f2f2;
          }

          h2 {
              color: #333;
          }
      </style>
  </head>
  <body>

      <h2 style="text-align: center;">Payment Page</h2>

      <!-- Display Order Summary -->
      <section id="order-summary">
          <h2>Order Summary</h2>
          <table>
              <tr>
                  <td>Subtotal</td>
                  <td><?php echo $subtotal; ?>₹</td>
              </tr>
              <tr>
                  <td>Tax</td>
                  <td><?php echo $tax; ?>₹</td>
              </tr>
              <tr>
                  <td>Total</td>
                  <td><?php echo $total; ?>₹</td>
              </tr>
          </table>
      </section>

    <!-- Display payment options and form -->
    <?php
    // Dummy payment processing logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        // Handle payment processing here

        // Assuming $conn is your database connection
        $user_id = $_SESSION['user_id'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];

        // Insert order details
        $insert_order_query = "INSERT INTO orders (user_id, total_amount, phone, address, email, payment_method, order_status) VALUES ('$user_id', '$total', '$phone', '$address', '$email', '$_POST[payment_method]', 'Pending')";
        $result = mysqli_query($conn, $insert_order_query);


            if ($result) {
                // Retrieve the generated order_id
                $order_id_query = "SELECT order_id FROM orders WHERE user_id = '$user_id' ORDER BY order_id DESC LIMIT 1";
                $order_id_result = mysqli_query($conn, $order_id_query);

                if ($order_id_result && mysqli_num_rows($order_id_result) > 0) {
                    $order_id_row = mysqli_fetch_assoc($order_id_result);
                    $order_id = $order_id_row['order_id'];


                    // Insert each item from the cart into order_items
                    $cart_query = "SELECT * FROM cart WHERE user_id = '$user_id'";
                    $cart_result = mysqli_query($conn, $cart_query);

                    while ($cart_item = mysqli_fetch_assoc($cart_result)) {
                        $product_id = $cart_item['product_id'];
                        $quantity = $cart_item['quantity'];

                        // Fetch the product details from the products table
                        $product_query = "SELECT price FROM products WHERE product_id = '$product_id'";
                        $product_result = mysqli_query($conn, $product_query);

                        if ($product_result && mysqli_num_rows($product_result) > 0) {
                            $product_data = mysqli_fetch_assoc($product_result);
                            $item_price = $product_data['price'];
                            $tax_rate = 10;

                            // Calculate total price paid for the quantity
                            $total_price = $item_price * $quantity;

                            // Calculate tax amount
                            $tax_amount = ($total_price * $tax_rate) / 100;

                            // Calculate total after taxes
                            $total_after_tax = $total_price + $tax_amount;

                            // Insert into order_items
                            $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, item_price, total_price, tax_amount, total_after_tax) VALUES ('$order_id', '$product_id', '$quantity', '$item_price', '$total_price', '$tax_amount', '$total_after_tax')";
                            mysqli_query($conn, $insert_item_query);
                        } else {
                            // Handle the case where product details are not found
                            echo "Product details not found for product ID: $product_id";
                        }
                    }



                    // Delete cart items
                    $delete_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
                    mysqli_query($conn, $delete_cart_query);

                    // For demonstration purposes, let's assume the payment is successful
                    echo "<p>Payment successful! Redirecting to confirmation page...</p>";

                    // Redirect to the order confirmation page
                    header("refresh:2;url=order_confirmation.php?order_id=$order_id");
                    exit();
                } else {
                    // Handle the case where order_id retrieval fails
                    echo "Error retrieving order_id.";
                    exit();
                }
            } else {
                // Print MySQL error if any
                echo "Error: " . mysqli_error($conn);
            }

            }
    ?>


   <!-- Dummy payment form -->
   <form method="post" style="max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc;">

       <!-- Payment options and form fields go here -->
       <label for="payment_method" style="display: block; margin-bottom: 10px;">Select Payment Method:</label>
       <select name="payment_method" id="payment_method" style="width: 100%; padding: 8px; margin-bottom: 20px;">

           <option value="online">Online Payment</option>
           <option value="offline">Offline Payment</option>
       </select>

       <!-- Auto-populated user details -->
       <label for="contact" style="display: block; margin-bottom: 10px;">Contact:</label>
       <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" required style="width: 100%; padding: 8px; margin-bottom: 20px;">

       <label for="address" style="display: block; margin-bottom: 10px;">Address:</label>
       <textarea name="address" id="address" required style="width: 100%; padding: 8px; margin-bottom: 20px;"><?php echo $address; ?></textarea>

       <label for="email" style="display: block; margin-bottom: 10px;">Email:</label>
       <input type="email" name="email" id="email" value="<?php echo $email; ?>" required style="width: 100%; padding: 8px; margin-bottom: 20px;">

       <button type="submit" name="submit" style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px;">Submit Payment</button>
   </form>


<footer class="section-p1">
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
        <a href="Userlogin.php">Sign In</a>
        <a href="#">View Cart</a>
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
</body>
</html>

