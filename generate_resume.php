<?php

session_start();

require_once "config/db.php";
require_once "ai/gemini.php";


/* ================= LOGIN CHECK ================= */

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


$user_id = $_SESSION['user_id'];



/* ================= FILE CHECK ================= */


if(!isset($_FILES['resume']))
{
    die("Resume file missing");
}


$file = $_FILES['resume'];



if($file['error'] != 0)
{
    die("File upload error");
}



$fileExtension = strtolower(
    pathinfo($file['name'], PATHINFO_EXTENSION)
);



if($fileExtension != "pdf")
{
    die("Only PDF files are allowed");
}



/* ================= UPLOAD PDF ================= */


$uploadFolder = "uploads/";


if(!is_dir($uploadFolder))
{
    mkdir($uploadFolder,0777,true);
}



$newFileName = time()."_".$file['name'];


$filePath = $uploadFolder.$newFileName;



if(!move_uploaded_file(
    $file['tmp_name'],
    $filePath
))
{
    die("File upload failed");
}





/* ================= EXTRACT PDF TEXT ================= */


require_once "vendor/autoload.php";


use Smalot\PdfParser\Parser;


try
{

    $parser = new Parser();

    $pdf = $parser->parseFile($filePath);

    $resumeText = $pdf->getText();

}

catch(Exception $e)
{

    die(
        "PDF Reading Error : ".$e->getMessage()
    );

}




if(trim($resumeText)=="")
{
    die("Could not extract text from PDF");
}





/* ================= SAVE RESUME ================= */


$stmt = mysqli_prepare(
$conn,

"INSERT INTO resumes
(
user_id,
resume_name,
resume_path,
resume_text
)
VALUES(?,?,?,?)"

);


mysqli_stmt_bind_param(
$stmt,
"isss",

$user_id,
$newFileName,
$filePath,
$resumeText

);



mysqli_stmt_bind_param(
$stmt,
"iss",

$user_id,
$newFileName,
$filePath

);



mysqli_stmt_execute($stmt);



$resume_id = mysqli_insert_id($conn);








/* ================= GEMINI AI ANALYSIS ================= */


$aiResult = analyzeResumeAI($resumeText);



if(isset($aiResult['error']))
{

die(
"AI Error : ".$aiResult['message']
);

}





/* ================= GET AI DATA ================= */


$atsScore = $aiResult['ats_score'] ?? 0;


$strengths = implode(
"\n",
$aiResult['strengths'] ?? []
);


$weaknesses = implode(
"\n",
$aiResult['weaknesses'] ?? []
);


$missingSkills = implode(
"\n",
$aiResult['missing_skills'] ?? []
);


$suggestions = implode(
"\n",
$aiResult['suggestions'] ?? []
);


$jobRoles = implode(
"\n",
$aiResult['job_roles'] ?? []
);


$questions = implode(
"\n",
$aiResult['interview_questions'] ?? []
);


$improvedResume = 
$aiResult['improved_resume'] ?? 
"Resume improvement not generated";







/* ================= SAVE ANALYSIS ================= */


$query = mysqli_prepare(
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

VALUES(?,?,?,?,?,?,?,?,?)"

);



mysqli_stmt_bind_param(

$query,

"iisssssss",

$resume_id,

$atsScore,

$strengths,

$weaknesses,

$missingSkills,

$suggestions,

$jobRoles,

$questions,

$improvedResume

);



if(!mysqli_stmt_execute($query))
{
    die(
        "Database Error : ".
        mysqli_error($conn)
    );
}



$analysis_id = mysqli_insert_id($conn);






/* ================= REDIRECT RESULT ================= */


header(
"Location: result.php?id=".$analysis_id
);


exit();


?>