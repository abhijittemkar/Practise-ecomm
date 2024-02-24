<?php
session_start(); // Start the session at the beginning of the file

error_reporting(E_ALL);
ini_set('display_errors', 1);
$isUserLoggedIn = isset($_SESSION['user_id']);

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'product_id' parameter is set in the URL
if (isset($_GET['product_id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['product_id']);

    // Query to retrieve product details
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $product = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Check if the product is in the user's cart
            $cart_query = "SELECT quantity FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $cart_result = mysqli_query($conn, $cart_query);

            if ($cart_result && mysqli_num_rows($cart_result) > 0) {
                $cart_data = mysqli_fetch_assoc($cart_result);
                $selected_quantity = $cart_data['quantity'];
                $button_label = 'Update Cart';
            } else {
                $selected_quantity = 1; // Default quantity
                $button_label = 'Add to Cart';
            }
        } else {
            $selected_quantity = 1; // Default quantity
            $button_label = 'Add to Cart';
        }
    } else {
        echo "Error retrieving product details: " . mysqli_error($conn);
        exit();
    }
} else {
    echo "Product ID not specified.";
    exit();
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
    <script src="script3.js"></script>

  </head>
  <body>
    <section id="header">
      <a href="index.php"><img src="img/amazon logo.jpg" class="logo" alt="logo" /></a>
      <div>
        <ul id="navbar">
          <li><a class="active" href="index.php">Shop</a></li>
          <li><a href="profile.php">Profile</a></li>
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


 <!-- Add a back button to navigate back to the main page -->
<button onclick="window.location.href='index.php'" style="background-color: #4CAF50; /* Green */
                                                                   border: none;
                                                                   color: white;
                                                                   padding: 10px 20px;
                                                                   text-align: center;
                                                                   text-decoration: none;
                                                                   display: inline-block;
                                                                   font-size: 16px;
                                                                   margin-right: 10px;
                                                                   cursor: pointer;">
                Back to All Products
            </button>

      <section id="prodetails" class="section-p1">
      <div class="single-pro-img">
        <div class="larg-img">
          <!-- Dynamically change the image source -->
          <img src="<?php echo isset($product['image']) ? 'img/products/' . $product['image'] : 'img/products/default_image.jpg'; ?>" width="100%" alt="pro" id="main-img" />
        </div>
      </div>

      <div class="single-pro-details">
        <h1><?php echo isset($product['brand']) ? $product['brand'] : ''; ?> <?php echo isset($product['style']) ? $product['style'] : ''; ?></h1>
        <p><strong>Product Name:</strong> <?php echo isset($product['product_name']) ? $product['product_name'] : ''; ?></p>
        <p><strong>Price:</strong> <?php echo isset($product['price']) ? $product['price'] : ''; ?></p>
        <p><strong>Country of Origin:</strong> <?php echo isset($product['country_of_origin']) ? $product['country_of_origin'] : ''; ?></p>
        <p><strong>Materials:</strong> <?php echo isset($product['materials']) ? $product['materials'] : ''; ?></p>
        <p><strong>Warranty:</strong> <?php echo isset($product['warranty']) ? $product['warranty'] : ''; ?></p>
        <h4>Product Details</h4>
        <span>
          <?php echo isset($product['product_description']) ? $product['product_description'] : ''; ?>
        </span>

      </div>
    </section>

    <!-- quantity input and Add to Cart or Update Cart button -->
    <form action="add_to_cart.php" method="post" style="margin: 20px auto; padding: 10px; border: 1px solid #ddd; border-radius: 5px; text-align: center; width: fit-content;">
        <label for="quantity" style="margin-right: 10px;">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $selected_quantity; ?>" min="1" style="padding: 5px; width: 50px;">

        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <!-- Include any other hidden fields needed (e.g., product details) -->

        <?php if (isset($_SESSION['user_id'])): ?>
            <button type="submit" name="<?php echo $button_label; ?>" style="background-color: #4CAF50; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;"><?php echo $button_label; ?></button>

            <!-- Show the "Remove from Cart" button only when the "Update Cart" button is active -->
            <?php if ($button_label === 'Update Cart'): ?>
                <a href="remove_from_cart.php?product_id=<?php echo $product['product_id']; ?>" style="color: #f00; margin-left: 10px; text-decoration: none;">Remove from Cart</a>
            <?php endif; ?>

        <?php else: ?>
            <p>Please <a href="Userlogin.php" style="color: #4CAF50; text-decoration: underline;">login</a> to add items to your cart.</p>
        <?php endif; ?>
    </form>






    <section id="newsletter" class="section-p1">
      <div class="newstext">
        <h4>Sign Up for Newsletters</h4>
        <p>
          Get E-mail updates about our latest shop and
          <span>special offers.</span>
        </p>
      </div>
      <div class="newsform">
        <input type="text" placeholder="Enter your Email Address" />
        <button class="normal">Sign Up</button>
      </div>
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
