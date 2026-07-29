<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | AI Resume Analyzer</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body{
            background: linear-gradient(135deg,#4F46E5,#7C3AED);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial, Helvetica, sans-serif;
        }

        .card{
            width:420px;
            border:none;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,.2);
        }

        .logo{
            font-size:55px;
            color:#4F46E5;
        }

        .btn-primary{
            background:#4F46E5;
            border:none;
        }

        .btn-primary:hover{
            background:#4338CA;
        }
    </style>

</head>

<body>

<div class="card p-4">

    <div class="text-center">

        <i class="bi bi-shield-lock-fill logo"></i>

        <h3 class="mt-3">Forgot Password?</h3>

        <p class="text-muted">
            Enter your registered email address.<br>
            To update your password
        </p>

    </div>

    <?php
    if(isset($_SESSION['success']))
    {
        echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
        unset($_SESSION['success']);
    }

    if(isset($_SESSION['error']))
    {
        echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="reset_password.php" method="POST">

        <div class="mb-3">

            <label class="form-label">
                Email Address
            </label>

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Enter your email"
                required>

        </div>

        <button
            type="submit"
            class="btn btn-primary w-100">

            <i class="bi bi-envelope-fill"></i>
            Update your password

        </button>

    </form>

    <div class="text-center mt-3">

        <a href="login.php" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i>
            Back to Login
        </a>

    </div>

</div>

</body>
</html>