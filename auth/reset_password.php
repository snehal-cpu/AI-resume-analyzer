<?php
session_start();
require_once "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if (empty($email) || empty($password) || empty($confirm)) {

        $message = "All fields are required.";

    } elseif ($password != $confirm) {

        $message = "Passwords do not match.";

    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users SET password=? WHERE email=?"
        );

        mysqli_stmt_bind_param($stmt, "ss", $hash, $email);

        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {

            echo "<script>
                    alert('Password changed successfully!');
                    window.location='login.php';
                  </script>";
            exit();

        } else {

            $message = "Email not found.";

        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reset Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>
body{
    background:linear-gradient(135deg,#4F46E5,#7C3AED);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Segoe UI,sans-serif;
}
.card{
    width:420px;
    border-radius:20px;
    border:none;
    box-shadow:0 15px 40px rgba(0,0,0,.2);
}
.logo{
    width:80px;
    height:80px;
    background:#EEF2FF;
    color:#4F46E5;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:35px;
}
</style>

</head>
<body>

<div class="card p-4">

<div class="text-center mb-4">
<div class="logo mb-3">
<i class="fa-solid fa-key"></i>
</div>

<h3>Reset Password</h3>

<p class="text-muted">Enter your email and new password.</p>

</div>

<?php if($message!=""){ ?>

<div class="alert alert-danger">
<?php echo $message; ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>New Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="mb-3">
<label>Confirm Password</label>
<input type="password" name="confirm_password" class="form-control" required>
</div>

<button class="btn btn-primary w-100">
Reset Password
</button>

</form>

<div class="text-center mt-3">
<a href="login.php">Back to Login</a>
</div>

</div>

</body>
</html>