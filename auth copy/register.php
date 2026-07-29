<?php
session_start();

if(isset($_SESSION['user_id']))
{
    header("Location: ../dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/auth.css">

</head>

<body>

<div class="login-container">

<div class="login-card">

<h2>Create Account</h2>

<p>Create your AI Resume Analyzer account</p>

<form action="register_process.php" method="POST">

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="fullname"
class="form-control"
pattern="[A-Za-z ]+"
title="Only alphabets and spaces allowed"
required>
</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
id="registerEmail"
class="form-control"
pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
title="Email must contain lowercase letters only"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}"
title="Minimum 8 characters with uppercase, lowercase and number"
required>

</div>

<div class="mb-3">
    <label>Confirm Password</label>
    <input
        type="password"
        name="confirm_password"
        class="form-control"
        required>
</div>

<button class="btn btn-primary w-100">

Register

</button>

</form>

<div class="text-center mt-4">

Already have an account?

<a href="login.php">

Login

</a>

</div>

</div>

</div>
<script src="assets/js/register_validation.js"></script>
</body>
</html>