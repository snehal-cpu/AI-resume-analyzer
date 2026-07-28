<?php

session_start();

require_once "config/db.php";


// ================= SESSION CHECK =================

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


$user_id = $_SESSION['user_id'];



// ================= USER DETAILS =================

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, fullname, email 
     FROM users 
     WHERE id=?"
);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);


mysqli_stmt_execute($stmt);


$userResult = mysqli_stmt_get_result($stmt);



if(mysqli_num_rows($userResult)==0)
{
    session_destroy();

    header("Location: auth/login.php");
    exit();
}


$user = mysqli_fetch_assoc($userResult);



// ================= TOTAL RESUMES =================

$totalResume = 0;


$stmt = mysqli_prepare(
$conn,
"SELECT COUNT(*) total 
 FROM resumes 
 WHERE user_id=?"
);


mysqli_stmt_bind_param(
$stmt,
"i",
$user_id
);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$row=mysqli_fetch_assoc($result);


$totalResume=$row['total'];




// ================= ATS SCORE =================

$averageATS = 0;

$stmt=mysqli_prepare(
$conn,
"
SELECT AVG(a.score) score

FROM analysis a

JOIN resumes r

ON a.resume_id=r.id

WHERE r.user_id=?
"
);


mysqli_stmt_bind_param(
$stmt,
"i",
$user_id
);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$row=mysqli_fetch_assoc($result);


if($row['score'] != NULL)
{
    $averageATS = round($row['score']);
}

mysqli_stmt_bind_param(
$stmt,
"i",
$user_id
);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$row=mysqli_fetch_assoc($result);



if($row['score']!=NULL)
{
    $averageATS=round($row['score']);
}

// ================= AI SUGGESTIONS =================


$totalSuggestions = 0;


$stmt=mysqli_prepare(
$conn,

"
SELECT COUNT(*) total

FROM resume_analysis ra

INNER JOIN resumes r

ON ra.resume_id=r.id

WHERE r.user_id=?

AND ra.suggestions IS NOT NULL

"

);


mysqli_stmt_bind_param(
$stmt,
"i",
$user_id
);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$row=mysqli_fetch_assoc($result);


$totalSuggestions=$row['total'];


// ================= ANALYSIS COUNT =================


$stmt=mysqli_prepare(
$conn,
"
SELECT COUNT(*) total

FROM resume_analysis ra

JOIN resumes r

ON ra.resume_id=r.id

WHERE r.user_id=?
"
);


mysqli_stmt_bind_param(
$stmt,
"i",
$user_id
);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$row=mysqli_fetch_assoc($result);


$analysisDone=$row['total'];




// ================= RECENT RESUMES =================


$stmt=mysqli_prepare(
$conn,
"
SELECT 
r.id,
r.resume_name,
ra.ats_score

FROM resumes r

LEFT JOIN resume_analysis ra

ON r.id=ra.resume_id

WHERE r.user_id=?

ORDER BY r.id DESC

LIMIT 5

"
);



mysqli_stmt_bind_param(
$stmt,
"i",
$user_id
);


mysqli_stmt_execute($stmt);


$recent=mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>

Dashboard

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link
rel="stylesheet"
href="assets/css/dashboard.css">

</head>

<body>

<!-- Sidebar -->

<div class="sidebar">

<div class="logo">

<i class="fa-solid fa-robot"></i>

<span>

ResumeAI

</span>

</div>

<ul>

<li class="active">

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

<li>

<a href="settings.php">

<i class="fa-solid fa-gear"></i>

Settings

</a>

</li>

<li>

<a href="contact.php">

<i class="fa-solid fa-headset"></i>

Help & Contact

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

<!-- Main -->

<div class="main">

<!-- Top Bar -->

<div class="topbar">

<div class="search">

<i class="fa fa-search"></i>

<input
type="text"
placeholder="Search Resume...">

</div>

<div class="profile">

<i class="fa-solid fa-bell notification"></i>

<img
src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['fullname']); ?>&background=2563eb&color=fff">

</div>

</div>

<!-- Welcome -->

<div class="welcome">

<div>

<h2>

Welcome,

<?php echo htmlspecialchars($user['fullname']); ?>

👋

</h2>

<p>

Manage all your AI Resume Analysis in one place.

</p>

</div>

<a
href="upload.php"
class="upload-btn">

<i class="fa-solid fa-upload"></i>

Upload Resume

</a>

</div>

<!-- Statistics -->

<div class="row g-4">

<div class="col-lg-3">

<div class="dashboard-card">

<div class="icon primary">

<i class="fa-solid fa-file-lines"></i>

</div>

<div>

<h3>

<?php echo $totalResume; ?>

</h3>

<p>

Total Resume

</p>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="dashboard-card">

<div class="icon success">

<i class="fa-solid fa-chart-line"></i>

</div>

<div>

<h3>

<?php echo $averageATS; ?>%

</h3>

<p>

Average ATS

</p>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="dashboard-card">

<div class="icon warning">

<i class="fa-solid fa-lightbulb"></i>

</div>

<div>

<h3>

<?php echo $totalSuggestions; ?>

</h3>

<p>

AI Suggestions

</p>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="dashboard-card">

<div class="icon danger">

<i class="fa-solid fa-chart-simple"></i>

</div>

<div>

<h3>

<?php echo $analysisDone; ?>

</h3>

<p>

Analysis Done

</p>

</div>

</div>

</div>

</div>
<!-- Charts -->

<div class="row mt-5">

    <div class="col-lg-8">

        <div class="chart-card">

            <div class="card-header-custom">

                <h4>
                    <i class="fa-solid fa-chart-line"></i>
                    ATS Performance
                </h4>

                <span class="badge bg-primary">
                    Last 6 Analysis
                </span>

            </div>

            <canvas id="atsChart" height="120"></canvas>

        </div>

    </div>


    <!-- Quick Actions -->

    <div class="col-lg-4">

        <div class="activity-card">

            <h4>

                <i class="fa-solid fa-bolt"></i>

                Quick Actions

            </h4>

            <a href="upload.php" class="action-btn">

                <i class="fa-solid fa-upload"></i>

                Upload Resume

            </a>

            <a href="resume_builder.php" class="action-btn">

                <i class="fa-solid fa-file-pen"></i>

                Build Resume

            </a>

            <a href="reports.php" class="action-btn">

                <i class="fa-solid fa-chart-column"></i>

                View Reports

            </a>

            <a href="profile.php" class="action-btn">

                <i class="fa-solid fa-user"></i>

                My Profile

            </a>

        </div>

    </div>

</div>


<!-- Recent Resume -->

<div class="recent-card mt-5">

<div class="card-header-custom">

<h4>

<i class="fa-solid fa-clock-rotate-left"></i>

Recent Resume Analysis

</h4>

<a href="reports.php" class="btn btn-primary btn-sm">

View All

</a>

</div>

<table class="table table-dark table-hover align-middle mt-4">

<thead>

<tr>

<th>Resume</th>

<th>ATS Score</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($recent)>0){

while($resume=mysqli_fetch_assoc($recent)){

?>

<tr>

<td>

<i class="fa-solid fa-file-pdf text-danger me-2"></i>

<?php echo htmlspecialchars($resume['resume_name']); ?>

</td>

<td>

<div class="progress">

<div class="progress-bar bg-success"

style="width:<?php echo ($resume['ats_score'] ?? 0); ?>%">

<?php echo ($resume['ats_score'] ?? 0); ?>%

</div>

</div>

</td>

<td>

<?php

$score = $resume['ats_score'] ?? 0;

if($score>=80){

echo '<span class="badge bg-success">Excellent</span>';

}elseif($score>=60){

echo '<span class="badge bg-warning text-dark">Good</span>';

}else{

echo '<span class="badge bg-danger">Needs Improvement</span>';

}

?>

</td>

<td>

<a href="analyze_resume.php?id=<?php echo $resume['id']; ?>"
class="btn btn-outline-success btn-sm">

<i class="fa-solid fa-brain"></i>

Analyze

</a>


<a href="result.php?id=<?php echo $resume['id']; ?>"
class="btn btn-outline-info btn-sm">

<i class="fa-solid fa-eye"></i>

Result

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="4" class="text-center p-5">

<i class="fa-solid fa-folder-open fa-3x mb-3"></i>

<br>

No resumes uploaded yet.

<br><br>

<a href="upload.php"

class="btn btn-primary">

Upload Your First Resume

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>


<!-- Footer -->

<div class="footer mt-5">

<div class="text-center">

<p>

© <?php echo date("Y"); ?>

AI Resume Analyzer

|

Developed with ❤️ using PHP & AI

</p>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const atsLabels = <?php 
echo json_encode(array_column($atsData,'date'));
?>;


const atsScores = <?php 
echo json_encode(array_column($atsData,'ats_score'));
?>;


const ctx = document.getElementById('atsChart');


new Chart(ctx, {

type:'line',

data:{

labels:atsLabels,

datasets:[{

label:"ATS Score %",

data:atsScores,

borderWidth:3,

tension:0.4,

fill:true

}]

},


options:{

responsive:true,

scales:{

y:{

beginAtZero:true,

max:100

}

}

}

});


</script>


<script src="assets/js/dashboard.js"></script>

</body>

</html>