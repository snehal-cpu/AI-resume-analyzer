<?php
session_start();

require_once "../config/db.php";

// Check request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

// Get form data
$token = trim($_POST['token']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

// Validate passwords
if (empty($password) || empty($confirm_password)) {
    die("All fields are required.");
}

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

if (strlen($password) < 6) {
    die("Password must be at least 6 characters long.");
}

// Verify token from users table
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, email, token_expiry
     FROM users
     WHERE reset_token = ?"
);

mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired reset link.");
}

$row = mysqli_fetch_assoc($result);

// Check expiry
if (strtotime($row['token_expiry']) < time()) {
    die("Reset link has expired.");
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Update password and clear token
$update = mysqli_prepare(
    $conn,
    "UPDATE users
     SET password = ?,
         reset_token = NULL,
         token_expiry = NULL
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $update,
    "si",
    $hashedPassword,
    $row['id']
);

if (mysqli_stmt_execute($update)) {

    $_SESSION['success'] = "Password reset successfully. Please login.";

    header("Location: login.php");
    exit();

} else {

    die("Failed to update password.");

}
?>