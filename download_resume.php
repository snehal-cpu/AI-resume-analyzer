<?php

session_start();

require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;


// =========================
// LOGIN CHECK
// =========================

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// =========================
// GET ANALYSIS ID
// =========================

if (!isset($_GET['id'])) {
    die("Analysis ID missing");
}

$analysis_id = intval($_GET['id']);

if ($analysis_id <= 0) {
    die("Invalid Analysis ID");
}


// =========================
// FETCH RESUME + ANALYSIS
// =========================

$stmt = mysqli_prepare(
    $conn,
    "SELECT 
        ra.*,
        r.user_id,
        r.resume_name
     FROM resume_analysis ra
     INNER JOIN resumes r
        ON ra.resume_id = r.id
     WHERE ra.id = ?
       AND r.user_id = ?
     LIMIT 1"
);

if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $analysis_id,
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Resume data not found");
}


// =========================
// DATA
// =========================

$atsScore = intval($data['ats_score'] ?? 0);

$strengths = $data['strengths'] ?? '';
$weaknesses = $data['weaknesses'] ?? '';
$missingSkills = $data['missing_skills'] ?? '';
$suggestions = $data['suggestions'] ?? '';
$jobRoles = $data['job_roles'] ?? '';
$improvedResume = $data['improved_resume'] ?? '';


// =========================
// ESCAPE HTML
// =========================

function clean($text)
{
    return nl2br(
        htmlspecialchars(
            $text,
            ENT_QUOTES,
            'UTF-8'
        )
    );
}


// =========================
// RESUME TITLE
// =========================

$resumeName = $data['resume_name'] ?? 'AI Generated Resume';


// =========================
// CREATE PROFESSIONAL PDF
// =========================

$html = '

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<style>

@page {
    margin: 35px 45px;
}

body {
    font-family: DejaVu Sans, sans-serif;
    color: #222;
    font-size: 11px;
    line-height: 1.5;
}

.header {
    text-align: center;
    margin-bottom: 25px;
}

.name {
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 5px;
}

.subtitle {
    font-size: 12px;
    color: #555;
}

.section {
    margin-top: 18px;
    margin-bottom: 12px;
}

.section-title {
    font-size: 14px;
    font-weight: bold;
    border-bottom: 1px solid #444;
    padding-bottom: 4px;
    margin-bottom: 8px;
}

.content {
    font-size: 11px;
    white-space: normal;
}

.resume-content {
    margin-top: 10px;
}

.score-box {
    text-align: center;
    margin-top: 20px;
    padding: 8px;
    border: 1px solid #ddd;
}

.footer {
    margin-top: 30px;
    text-align: center;
    font-size: 8px;
    color: #777;
}

</style>

</head>

<body>


<div class="header">

<div class="name">
AI Generated Resume
</div>

<div class="subtitle">
ATS Optimized Professional Resume
</div>

</div>


<div class="section">

<div class="section-title">
Professional Resume
</div>

<div class="resume-content">
' . clean($improvedResume) . '
</div>

</div>


<div class="score-box">

<strong>
ATS Compatibility Score:
</strong>

' . $atsScore . '%

</div>


<div class="section">

<div class="section-title">
Key Strengths
</div>

<div class="content">
' . clean($strengths) . '
</div>

</div>


<div class="section">

<div class="section-title">
Recommended Job Roles
</div>

<div class="content">
' . clean($jobRoles) . '
</div>

</div>


<div class="section">

<div class="section-title">
Skills to Improve
</div>

<div class="content">
' . clean($missingSkills) . '
</div>

</div>


<div class="section">

<div class="section-title">
AI Suggestions
</div>

<div class="content">
' . clean($suggestions) . '
</div>

</div>


<div class="footer">

Generated using AI Resume Analyzer

</div>


</body>

</html>
';


// =========================
// GENERATE PDF
// =========================

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper(
    'A4',
    'portrait'
);

$dompdf->render();


// =========================
// DOWNLOAD
// =========================

$dompdf->stream(
    "AI_Resume.pdf",
    [
        "Attachment" => true
    ]
);

exit();

?>