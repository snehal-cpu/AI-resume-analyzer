<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "config/db.php";
require_once "ai/gemini.php";

// =========================
// FORM VALIDATION
// =========================


$email = strtolower(trim($_POST['email'] ?? ''));

$phone = trim($_POST['phone'] ?? '');

$fullname = trim($_POST['fullname'] ?? '');





// NAME VALIDATION

if(!preg_match("/^[A-Za-z ]+$/", $fullname))
{
    die("Invalid name. Only alphabets and spaces are allowed.");
}






// EMAIL VALIDATION

if(!preg_match(
"/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/",
$email
))
{
    die("Invalid email. Use lowercase letters only.");
}







// PHONE VALIDATION

if(!preg_match(
"/^[0-9]{10,12}$/",
$phone
))
{
    die("Invalid mobile number. Enter 10 to 12 digits only.");
}
/* =====================================
   LOGIN CHECK
===================================== */

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


$user_id = $_SESSION['user_id'];



/* =====================================
   FORM CHECK
===================================== */

if($_SERVER["REQUEST_METHOD"]!="POST")
{
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

$skills = $_POST['skills'] ?? '';

$projects = trim($_POST['projects'] ?? '');

$certificates = trim($_POST['certificates'] ?? '');

$languages = trim($_POST['languages'] ?? '');

$template = $_POST['template'] ?? "professional";

if($fullname=="" || $email=="")
{
    die("Name and Email required");
}




/* =====================================
   EXPERIENCE DATA
===================================== */


$experience="";


if(isset($_POST['company']))
{

foreach($_POST['company'] as $key=>$company)
{

$company = trim($company);

$position = trim($_POST['position'][$key] ?? '');

$start = trim($_POST['start'][$key] ?? '');

$end = trim($_POST['end'][$key] ?? '');

$description = trim($_POST['description'][$key] ?? '');



if($company=="" && $position=="")
{
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


$education="";


if(isset($_POST['college']))
{

foreach($_POST['college'] as $key=>$college)
{


$college = trim($college);

$degree = trim($_POST['degree'][$key] ?? '');

$year = trim($_POST['year'][$key] ?? '');

$cgpa = trim($_POST['cgpa'][$key] ?? '');



if($college=="" && $degree=="")
{
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
   GEMINI AI ANALYSIS
===================================== */


$aiResponse = analyzeResumeAI($resumeText);


// Convert JSON response into PHP array

$aiResult = json_decode(
    $aiResponse,
    true
);



if(!$aiResult)
{

die(
"Gemini Response Invalid"
);

}



/* =====================================
   EXTRACT AI DATA
===================================== */


$atsScore = intval(
$aiResult['ats_score'] ?? 0
);



$strengths = "";

if(isset($aiResult['strengths']))
{
$strengths =
implode(
"\n",
$aiResult['strengths']
);
}



$weaknesses = "";

if(isset($aiResult['weaknesses']))
{
$weaknesses =
implode(
"\n",
$aiResult['weaknesses']
);
}



$missingSkills = "";

if(isset($aiResult['missing_skills']))
{
$missingSkills =
implode(
"\n",
$aiResult['missing_skills']
);
}



$suggestions = "";

if(isset($aiResult['suggestions']))
{
$suggestions =
implode(
"\n",
$aiResult['suggestions']
);
}



$jobRoles = "";

if(isset($aiResult['job_roles']))
{
$jobRoles =
implode(
"\n",
$aiResult['job_roles']
);
}



$interviewQuestions = "";

if(isset($aiResult['interview_questions']))
{
$interviewQuestions =
implode(
"\n",
$aiResult['interview_questions']
);
}



$improvedResume =
$aiResult['improved_resume']
?? $resumeText;



/* =====================================
   SAVE GENERATED RESUME
===================================== */


mysqli_begin_transaction($conn);


try
{


$resumeName =
$fullname."_AI_Resume";



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
(?,?,?,?,?,?,?)"

);



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



mysqli_stmt_execute($stmt);



$resume_id =
mysqli_insert_id($conn);
/* =====================================
   SAVE AI ANALYSIS
===================================== */


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
(?,?,?,?,?,?,?,?,?)"

);



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



mysqli_stmt_execute($analysisStmt);



$analysis_id =
mysqli_insert_id($conn);



/* =====================================
   COMPLETE TRANSACTION
===================================== */


mysqli_commit($conn);



/* =====================================
   REDIRECT TO RESULT PAGE
===================================== */


header(
"Location: builder_result.php?id=".$analysis_id
);

exit();



}

catch(Exception $e)
{


mysqli_rollback($conn);


echo "

<h2>
Resume Generation Failed
</h2>

<p>

".$e->getMessage()."

</p>

";


}



?>


