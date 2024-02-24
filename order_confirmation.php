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


<?php
// Assuming you have the order_id in the URL parameter
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

// You might want to perform additional validation on $order_id

// Display the order confirmation message
echo "<h2>Order Confirmation</h2>";
echo "<p>Your order with ID $order_id has been confirmed. Thank you for shopping with us!</p>";

?>



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
