<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require_once "config/db.php";

$user_id = $_SESSION['user_id'];

$userQuery = mysqli_query($conn, "SELECT fullname FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($userQuery);

$userName = $user['fullname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Upload Resume | AI Resume Analyzer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/theme.css">
<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{

background:#0f172a;
color:#fff;
overflow-x:hidden;

}

/* Sidebar */

.sidebar{

position:fixed;
left:0;
top:0;
width:260px;
height:100vh;

background:#111827;

padding:25px;

border-right:1px solid rgba(255,255,255,.08);

}

.logo{

font-size:26px;
font-weight:700;
margin-bottom:40px;

}

.logo i{

color:#3b82f6;

margin-right:8px;

}

.sidebar ul{

list-style:none;
padding:0;

}

.sidebar ul li{

margin-bottom:12px;

}

.sidebar ul li a{

display:block;

padding:14px 18px;

border-radius:12px;

color:#cbd5e1;

text-decoration:none;

transition:.3s;

}

.sidebar ul li a:hover,
.sidebar ul li.active a{

background:linear-gradient(135deg,#2563eb,#7c3aed);

color:white;

}

/* Main */

.main{

margin-left:260px;

padding:35px;

}

/* Top */

.topbar{

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:35px;

}

.profile{

display:flex;

align-items:center;

gap:15px;

}

.profile img{

width:48px;
height:48px;
border-radius:50%;

}

/* Upload Card */

.upload-card{

background:#1e293b;

border-radius:25px;

padding:40px;

box-shadow:0 15px 40px rgba(0,0,0,.3);

border:2px dashed #3b82f6;

text-align:center;

}

.upload-icon{

font-size:70px;

color:#38bdf8;

margin-bottom:20px;

}

.upload-card h3{

margin-bottom:10px;

}

.upload-card p{

color:#94a3b8;

}

input[type=file]{

display:none;

}

.upload-btn{

display:inline-block;

padding:15px 40px;

margin-top:25px;

background:linear-gradient(135deg,#2563eb,#7c3aed);

border-radius:15px;

cursor:pointer;

font-weight:600;

transition:.3s;

}

.upload-btn:hover{

transform:translateY(-3px);

}

.file-name{

margin-top:20px;

font-weight:600;

color:#22c55e;

}

/* Analysis Mode */

.analysis-card{

margin-top:30px;

background:#0f172a;

padding:25px;

border-radius:18px;

text-align:left;

}

.analysis-card h5{

margin-bottom:20px;

}

.form-check{

margin-bottom:12px;

}

#manualSection{

display:none;

margin-top:25px;

}

.form-select{

background:#1e293b;

color:white;

border:1px solid #334155;

padding:12px;

}

.form-select:focus{

background:#1e293b;

color:white;

}

/* Analyze Button */

.analyze-btn{

margin-top:35px;

width:100%;

padding:15px;

font-size:18px;

font-weight:600;

border:none;

border-radius:15px;

background:linear-gradient(135deg,#22c55e,#16a34a);

color:white;

transition:.3s;

}

.analyze-btn:hover{

transform:translateY(-2px);

}

/* Features */

.features{

margin-top:35px;

background:#1e293b;

padding:25px;

border-radius:20px;

}

.features ul{

padding-left:20px;

}

.features li{

margin-bottom:12px;

color:#cbd5e1;

}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">

<i class="fa-solid fa-robot"></i>

ResumeAI

</div>

<ul>

<li><a href="dashboard.php"><i class="fa fa-house"></i> Dashboard</a></li>

<li class="active"><a href="upload.php"><i class="fa fa-upload"></i> Upload Resume</a></li>

<li><a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a></li>

<li><a href="resume_builder.php"><i class="fa fa-file-pen"></i> Resume Builder</a></li>

<li><a href="profile.php"><i class="fa fa-user"></i> Profile</a></li>

<li><a href="settings.php"><i class="fa fa-gear"></i> Settings</a></li>

<li><a href="auth/logout.php"><i class="fa fa-right-from-bracket"></i> Logout</a></li>

</ul>

</div>

<div class="main">

<div class="topbar">

<div>

<h2>Upload Resume</h2>

<p>Analyze resumes for any industry using AI</p>

</div>

<div class="profile">

<img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userName); ?>&background=3b82f6&color=fff">

<strong><?php echo htmlspecialchars($userName); ?></strong>

</div>

</div>

<form action="upload_process.php" method="POST" enctype="multipart/form-data">

<div class="upload-card">

<i class="fa-solid fa-cloud-arrow-up upload-icon"></i>

<h3>Upload Your Resume</h3>

<p>Supported format: PDF (Maximum 5 MB)</p>

<label class="upload-btn">

<i class="fa fa-folder-open"></i>

Choose Resume

<input type="file"
name="resume"
id="resume"
accept=".pdf"
required>

</label>

<div class="file-name" id="filename">

No file selected

</div>
<!-- Analysis Mode -->

<div class="analysis-card">

<h5>
<i class="fa-solid fa-brain"></i>
Choose Analysis Mode
</h5>

<div class="form-check">

<input
class="form-check-input"
type="radio"
name="analysis_mode"
id="auto"
value="auto"
checked>

<label class="form-check-label" for="auto">
🤖 Auto Detect Job Role (Recommended)
</label>

</div>

<div class="form-check">

<input
class="form-check-input"
type="radio"
name="analysis_mode"
id="manual"
value="manual">

<label class="form-check-label" for="manual">
👤 Choose Industry & Job Role Yourself
</label>

</div>

<div id="manualSection">

<label class="mt-3">
Industry
</label>

<select
class="form-select mt-2"
name="industry"
id="industry">

<option value="">Select Industry</option>

<option value="Information Technology">Information Technology</option>

<option value="Engineering">Engineering</option>

<option value="Finance">Finance</option>

<option value="Healthcare">Healthcare</option>

<option value="Marketing">Marketing</option>

<option value="Education">Education</option>

<option value="Government">Government</option>

<option value="Hospitality">Hospitality</option>

<option value="Legal">Legal</option>

<option value="Other">Other</option>

</select>

<label class="mt-4">
Job Role
</label>

<select
class="form-select mt-2"
name="job_role"
id="jobRole">

<option value="">Select Job Role</option>

</select>

</div>

<button
type="submit"
class="analyze-btn">

<i class="fa-solid fa-magnifying-glass-chart"></i>

Analyze Resume

</button>

</div>

</form>

<div class="features">

<h4>
AI Analysis Includes
</h4>

<ul>

<li>✅ ATS Score</li>

<li>✅ Resume Summary</li>

<li>✅ Strengths & Weaknesses</li>

<li>✅ Missing Skills</li>

<li>✅ AI Suggestions</li>

<li>✅ Industry Detection</li>

<li>✅ Job Role Detection</li>

<li>✅ Interview Questions</li>

<li>✅ Improved Resume</li>

</ul>

</div>

</div>

<script>

const resume=document.getElementById("resume");
const filename=document.getElementById("filename");

resume.addEventListener("change",function(){

if(this.files.length>0){

let file=this.files[0];

if(file.type!="application/pdf"){

alert("Only PDF files are allowed.");

this.value="";

return;

}

if(file.size>5*1024*1024){

alert("Maximum file size is 5MB.");

this.value="";

return;

}

filename.innerHTML="<i class='fa-solid fa-file-pdf'></i> "+file.name;

}

});

const auto=document.getElementById("auto");
const manual=document.getElementById("manual");
const manualSection=document.getElementById("manualSection");

const industry=document.getElementById("industry");
const jobRole=document.getElementById("jobRole");

const jobs={

"Information Technology":[
"Software Engineer",
"Frontend Developer",
"Backend Developer",
"Full Stack Developer",
"Java Developer",
"Python Developer",
"PHP Developer",
"Android Developer",
"Data Analyst",
"Data Scientist",
"DevOps Engineer",
"Cloud Engineer",
"Cyber Security Engineer"
],

"Engineering":[
"Mechanical Engineer",
"Civil Engineer",
"Electrical Engineer",
"Electronics Engineer",
"Automobile Engineer"
],

"Finance":[
"Accountant",
"Financial Analyst",
"Auditor",
"Tax Consultant"
],

"Healthcare":[
"Doctor",
"Nurse",
"Pharmacist",
"Lab Technician"
],

"Marketing":[
"Marketing Executive",
"SEO Specialist",
"Digital Marketer",
"Sales Executive"
],

"Education":[
"Teacher",
"Professor",
"Trainer"
],

"Government":[
"Administrative Officer",
"Clerk",
"Police Officer"
],

"Hospitality":[
"Hotel Manager",
"Receptionist",
"Chef"
],

"Legal":[
"Lawyer",
"Legal Advisor"
],

"Other":[
"General"
]

};

industry.addEventListener("change",function(){

jobRole.innerHTML="<option value=''>Select Job Role</option>";

if(jobs[this.value]){

jobs[this.value].forEach(function(role){

let option=document.createElement("option");

option.value=role;

option.textContent=role;

jobRole.appendChild(option);

});

}

});

function updateMode(){

if(auto.checked){

manualSection.style.display="none";

industry.required=false;

jobRole.required=false;

}else{

manualSection.style.display="block";

industry.required=true;

jobRole.required=true;

}

}

auto.addEventListener("change",updateMode);

manual.addEventListener("change",updateMode);

updateMode();

</script>

<script src="assets/js/script.js"></script>

</body>
</html>