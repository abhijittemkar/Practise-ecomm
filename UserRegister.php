<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style_2.css" />
    <script src="https://kit.fontawesome.com/716afbfda6.js" crossorigin="anonymous"></script>
    <script src="script3.js"></script>
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
                <a href="Userlogin.php"><i class="fa-solid fa-bag-shopping"></i></a>
              </li>
              <a href="#" id="close"><i class="fa-solid fa-xmark"></i></a>
            </ul>
          </div>
          <div id="mobile">
            <a href="cart.html"><i class="fa-solid fa-bag-shopping"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
          </div>
    </section>

    <div style="display: flex; justify-content: center; align-items: center; height: auto; background-color: #f0f0f0;">
        <div id="register-box" style="background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); padding: 20px; width: 500px; text-align: left;">

            <h2 style="color: #333333;">User Registration</h2>

            <form action="register_process.php" method="post">

                <label for="username" style="display: block; margin-bottom: 5px; font-size: 14px; color: #555555;">Username:</label>
                <input type="text" id="username" name="username" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 4px;">

                <label for="email" style="display: block; margin-bottom: 5px; font-size: 14px; color: #555555;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 4px;">

                <label for="password" style="display: block; margin-bottom: 5px; font-size: 14px; color: #555555;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 4px;">

                <label for="address" style="display: block; margin-bottom: 5px; font-size: 14px; color: #555555;">Address:</label>
                <input type="text" id="address" name="address" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 4px;">

                <label for="phone" style="display: block; margin-bottom: 5px; font-size: 14px; color: #555555;">Phone:</label>
                <input type="text" id="phone" name="phone" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 4px;">

                <label for="sex" style="display: block; margin-bottom: 5px; font-size: 14px; color: #555555;">Sex:</label>
                <select id="sex" name="sex" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cccccc; border-radius: 4px;">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>

                <button type="submit" name="register" style="background-color: #008CBA; color: #ffffff; padding: 10px 20px; font-size: 16px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">Register</button>
            </form>

            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="message" style="margin-top: 10px; color: #FF0000;">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
            ?>

            <p style="margin-top: 10px;">Already have an account? <a href="UserLogin.php">Login here</a></p>
        </div>
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
