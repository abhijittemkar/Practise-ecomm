<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: profile.php"); // Redirect to user profile page
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "ecommerce");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, set session variables
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_id'] = $row['user_id'];

            // Redirect back to the referring page or a default page
            $redirectPage = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'index.php';
            header("Location: $redirectPage");
            exit();
        } else {
            showError("Incorrect password. Please try again");
            exit();
        }
    } else {
        showError("User not found. Please register.");
        exit();
    }
}

function showError($message) {
    echo "<script>alert('$message'); window.location.href='UserLogin.php';</script>";
    exit();
}

// Close the database connection
$conn->close();
?>
