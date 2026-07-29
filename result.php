<?php

session_start();

require_once "config/db.php";


// ================= LOGIN CHECK =================

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


// ================= GET ANALYSIS ID =================

if(isset($_GET['resume_id']))
{
    $analysis_id = intval($_GET['resume_id']);
}
elseif(isset($_GET['id']))
{
    $analysis_id = intval($_GET['id']);
}
else
{
    die("Resume ID missing");
}



if($analysis_id <= 0)
{
    die("Invalid Resume ID");
}



// ================= FETCH ANALYSIS =================


$stmt = mysqli_prepare(
    $conn,
    "
    SELECT *
    FROM resume_analysis
    WHERE id=?
    "
);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $analysis_id
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


$analysis = mysqli_fetch_assoc($result);



if(!$analysis)
{
    die("Analysis report not found");
}




// ================= SAFE DATA FUNCTION =================


function convertToArray($data)
{

    if(empty($data))
    {
        return [];
    }


    // JSON support

    $decoded = json_decode($data,true);


    if(is_array($decoded))
    {
        return $decoded;
    }


    // newline support

    return explode("\n",$data);

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


if($score >= 90)
{
    $grade="A+";
    $status="Excellent Resume";
}
elseif($score >=80)
{
    $grade="A";
    $status="Strong Resume";
}
elseif($score>=70)
{
    $grade="B";
    $status="Good Resume";
}
elseif($score>=60)
{
    $grade="C";
    $status="Needs Improvement";
}
else
{
    $grade="D";
    $status="Weak Resume";
}



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
AI Resume Analysis Report
</title>



<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">



<link rel="stylesheet"
href="assets/css/result.css">


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



<div class="score-circle">

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