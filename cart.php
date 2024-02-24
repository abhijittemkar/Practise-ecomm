<?php
session_start();

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

// Check if the user is logged in
$isUserLoggedIn = isset($_SESSION['user_id']);

// Handle quantity update if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($isUserLoggedIn && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = max(1, $_POST['quantity']); // Ensure quantity is not below 1

        // Update quantity in the database
        $update_query = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '{$_SESSION['user_id']}' AND product_id = '$product_id'";
        mysqli_query($conn, $update_query);

        // Redirect back to the cart page
        header("Location: cart.php");
        exit();
    }
}

// Retrieve cart items for the logged-in user
if ($isUserLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT products.*, cart.quantity as cart_quantity FROM cart JOIN products ON cart.product_id = products.product_id WHERE cart.user_id = '$user_id'";
    $cart_result = mysqli_query($conn, $cart_query);

    // Calculate subtotal based on cart items
    $subtotal = 0;
    while ($cart_item = mysqli_fetch_assoc($cart_result)) {
        $subtotal += $cart_item['cart_quantity'] * $cart_item['price'];
    }

    // Calculate tax (you can adjust the tax rate as needed)
    $tax_rate = 0.1; // 10%
    $tax = $subtotal * $tax_rate;

    // Calculate total including tax
    $total = $subtotal + $tax;

    // Fetch cart items again for displaying
    mysqli_data_seek($cart_result, 0);
}
?>


<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" , initial-scale="1.0" />
    <link rel="stylesheet" href="style_2.css" />
    <title>Ecommerce Website</title>
    <script
      src="https://kit.fontawesome.com/716afbfda6.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body>
    <section id="header">
      <a href="index.php"><img src="img/amazon logo.jpg" class="logo" alt="logo" /></a>
      <div>
        <ul id="navbar">
          <li><a href="index.php">Shop</a></li>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="blog.html">Blog</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li id="lg-bag">
            <a class="active" href="cart.php"
              ><i class="fa-solid fa-bag-shopping"></i
            ></a>
          </li>
          <a href="#" id="close"><i class="fa-solid fa-xmark"></i></a>
        </ul>
      </div>
      <div id="mobile">
        <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
        <i id="bar" class="fas fa-outdent"></i>
      </div>
    </section>

    <section id="shop-pg" class="about-head">
            <h2>#cart</h2>
            <p>Add your coupon code & SAVE up to 25%!</p>
        </section>

        <section id="cart" class="section-p1">
            <?php if ($isUserLoggedIn && isset($cart_result) && mysqli_num_rows($cart_result) > 0): ?>
                <table width="100%">
                    <thead>
                        <tr>
                            <td>Remove</td>
                            <td>Image</td>
                            <td>Product</td>
                            <td>Price</td>
                            <td>Quantity</td>
                            <td>Subtotal</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cart_item = mysqli_fetch_assoc($cart_result)): ?>
                            <tr>
                                <td><a href="remove_from_cart.php?product_id=<?php echo $cart_item['product_id']; ?>"><i class="far fa-times-circle"></i></a></td>
                                <td><img src="<?php echo 'img/products/' . $cart_item['image']; ?>" alt="" /></td>
                                <td><?php echo $cart_item['product_name']; ?></td>
                                <td><?php echo $cart_item['price']; ?>₹</td>
                                <td>
                                    <form method="post" action="cart.php">
                                        <input type="hidden" name="product_id" value="<?php echo $cart_item['product_id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo max($cart_item['cart_quantity'], 1); ?>" min="1">
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                                <td><?php echo $cart_item['cart_quantity'] * $cart_item['price']; ?>₹</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Your cart is empty. <a href="index.php">Continue shopping</a>!</p>
            <?php endif; ?>
        </section>

        <section id="cart-add" class="section-p1">
            <?php if ($isUserLoggedIn && isset($cart_result) && mysqli_num_rows($cart_result) > 0): ?>
                <div id="coupon">
                    <h3>Apply Coupon</h3>
                    <div>
                        <input type="text" placeholder="Enter your Coupon Code" />
                        <button class="normal">Apply</button>
                    </div>
                </div>

                <div id="subtotal">
                    <h3>Cart Totals</h3>
                    <table>
                        <tr>
                            <td>Cart Subtotals</td>
                            <td><?php echo $subtotal; ?>₹</td>
                        </tr>
                        <tr>
                            <td>Tax (<?php echo ($tax_rate * 100); ?>%)</td>
                            <td><?php echo $tax; ?>₹</td>
                        </tr>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong><?php echo $total; ?>₹</strong></td>
                        </tr>
                    </table>
<a href="payments.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; text-align: center; font-size: 16px; border-radius: 5px;">Proceed to Checkout</a>
                </div>
            <?php endif; ?>
        </section>




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
</body>
</html>
