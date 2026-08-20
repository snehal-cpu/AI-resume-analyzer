<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "config/db.php";
require_once "ai/gemini.php";



/* =====================================
   LOGIN CHECK
===================================== */

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];



/* =====================================
   REQUEST METHOD CHECK
===================================== */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: resume_builder.php");
    exit();
}



/* =====================================
   GET FORM DATA
===================================== */

$fullname = trim($_POST['fullname'] ?? '');

$email = strtolower(trim($_POST['email'] ?? ''));

$phone = trim($_POST['phone'] ?? '');

$address = trim($_POST['address'] ?? '');

$linkedin = trim($_POST['linkedin'] ?? '');

$github = trim($_POST['github'] ?? '');

$summary = trim($_POST['summary'] ?? '');

$skills = trim($_POST['skills'] ?? '');

$projects = trim($_POST['projects'] ?? '');

$certificates = trim($_POST['certificates'] ?? '');

$languages = trim($_POST['languages'] ?? '');

$template = $_POST['template'] ?? "professional";



/* =====================================
   VALIDATION
===================================== */

if ($fullname === "" || $email === "") {
    die("Name and Email are required.");
}


/* Name */

if (!preg_match("/^[A-Za-z ]+$/", $fullname)) {
    die("Invalid name. Only alphabets and spaces are allowed.");
}


/* Email */

if (!preg_match(
    "/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/",
    $email
)) {
    die("Invalid email. Use lowercase letters only.");
}


/* Phone */

if (!preg_match("/^[0-9]{10,12}$/", $phone)) {
    die("Invalid mobile number. Enter 10 to 12 digits only.");
}



/* =====================================
   EXPERIENCE DATA
===================================== */

$experience = "";

if (isset($_POST['company']) && is_array($_POST['company'])) {

    foreach ($_POST['company'] as $key => $company) {

        $company = trim($company);

        $position = trim($_POST['position'][$key] ?? '');

        $start = trim($_POST['start'][$key] ?? '');

        $end = trim($_POST['end'][$key] ?? '');

        $description = trim(
            $_POST['description'][$key] ?? ''
        );


        if ($company === "" && $position === "") {
            continue;
        }


        $experience .= "
Company:
$company

Position:
$position

Duration:
$start - $end

Description:
$description

----------------------------
";
    }
}



/* =====================================
   EDUCATION DATA
===================================== */

$education = "";

if (isset($_POST['college']) && is_array($_POST['college'])) {

    foreach ($_POST['college'] as $key => $college) {

        $college = trim($college);

        $degree = trim(
            $_POST['degree'][$key] ?? ''
        );

        $year = trim(
            $_POST['year'][$key] ?? ''
        );

        $cgpa = trim(
            $_POST['cgpa'][$key] ?? ''
        );


        if ($college === "" && $degree === "") {
            continue;
        }


        $education .= "
College:
$college

Degree:
$degree

Year:
$year

CGPA:
$cgpa

----------------------------
";
    }
}



/* =====================================
   CREATE RESUME TEXT
===================================== */

$resumeText = "

===========================
PERSONAL INFORMATION
===========================

Name:
$fullname

Email:
$email

Phone:
$phone

Address:
$address

LinkedIn:
$linkedin

GitHub:
$github


===========================
PROFESSIONAL SUMMARY
===========================

$summary


===========================
SKILLS
===========================

$skills


===========================
WORK EXPERIENCE
===========================

$experience


===========================
EDUCATION
===========================

$education


===========================
PROJECTS
===========================

$projects


===========================
CERTIFICATES
===========================

$certificates


===========================
LANGUAGES
===========================

$languages

";



/* =====================================
   AI ANALYSIS
===================================== */

$aiResponse = analyzeResumeAI($resumeText);


if (is_array($aiResponse) && !empty($aiResponse['api_error'])) {
    echo "<h2>Gemini AI Error</h2>";
    echo "<pre>";
    echo htmlspecialchars($aiResponse['api_error']);
    echo "</pre>";
    exit();
}


/*
 * analyzeResumeAI() may return either:
 * 1. An array
 * 2. A JSON string
 */

if (is_array($aiResponse)) {

    // Already an array — DO NOT json_decode()
    $aiResult = $aiResponse;

} elseif (is_string($aiResponse)) {

    // JSON string — decode it
    $aiResult = json_decode($aiResponse, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<h2>AI Response Invalid</h2>";
        echo "<pre>";
        echo htmlspecialchars($aiResponse);
        echo "</pre>";
        exit();
    }

} else {

    echo "<h2>AI Response Invalid</h2>";
    echo "<pre>";
    echo "Unexpected AI response type: " . gettype($aiResponse);
    echo "</pre>";
    exit();
}

if (!is_array($aiResult)) {
    echo "<h2>AI Response Invalid</h2>";
    echo "<pre>";
    print_r($aiResult);
    echo "</pre>";
    exit();
}


/* =====================================
   EXTRACT AI DATA
===================================== */

$atsScore = intval(
    $aiResult['ats_score'] ?? 0
);


$strengths = "";

if (isset($aiResult['strengths']) &&
    is_array($aiResult['strengths'])) {

    $strengths = implode(
        "\n",
        $aiResult['strengths']
    );
}


$weaknesses = "";

if (isset($aiResult['weaknesses']) &&
    is_array($aiResult['weaknesses'])) {

    $weaknesses = implode(
        "\n",
        $aiResult['weaknesses']
    );
}


$missingSkills = "";

if (isset($aiResult['missing_skills']) &&
    is_array($aiResult['missing_skills'])) {

    $missingSkills = implode(
        "\n",
        $aiResult['missing_skills']
    );
}


$suggestions = "";

if (isset($aiResult['suggestions']) &&
    is_array($aiResult['suggestions'])) {

    $suggestions = implode(
        "\n",
        $aiResult['suggestions']
    );
}


$jobRoles = "";

if (isset($aiResult['job_roles']) &&
    is_array($aiResult['job_roles'])) {

    $jobRoles = implode(
        "\n",
        $aiResult['job_roles']
    );
}


$interviewQuestions = "";

if (isset($aiResult['interview_questions']) &&
    is_array($aiResult['interview_questions'])) {

    $interviewQuestions = implode(
        "\n",
        $aiResult['interview_questions']
    );
}


$improvedResume =
    $aiResult['improved_resume']
    ?? $resumeText;



/* =====================================
   DATABASE TRANSACTION
===================================== */

mysqli_begin_transaction($conn);

try {


    /* =================================
       SAVE RESUME
    ================================= */

    $resumeName =
        $fullname . "_AI_Resume";

    $resumePath = "";

    $industry = "General";

    $analysisMode = "builder";


    $stmt = mysqli_prepare(
        $conn,

        "INSERT INTO resumes
        (
            user_id,
            resume_name,
            resume_path,
            resume_text,
            industry,
            job_role,
            analysis_mode
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?)"
    );


    if (!$stmt) {
        throw new Exception(
            "Resume prepare failed: " .
            mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $stmt,
        "issssss",
        $user_id,
        $resumeName,
        $resumePath,
        $improvedResume,
        $industry,
        $jobRoles,
        $analysisMode
    );


    if (!mysqli_stmt_execute($stmt)) {

        throw new Exception(
            "Resume insert failed: " .
            mysqli_stmt_error($stmt)
        );
    }


    $resume_id = mysqli_insert_id($conn);


    if ($resume_id <= 0) {
        throw new Exception(
            "Resume ID was not generated."
        );
    }



    /* =================================
       SAVE AI ANALYSIS
    ================================= */

    $analysisStmt = mysqli_prepare(
        $conn,

        "INSERT INTO resume_analysis
        (
            resume_id,
            ats_score,
            strengths,
            weaknesses,
            missing_skills,
            suggestions,
            job_roles,
            interview_questions,
            improved_resume
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );


    if (!$analysisStmt) {

        throw new Exception(
            "Analysis prepare failed: " .
            mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $analysisStmt,
        "iisssssss",
        $resume_id,
        $atsScore,
        $strengths,
        $weaknesses,
        $missingSkills,
        $suggestions,
        $jobRoles,
        $interviewQuestions,
        $improvedResume
    );


    if (!mysqli_stmt_execute($analysisStmt)) {

        throw new Exception(
            "Analysis insert failed: " .
            mysqli_stmt_error($analysisStmt)
        );
    }


    /* =================================
       GET ANALYSIS ID
    ================================= */

    $analysis_id =
        mysqli_insert_id($conn);


    if ($analysis_id <= 0) {

        throw new Exception(
            "Analysis ID was not generated."
        );
    }



    /* =================================
       VERY IMPORTANT
       COMMIT BEFORE REDIRECT
    ================================= */

    mysqli_commit($conn);



    /* =================================
       REDIRECT
    ================================= */

    header(
        "Location: builder_result.php?id=" .
        $analysis_id
    );

    exit();


}

catch (Exception $e) {


    mysqli_rollback($conn);


    echo "<h2>Resume Generation Failed</h2>";

    echo "<p>";

    echo htmlspecialchars(
        $e->getMessage()
    );

    echo "</p>";

}

?>