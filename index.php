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

    <section id="shop-pg">
      <h2>#savefromhome</h2>
      <p>Save more with coupons & upto 25% off!</p>
    </section>

        <div style="margin-bottom: 20px;">
            <!-- Add a back button above the search form -->
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

            <!-- the search form  -->
            <form method="GET" action="index.php" style="display: inline-block;">
                <input type="text" name="search" placeholder="Search products" style="padding: 10px;
                                                                                    font-size: 16px;
                                                                                    border: 1px solid #ccc;
                                                                                    border-radius: 4px;">
                <button type="submit" style="background-color: #008CBA; /* Blue */
                                              color: white;
                                              padding: 10px 20px;
                                              font-size: 16px;
                                              border: none;
                                              border-radius: 4px;
                                              cursor: pointer;">
                    Search
                </button>
            </form>
        </div>


        <section id="products" class="section-p1">
            <div class="container">

            <?php
            // Connect to the database
            $conn = new mysqli("localhost", "root", "", "ecommerce");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Set the limit and calculate the offset
            $limit = 12;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Fetch products from the database based on search with limit and offset
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "SELECT * FROM products WHERE brand LIKE '%$search%' OR product_name LIKE '%$search%' LIMIT $limit OFFSET $offset";
            $result = $conn->query($sql);

            // Display products
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="pro" onclick="window.location.href=\'product_details.php?product_id=' . $row['product_id'] . '\'">';
                    echo '<i class="fa-solid fa-cart-shopping cart" id="cartIcon" aria-hidden="true" onclick="addToCart(' . $row['id'] . ')"></i>';
                    echo "<img src='img/products/" . $row['image'] . "' alt='product image'/>";
                    echo "<div class='des'>";
                    echo "<span>" . $row['brand'] . "</span>";
                    echo "<h5>" . $row['product_name'] . "</h5>";
                    echo "<h4>" . $row['price'] . "₹</h4>";
                    echo "</div>";
                    echo "<a href='#'><i class='fa-solid fa-cart-shopping cart'></i></a>";
                    echo "</div>";
                }

                // Close the loop for displaying products
                echo '</div>'; // Close the container for products

                // Calculate the total number of pages
                $total_sql = "SELECT COUNT(*) as count FROM products WHERE brand LIKE '%$search%' OR product_name LIKE '%$search%'";
                $total_result = $conn->query($total_sql);
                $total_rows = $total_result->fetch_assoc()['count'];
                $total_pages = ceil($total_rows / $limit);

                // Display pagination links outside the loop
                echo '<div id="pager">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<a class="current-page" href="?page=' . $i . '">' . $i . '</a>';
                    } else {
                        echo '<a href="?page=' . $i . '">' . $i . '</a>';
                    }
                }
                echo '</div>';

            } else {
                echo "No products found.";
            }

            // Close the database connection
            $conn->close();
            ?>


        </div>
    </section>



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
    <script src="script.js"></script>

</body>
</html>
