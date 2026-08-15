<?php

session_start();

require_once "config/db.php";



/* ===============================
   LOGIN CHECK
================================ */

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}



$user_id = $_SESSION['user_id'];



/* ===============================
   FETCH USER DETAILS
================================ */


$stmt = mysqli_prepare(
    $conn,
    "SELECT id, fullname, email, created_at
     FROM users
     WHERE id=?"
);



mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);



mysqli_stmt_execute($stmt);



$result = mysqli_stmt_get_result($stmt);



$user = mysqli_fetch_assoc($result);



if(!$user)
{
    header("Location: auth/login.php");
    exit();
}






/* ===============================
   TOTAL RESUMES
================================ */


$resumeStmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total
     FROM resumes
     WHERE user_id=?"
);



mysqli_stmt_bind_param(
    $resumeStmt,
    "i",
    $user_id
);



mysqli_stmt_execute($resumeStmt);



$resumeResult = mysqli_stmt_get_result($resumeStmt);



$resumeData = mysqli_fetch_assoc($resumeResult);



$totalResume = $resumeData['total'] ?? 0;



$joinYear = date(
    "Y",
    strtotime($user['created_at'])
);



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
Profile | AI Resume Analyzer
</title>




<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">



<link rel="stylesheet"
href="assets/css/profile.css">
<link rel="stylesheet" href="assets/css/theme.css">


</head>



<body>




<!-- ================= SIDEBAR ================= -->

<?php include "includes/sidebar.php"; ?>







<!-- ================= MAIN CONTENT ================= -->


<div class="main-content">





<div class="profile-wrapper">





<div class="profile-card">





<!-- ================= PROFILE HEADER ================= -->


<div class="profile-header">



<div class="avatar">


<i class="fa-solid fa-user"></i>


</div>




<div class="profile-info">


<h1>

<?php echo htmlspecialchars($user['fullname']); ?>

</h1>


<p>

<i class="fa-solid fa-sparkles"></i>

AI Resume Analyzer Member

</p>


</div>



</div>







<!-- ================= STATISTICS ================= -->


<div class="stats">





<div class="stat-box">


<div class="stat-icon">

<i class="fa-solid fa-file-lines"></i>

</div>


<h2>

<?php echo $totalResume; ?>

</h2>


<p>

Resumes Uploaded

</p>


</div>







<div class="stat-box">


<div class="stat-icon">

<i class="fa-solid fa-calendar"></i>

</div>


<h2>

<?php echo $joinYear; ?>

</h2>


<p>

Joined Year

</p>


</div>








<div class="stat-box">


<div class="stat-icon">

<i class="fa-solid fa-circle-check"></i>

</div>


<h2>

Active

</h2>


<p>

Account Status

</p>


</div>





</div>









<!-- ================= EDIT PROFILE ================= -->



<div class="profile-form">



<h2>

<i class="fa-solid fa-user-pen"></i>

Edit Profile

</h2>





<form action="update_profile.php" method="POST">



<input type="hidden"
name="id"
value="<?php echo $user['id']; ?>">







<div class="input-group">


<label>

Full Name

</label>



<div class="input-box">


<i class="fa-solid fa-user"></i>



<input 
type="text"
name="fullname"
value="<?php echo htmlspecialchars($user['fullname']); ?>"
required>



</div>



</div>









<div class="input-group">


<label>

Email Address

</label>



<div class="input-box">


<i class="fa-solid fa-envelope"></i>



<input 
type="email"
name="email"
value="<?php echo htmlspecialchars($user['email']); ?>"
required>



</div>



</div>









<div class="input-group">


<label>

Account Created

</label>



<div class="input-box">


<i class="fa-solid fa-calendar-days"></i>



<input 
type="text"
value="<?php echo htmlspecialchars($user['created_at']); ?>"
readonly>



</div>



</div>








<button class="update-btn">


<i class="fa-solid fa-save"></i>


Save Changes


</button>





</form>





</div>







</div>



</div>






</div>




<script src="assets/js/profile.js"></script>



</body>

</html>