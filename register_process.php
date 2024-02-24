<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "ecommerce");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape user inputs to prevent SQL injection
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string(password_hash($_POST['password'], PASSWORD_DEFAULT)); // Hash the password
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $sex = $conn->real_escape_string($_POST['sex']);

    // Check for duplicate email
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $resultEmail = $conn->query($checkEmail);

    if ($resultEmail->num_rows > 0) {
        echo "Email is already registered.";
        exit();
    }

    // Check for duplicate username
    $checkUsername = "SELECT * FROM users WHERE username='$username'";
    $resultUsername = $conn->query($checkUsername);

    if ($resultUsername->num_rows > 0) {
        echo "Username is already taken.";
        exit();
    }

    // Validate phone number (basic validation)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "Invalid phone number.";
        exit();
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit();
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password, address, phone, sex) VALUES ('$username', '$email', '$password', '$address', '$phone', '$sex')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
