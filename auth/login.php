<?php

session_start();


// If already logged in
if(isset($_SESSION['user_id']))
{
    header("Location: ../dashboard.php");
    exit();
}


$error = "";

if(isset($_GET['error']))
{
    if($_GET['error']=="invalid")
    {
        $error = "Invalid email or password.";
    }

    elseif($_GET['error']=="empty")
    {
        $error = "Please fill all fields.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | AI Resume Analyzer</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="../assets/css/auth.css">


</head>


<body>


<div class="login-container">


<div class="login-card">


<h2>
Welcome Back 👋
</h2>


<p>
Login to your AI Resume Analyzer account
</p>



<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>




<form action="login_process.php" method="POST">



<div class="mb-3">

<label>
Email
</label>


<input

type="email"

name="email"

class="form-control"

placeholder="Enter your email"

required

>

</div>





<div class="mb-3">


<label>
Password
</label>


<input

type="password"

name="password"

class="form-control"

placeholder="Enter password"

required

>


</div>

<div class="text-end mt-2">
    <a href="forgot_password.php" class="text-decoration-none">
        Forgot Password?
    </a>
</div>


<button

type="submit"

class="btn btn-primary w-100"

>

Login

</button>



</form>





<div class="text-center mt-4">

Don't have an account?

<a href="register.php">

Register

</a>


</div>



</div>


</div>



</body>

</html>