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
AI Resume Builder
</title>


<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


<link rel="stylesheet"
href="assets/css/resume_builder.css">

<link rel="stylesheet" href="assets/css/theme.css">


<style>

/* =====================================================
   AI RESUME BUILDER - DESIGN
   Same dark blue + cyan theme
===================================================== */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;

    font-family: 'Poppins', sans-serif;

    background:
        linear-gradient(
            135deg,
            #061426,
            #102a43
        );

    color: #ffffff;
}


/* =====================================================
   SIDEBAR
===================================================== */

.sidebar {
    position: fixed;

    top: 0;
    left: 0;

    width: 250px;
    height: 100vh;

    padding: 25px 20px;

    background: #071522;

    box-shadow:
        5px 0 25px rgba(0,0,0,.35);

    z-index: 1000;
}


/* LOGO */

.sidebar .logo {
    display: flex;

    align-items: center;
    gap: 10px;

    padding: 5px 10px;

    margin-bottom: 40px;

    font-size: 24px;
    font-weight: 700;

    color: #ffffff;
}

.sidebar .logo i {
    color: #00c6ff;
    font-size: 25px;
}


/* SIDEBAR MENU */

.sidebar ul {
    list-style: none;

    margin: 0;
    padding: 0;
}

.sidebar ul li {
    margin-bottom: 10px;
}

.sidebar ul li a {
    display: flex;

    align-items: center;

    gap: 13px;

    padding: 14px 16px;

    border-radius: 12px;

    text-decoration: none;

    color: #cbd5e1;

    font-size: 15px;
    font-weight: 500;

    transition: all .3s ease;
}

.sidebar ul li a i {
    width: 22px;

    text-align: center;

    font-size: 17px;
}

.sidebar ul li a:hover,
.sidebar ul li.active a {
    background:
        linear-gradient(
            135deg,
            #0066ff,
            #00c6ff
        );

    color: #ffffff;

    transform: translateX(3px);
}


/* =====================================================
   MAIN CONTENT
===================================================== */

.main {
    margin-left: 250px;

    min-height: 100vh;

    padding: 30px 40px 60px;
}


/* =====================================================
   TOP BAR
===================================================== */

.topbar {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 25px;

    margin-bottom: 30px;

    padding: 22px 28px;

    background: rgba(16, 42, 67, .85);

    border: 1px solid rgba(0,198,255,.08);

    border-radius: 18px;

    box-shadow:
        0 15px 40px rgba(0,0,0,.25);
}

.topbar h2 {
    margin: 0;

    font-size: 28px;

    color: #ffffff;
}

.topbar p {
    margin: 6px 0 0;

    color: #94a3b8;

    font-size: 14px;
}


/* =====================================================
   PROFILE
===================================================== */

.profile {
    display: flex;

    align-items: center;

    gap: 12px;

    padding: 8px 14px;

    border-radius: 14px;

    background: #071522;
}

.profile img {
    width: 45px;
    height: 45px;

    border-radius: 50%;

    border: 2px solid #00c6ff;
}

.profile strong {
    color: #ffffff;

    font-size: 14px;
}

.profile small {
    color: #94a3b8;

    font-size: 12px;
}


/* =====================================================
   WELCOME CARD
===================================================== */

.welcome-card {
    position: relative;

    overflow: hidden;

    margin-bottom: 25px;

    padding: 28px 30px;

    border-radius: 20px;

    background:
        linear-gradient(
            135deg,
            #102a43,
            #123654
        );

    border: 1px solid rgba(0,198,255,.10);

    box-shadow:
        0 15px 40px rgba(0,0,0,.25);
}

.welcome-card::after {
    content: "";

    position: absolute;

    width: 180px;
    height: 180px;

    right: -70px;
    top: -90px;

    border-radius: 50%;

    background: rgba(0,198,255,.08);
}

.welcome-card h3 {
    margin: 0 0 8px;

    font-size: 22px;

    color: #00c6ff;
}

.welcome-card p {
    margin: 0;

    color: #cbd5e1;

    font-size: 14px;
}


/* =====================================================
   BUILDER CARD
===================================================== */

.builder-card {
    margin-bottom: 25px;

    padding: 28px;

    border-radius: 20px;

    background: #102a43;

    border: 1px solid rgba(255,255,255,.04);

    box-shadow:
        0 15px 40px rgba(0,0,0,.28);

    transition: transform .3s ease,
                box-shadow .3s ease;
}

.builder-card:hover {
    transform: translateY(-2px);

    box-shadow:
        0 18px 45px rgba(0,0,0,.35);
}


/* CARD HEADING */

.builder-card h3 {
    display: flex;

    align-items: center;

    gap: 10px;

    margin: 0 0 25px;

    padding-bottom: 14px;

    border-bottom: 1px solid rgba(255,255,255,.08);

    font-size: 20px;

    color: #ffffff;
}

.builder-card h3 i {
    color: #00c6ff;
}


/* =====================================================
   FORM ROW
===================================================== */

.row {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 22px;
}


/* =====================================================
   FORM GROUP
===================================================== */

.col-md-6 {
    display: flex;

    flex-direction: column;
}


/* LABEL */

label {
    display: block;

    margin-bottom: 8px;

    color: #cbd5e1;

    font-size: 14px;

    font-weight: 500;
}


/* INPUTS */

.form-control {
    width: 100%;

    padding: 13px 15px;

    border: 1px solid #29445e;

    border-radius: 10px;

    outline: none;

    background: #071522;

    color: #ffffff;

    font-family: 'Poppins', sans-serif;

    font-size: 14px;

    transition:
        border-color .3s ease,
        box-shadow .3s ease,
        background .3s ease;
}

.form-control::placeholder {
    color: #64748b;
}

.form-control:focus {
    border-color: #00c6ff;

    background: #081b2c;

    box-shadow:
        0 0 0 3px rgba(0,198,255,.10);
}


/* TEXTAREA */

textarea.form-control {
    resize: vertical;

    min-height: 120px;

    line-height: 1.6;
}


/* =====================================================
   EXPERIENCE / EDUCATION BOX
===================================================== */

.experience-box,
.education-box {
    padding: 20px;

    border-radius: 15px;

    background: #071522;

    border: 1px solid rgba(255,255,255,.06);
}


/* =====================================================
   BUTTONS
===================================================== */

.btn {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    border: none;

    border-radius: 10px;

    padding: 12px 20px;

    font-family: 'Poppins', sans-serif;

    font-size: 14px;

    font-weight: 600;

    text-decoration: none;

    cursor: pointer;

    transition:
        transform .3s ease,
        box-shadow .3s ease,
        opacity .3s ease;
}

.btn:hover {
    transform: translateY(-2px);

    opacity: .95;
}


/* ADD BUTTON */

.btn-outline-primary {
    border: 1px solid #00c6ff;

    background: transparent;

    color: #00c6ff;
}

.btn-outline-primary:hover {
    background:
        linear-gradient(
            135deg,
            #0066ff,
            #00c6ff
        );

    color: #ffffff;

    box-shadow:
        0 8px 20px rgba(0,198,255,.20);
}


/* SUCCESS BUTTON */

.btn-success {
    background:
        linear-gradient(
            135deg,
            #0066ff,
            #00c6ff
        );

    color: #ffffff;

    box-shadow:
        0 8px 25px rgba(0,198,255,.18);
}


/* SECONDARY BUTTON */

.btn-secondary {
    background: #1e293b;

    color: #cbd5e1;

    border: 1px solid #334155;
}

.btn-secondary:hover {
    background: #26364a;

    color: #ffffff;
}


/* =====================================================
   TEMPLATE RADIO BUTTONS
===================================================== */

.builder-card label input[type="radio"] {
    accent-color: #00c6ff;

    margin-right: 7px;
}

.builder-card > label {
    display: inline-flex;

    align-items: center;

    margin-bottom: 12px;

    padding: 10px 14px;

    border-radius: 10px;

    background: #071522;

    border: 1px solid rgba(255,255,255,.06);

    cursor: pointer;

    transition: .3s;
}

.builder-card > label:hover {
    border-color: #00c6ff;
}


/* =====================================================
   SUBMIT AREA
===================================================== */

.text-center {
    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 15px;

    margin-top: 35px !important;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media screen and (max-width: 1000px) {

    .main {
        padding: 25px;
    }

    .topbar {
        align-items: flex-start;
    }

    .row {
        grid-template-columns: 1fr;
    }

}


@media screen and (max-width: 768px) {

    .sidebar {
        position: relative;

        width: 100%;

        height: auto;

        min-height: auto;

        padding: 15px;
    }

    .sidebar .logo {
        margin-bottom: 15px;
    }

    .sidebar ul {
        display: grid;

        grid-template-columns:
            repeat(2, 1fr);

        gap: 8px;
    }

    .sidebar ul li {
        margin: 0;
    }

    .sidebar ul li a {
        padding: 11px;

        font-size: 13px;
    }

    .main {
        margin-left: 0;

        padding: 20px 15px 40px;
    }

    .topbar {
        flex-direction: column;

        align-items: stretch;

        padding: 20px;
    }

    .profile {
        width: 100%;
    }

    .welcome-card {
        padding: 22px;
    }

    .builder-card {
        padding: 20px;
    }

}


@media screen and (max-width: 500px) {

    .sidebar ul {
        grid-template-columns: 1fr;
    }

    .topbar h2 {
        font-size: 23px;
    }

    .builder-card h3 {
        font-size: 18px;
    }

    .text-center {
        flex-direction: column;

        align-items: stretch;
    }

    .text-center .btn {
        width: 100%;
    }

}

</style>

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



<!-- TOP BAR -->

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

</div>


</div>
<!-- TOPBAR END -->







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