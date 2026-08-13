<?php

session_start();

require_once "config/db.php";

// ============================
// LOGIN CHECK
// ============================

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// ============================
// GET FORM DATA
// ============================

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');

$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';


// ============================
// BASIC VALIDATION
// ============================

if ($fullname === '' || $email === '') {

    $_SESSION['error'] = "Full name and email are required.";

    header("Location: profile.php");
    exit();
}


// ============================
// PASSWORD UPDATE
// ============================

if ($password !== '') {

    if ($password !== $confirm_password) {

        $_SESSION['error'] = "Passwords do not match.";

        header("Location: profile.php");
        exit();
    }

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $query = mysqli_prepare(
        $conn,
        "UPDATE users
         SET fullname=?, email=?, password=?
         WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $query,
        "sssi",
        $fullname,
        $email,
        $hashedPassword,
        $user_id
    );


// ============================
// NAME + EMAIL ONLY
// ============================

} else {

    $query = mysqli_prepare(
        $conn,
        "UPDATE users
         SET fullname=?, email=?
         WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $query,
        "ssi",
        $fullname,
        $email,
        $user_id
    );
}


// ============================
// EXECUTE UPDATE
// ============================

if (mysqli_stmt_execute($query)) {

    $_SESSION['success'] = "Profile updated successfully.";

} else {

    $_SESSION['error'] = "Something went wrong while updating your profile.";
}


// ============================
// REDIRECT
// ============================

header("Location: profile.php");
exit();

?>