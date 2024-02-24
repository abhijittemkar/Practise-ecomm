<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the admin's credentials (you should use hashed passwords and validate against a database) This is only for testing.
    if ($username === 'admin' && $password === 'admin_password') {
        // Authentication successful
        $_SESSION['admin'] = true;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        // Authentication failed
        echo 'Invalid username or password.';
    }
}
?>
