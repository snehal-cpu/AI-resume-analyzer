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
   USER DATA
========================= */


$stmt = mysqli_prepare(
    $conn,
    "SELECT fullname,email FROM users WHERE id=?"
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
AI Resume Builder Result
</title>


<link href="
https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


<link rel="stylesheet"
href="assets/css/resume_builder.css">

<link rel="stylesheet" href="assets/css/theme.css">


</head>



<body>



<!-- SIDEBAR -->

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



<li class="active">

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



<li>

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





<div class="main">



<div class="topbar">


<div>

<h2>

AI Resume Builder

</h2>


<p>

Create professional ATS friendly resume using AI

</p>

</div>



<div class="profile">


<img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullname); ?>">


<div>

<strong>

<?php echo htmlspecialchars($fullname); ?>

</strong>


<br>


<small>

<?php echo htmlspecialchars($email); ?>

</small>


</div>

<button id="theme-toggle" class="theme-btn" type="button">
    <i class="fa-solid fa-moon"></i>
</button>
</div>


</div>





<div class="welcome-card">


<h3>

Welcome <?php echo htmlspecialchars($fullname); ?> 👋

</h3>


<p>

Fill your details and generate AI optimized resume.

</p>


</div>




<!-- IMPORTANT FORM START -->

<form action="builder_process.php" method="POST">


<div class="builder-card">


<h3>

<i class="fa-solid fa-user"></i>

Personal Information

</h3>



<div class="row">


<div class="col-md-6">

<label>
Full Name
</label>


<input
type="text"
name="fullname"
class="form-control"
value="<?php echo htmlspecialchars($fullname); ?>"
required>

</div>




<div class="col-md-6">

<label>
Email
</label>


<input
type="email"
name="email"
id="email"
class="form-control"
value="<?php echo htmlspecialchars($email); ?>"
pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
title="Email must contain only lowercase letters"
required>

</div>



<div class="col-md-6">

<label>
Phone
</label>


<input
type="text"
name="phone"
id="phone"
class="form-control"
pattern="[0-9]{10,12}"
maxlength="12"
title="Mobile number must contain 10 to 12 digits"
required>

</div>




<div class="col-md-6">

<label>
Address
</label>


<input
type="text"
name="address"
class="form-control">

</div>



</div>


</div>
<!-- =========================
PROFESSIONAL DETAILS
========================= -->


<div class="builder-card">


<h3>

<i class="fa-solid fa-file-lines"></i>

Professional Summary

</h3>


<textarea

name="summary"

class="form-control"

rows="6"

placeholder="Write your professional summary...">

</textarea>


</div>





<div class="builder-card">


<h3>

<i class="fa-solid fa-code"></i>

Skills

</h3>


<input

type="text"

name="skills"

class="form-control"

placeholder="PHP, MySQL, HTML, CSS, JavaScript, Python, Java, AI">


</div>





<!-- EXPERIENCE -->


<div class="builder-card">


<h3>

<i class="fa-solid fa-briefcase"></i>

Experience

</h3>



<div id="experienceContainer">


<div class="experience-box">


<input

type="text"

name="company[]"

class="form-control mb-3"

placeholder="Company Name">



<input

type="text"

name="position[]"

class="form-control mb-3"

placeholder="Job Position">



<input

type="month"

name="start[]"

class="form-control mb-3">



<input

type="month"

name="end[]"

class="form-control mb-3">



<textarea

name="description[]"

class="form-control"

rows="4"

placeholder="Work description">

</textarea>



</div>


</div>



<button

type="button"

id="addExperience"

class="btn btn-outline-primary mt-3">

<i class="fa-solid fa-plus"></i>

Add Experience

</button>



</div>





<!-- EDUCATION -->


<div class="builder-card">


<h3>

<i class="fa-solid fa-graduation-cap"></i>

Education

</h3>




<div id="educationContainer">


<div class="education-box">


<input

type="text"

name="college[]"

class="form-control mb-3"

placeholder="College / University">



<input

type="text"

name="degree[]"

class="form-control mb-3"

placeholder="Degree">



<input

type="text"

name="year[]"

class="form-control mb-3"

placeholder="Passing Year">



<input

type="text"

name="cgpa[]"

class="form-control"

placeholder="CGPA / Percentage">



</div>


</div>



<button

type="button"

id="addEducation"

class="btn btn-outline-primary mt-3">


<i class="fa-solid fa-plus"></i>

Add Education


</button>



</div>






<!-- PROJECTS -->


<div class="builder-card">


<h3>

<i class="fa-solid fa-folder-open"></i>

Projects

</h3>



<textarea

name="projects"

class="form-control"

rows="5"

placeholder="AI Resume Analyzer
Student Management System">

</textarea>



</div>





<!-- CERTIFICATES -->


<div class="builder-card">


<h3>

<i class="fa-solid fa-award"></i>

Certificates

</h3>



<textarea

name="certificates"

class="form-control"

rows="4"

placeholder="Google AI
AWS Cloud">

</textarea>



</div>





<!-- LANGUAGES -->


<div class="builder-card">


<h3>

<i class="fa-solid fa-language"></i>

Languages

</h3>



<input

type="text"

name="languages"

class="form-control"

placeholder="English, Hindi, Marathi">


</div>





<!-- TEMPLATE -->


<div class="builder-card">


<h3>

<i class="fa-solid fa-palette"></i>

Resume Template

</h3>



<label>

<input

type="radio"

name="template"

value="professional"

checked>

Professional

</label>



<br>



<label>

<input

type="radio"

name="template"

value="modern">

Modern

</label>



<br>



<label>

<input

type="radio"

name="template"

value="creative">

Creative

</label>



</div>
<!-- =========================
SUBMIT BUTTON
========================= -->


<div class="text-center my-5">


<button

type="submit"

class="btn btn-success btn-lg px-5">


<i class="fa-solid fa-wand-magic-sparkles"></i>

Generate AI Resume


</button>



<a

href="dashboard.php"

class="btn btn-secondary btn-lg px-5 ms-3">


<i class="fa-solid fa-arrow-left"></i>

Back


</a>



</div>



</form>

<!-- IMPORTANT FORM END -->



</div>



<script src="assets/js/resume_builder.js"></script>


</body>


</html>