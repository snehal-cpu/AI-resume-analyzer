<?php

session_start();

require_once "config/db.php";

// ================= LOGIN CHECK =================

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}


// ================= CHECK RESUME ID =================

if (!isset($_GET['resume_id'])) {
    die("Resume ID missing");
}

$resume_id = intval($_GET['resume_id']);

if ($resume_id <= 0) {
    die("Invalid Resume ID");
}


// ================= FETCH ANALYSIS =================

$sql = "
    SELECT *
    FROM resume_analysis
    WHERE resume_id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $resume_id
);

if (!mysqli_stmt_execute($stmt)) {
    die("Query failed: " . mysqli_stmt_error($stmt));
}

$result = mysqli_stmt_get_result($stmt);

$analysis = mysqli_fetch_assoc($result);


// ================= CHECK RESULT =================

if (!$analysis) {
    die("Analysis report not found for Resume ID: " . $resume_id);
}


// ================= SAFE DATA FUNCTION =================

function convertToArray($data)
{
    if (empty($data)) {
        return [];
    }

    $decoded = json_decode($data, true);

    if (is_array($decoded)) {
        return $decoded;
    }

    return explode("\n", $data);
}


// ================= DATA =================

$score = intval(
    $analysis['ats_score'] ?? 0
);

$strengths = convertToArray(
    $analysis['strengths'] ?? ''
);

$weaknesses = convertToArray(
    $analysis['weaknesses'] ?? ''
);

$missingSkills = convertToArray(
    $analysis['missing_skills'] ?? ''
);

$suggestions = convertToArray(
    $analysis['suggestions'] ?? ''
);

$jobRoles = convertToArray(
    $analysis['job_roles'] ?? ''
);

$questions = convertToArray(
    $analysis['interview_questions'] ?? ''
);

$improvedResume =
    $analysis['improved_resume'] ?? '';


// ================= ATS GRADE =================

if ($score >= 90) {

    $grade = "A+";
    $status = "Excellent Resume";

}
elseif ($score >= 80) {

    $grade = "A";
    $status = "Strong Resume";

}
elseif ($score >= 70) {

    $grade = "B";
    $status = "Good Resume";

}
elseif ($score >= 60) {

    $grade = "C";
    $status = "Needs Improvement";

}
else {

    $grade = "D";
    $status = "Weak Resume";

}

?>


<!DOCTYPE html>

<html lang="en">


<head>

<link rel="stylesheet" href="assets/css/sidebar.css">
<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
AI Resume Analysis Report
</title>



<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">



<link rel="stylesheet"
href="assets/css/result.css">

<style>
/* ================= RESULT PAGE LAYOUT FIX ================= */

.main-content {
    margin-left: 300px !important;
    width: calc(100% - 300px) !important;
    padding: 40px !important;
}

.report-container {
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
}

/* Keep mobile layout unchanged */
@media screen and (max-width: 900px) {

    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 80px 20px 30px !important;
    }

}
</style>


</head>



<body>



<?php include "includes/sidebar.php"; ?>



<div class="main-content">


<div class="report-container">



<div class="page-title">

<h1>

<i class="fa-solid fa-robot"></i>

AI Resume Analysis Report

</h1>


<p>
Powered by AI Resume Analyzer
</p>


</div>





<!-- ATS SCORE -->


<div class="card score-card">


<h2>

<i class="fa-solid fa-chart-line"></i>

ATS Score

</h2>



<div class="score-circle"
     style="background: conic-gradient(#00c6ff <?php echo ($score * 3.6); ?>deg, #071522 <?php echo ($score * 3.6); ?>deg 360deg);">

    <span>
        <?php echo $score; ?>%
    </span>

</div>



<h3>

Grade:
<?php echo $grade; ?>

</h3>


<p>

<?php echo $status; ?>

</p>


</div>







<div class="grid">



<!-- STRENGTH -->


<div class="card">


<h2 class="green">

<i class="fa-solid fa-circle-check"></i>

Strengths

</h2>


<ul>


<?php foreach($strengths as $item): ?>


<?php if(trim($item)!=""): ?>


<li>
<?php echo htmlspecialchars($item); ?>
</li>


<?php endif; ?>


<?php endforeach; ?>


</ul>


</div>






<!-- WEAKNESS -->


<div class="card">


<h2 class="red">

<i class="fa-solid fa-circle-xmark"></i>

Weaknesses

</h2>


<ul>


<?php foreach($weaknesses as $item): ?>


<?php if(trim($item)!=""): ?>


<li>
<?php echo htmlspecialchars($item); ?>
</li>


<?php endif; ?>


<?php endforeach; ?>


</ul>


</div>



</div>








<!-- MISSING SKILLS -->


<div class="card">


<h2 class="orange">

<i class="fa-solid fa-triangle-exclamation"></i>

Missing Skills

</h2>


<div class="tags">


<?php foreach($missingSkills as $skill): ?>


<?php if(trim($skill)!=""): ?>


<span>

<?php echo htmlspecialchars($skill); ?>

</span>


<?php endif; ?>


<?php endforeach; ?>


</div>


</div>








<div class="grid">



<!-- JOB ROLES -->


<div class="card">


<h2 class="blue">

<i class="fa-solid fa-briefcase"></i>

Recommended Jobs

</h2>


<ul>


<?php foreach($jobRoles as $role): ?>


<?php if(trim($role)!=""): ?>


<li>
<?php echo htmlspecialchars($role); ?>
</li>


<?php endif; ?>


<?php endforeach; ?>


</ul>


</div>






<!-- SUGGESTIONS -->


<div class="card">


<h2 class="purple">

<i class="fa-solid fa-wand-magic-sparkles"></i>

AI Suggestions

</h2>



<ul>


<?php foreach($suggestions as $item): ?>


<?php if(trim($item)!=""): ?>


<li>
<?php echo htmlspecialchars($item); ?>
</li>


<?php endif; ?>


<?php endforeach; ?>


</ul>


</div>



</div>









<!-- INTERVIEW QUESTIONS -->


<div class="card">


<h2 class="yellow">

<i class="fa-solid fa-comments"></i>

Interview Questions

</h2>


<ol>


<?php foreach($questions as $q): ?>


<?php if(trim($q)!=""): ?>


<li>
<?php echo htmlspecialchars($q); ?>
</li>


<?php endif; ?>


<?php endforeach; ?>


</ol>


</div>









<!-- IMPROVED RESUME -->


<div class="card">


<h2>

<i class="fa-solid fa-file-pen"></i>

AI Improved Resume

</h2>



<div class="resume-text">


<?php


echo nl2br(
htmlspecialchars($improvedResume)
);


?>


</div>


</div>








<div class="buttons">


<a href="reports.php">

<i class="fa-solid fa-arrow-left"></i>

Back Reports

</a>



<a href="upload.php">

<i class="fa-solid fa-upload"></i>

Analyze Another

</a>



<button onclick="window.print()">

<i class="fa-solid fa-print"></i>

Print Report

</button>


</div>






</div>


</div>




<script src="assets/js/result.js"></script>


</body>

</html>