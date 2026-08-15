<?php

session_start();

require_once "config/db.php";


/* =========================
LOGIN CHECK
========================= */

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


$user_id = $_SESSION['user_id'];



/* =========================
GET USER DETAILS
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id=?"
);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


$user = mysqli_fetch_assoc($result);



$fullname = $user['fullname'] ?? "User";

$email = $user['email'] ?? "";

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
Settings - ResumeAI
</title>



<link href="
https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


<link rel="stylesheet"
href="assets/css/settings.css">

<link rel="stylesheet" href="assets/css/theme.css">


</head>



<body>



<!-- =========================
SIDEBAR
========================= -->


<div class="sidebar">


<div class="logo">

<i class="fa-solid fa-robot"></i>

ResumeAI

</div>



<ul>


<li>

<a href="dashboard.php">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

</li>



<li>

<a href="upload.php">

<i class="fa-solid fa-upload"></i>

Upload Resume

</a>

</li>



<li>

<a href="reports.php">

<i class="fa-solid fa-chart-column"></i>

Reports

</a>

</li>



<li>

<a href="resume_builder.php">

<i class="fa-solid fa-file-pen"></i>

Resume Builder

</a>

</li>



<li>

<a href="profile.php">

<i class="fa-solid fa-user"></i>

Profile

</a>

</li>



<li class="active">

<a href="settings.php">

<i class="fa-solid fa-gear"></i>

Settings

</a>

</li>



<li>

<a href="auth/logout.php">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</li>


</ul>


</div>





<!-- =========================
MAIN
========================= -->


<div class="main">



<!-- TOPBAR -->


<div class="topbar">


<div>

<h2>

Settings

</h2>


<p>

Manage your ResumeAI account

</p>

</div>




<div class="profile">


<img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullname); ?>&background=2563eb&color=fff">



<div>


<strong>

<?php echo htmlspecialchars($fullname); ?>

</strong>


<br>


<small>

<?php echo htmlspecialchars($email); ?>

</small>


</div>


</div>



</div>






<!-- SETTINGS CARDS -->


<div class="settings-container">



<div class="settings-card">


<h3>

<i class="fa-solid fa-user"></i>

Account Information

</h3>



<label>

Full Name

</label>


<input type="text"
value="<?php echo htmlspecialchars($fullname); ?>">



<label>

Email

</label>


<input type="email"
value="<?php echo htmlspecialchars($email); ?>">



<button class="save-btn">

<i class="fa-solid fa-save"></i>

Save Changes

</button>


</div>





<div class="settings-card">


<h3>

<i class="fa-solid fa-lock"></i>

Change Password

</h3>



<label>

Current Password

</label>


<input type="password"
placeholder="Enter current password">



<label>

New Password

</label>


<input type="password"
placeholder="Enter new password">



<label>

Confirm Password

</label>


<input type="password"
placeholder="Confirm new password">



<button class="save-btn">

<i class="fa-solid fa-key"></i>

Update Password

</button>


</div>




</div>



</div>



</body>

<script src="assets/js/script.js"></script>

</html>