<?php

session_start();

require_once "config/db.php";
require_once "ai/gemini.php";
require_once "ats_engine.php";


// ================= LOGIN CHECK =================

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


// ================= CHECK DATA =================

if(!isset($_POST['resume_text']) || !isset($_POST['resume_id']))
{
    die("Resume data missing");
}


$resume_id = intval($_POST['resume_id']);

$resumeText = trim($_POST['resume_text']);



if(empty($resumeText))
{
    die("Resume text empty");
}



// ================= ATS ENGINE =================

$ats = calculateATS($resumeText);

$atsScore = $ats['ats_score'];

// ================= GEMINI AI =================

$aiResult = analyzeResumeAI($resumeText);

// If Gemini fails, use local defaults

if(isset($aiResult['error']))
{
    $aiResult = [

        "strengths"=>[
            "Resume analyzed successfully.",
            "ATS score generated locally."
        ],

        "weaknesses"=>[
            "AI service unavailable."
        ],

        "missing_skills"=>$ats['missing_skills'],

        "suggestions"=>[
            "Add more technical skills.",
            "Improve project descriptions.",
            "Include certifications."
        ],

        "job_roles"=>[
            "Software Developer"
        ],

        "interview_questions"=>[
            "Explain your projects."
        ],

        "improved_resume"=>"Improve your resume by adding achievements and technical skills."

    ];
}



// ================= CONVERT ARRAYS =================



$strengths = "";

if(isset($aiResult['strengths']))
{
    $strengths = implode(
        "\n",
        $aiResult['strengths']
    );
}



$weaknesses = "";

if(isset($aiResult['weaknesses']))
{
    $weaknesses = implode(
        "\n",
        $aiResult['weaknesses']
    );
}




$missingSkills = "";

if(isset($aiResult['missing_skills']))
{
    $missingSkills = implode(
        "\n",
        $aiResult['missing_skills']
    );
}





$suggestions = "";

if(isset($aiResult['suggestions']))
{
    $suggestions = implode(
        "\n",
        $aiResult['suggestions']
    );
}





$jobRoles = "";

if(isset($aiResult['job_roles']))
{
    $jobRoles = implode(
        "\n",
        $aiResult['job_roles']
    );
}





$interviewQuestions = "";

if(isset($aiResult['interview_questions']))
{
    $interviewQuestions = implode(
        "\n",
        $aiResult['interview_questions']
    );
}





$improvedResume = "";

if(isset($aiResult['improved_resume']))
{
    $improvedResume =
    $aiResult['improved_resume'];
}




// ================= DELETE OLD ANALYSIS =================
// Prevent duplicate reports


$delete = mysqli_prepare(
    $conn,

    "DELETE FROM resume_analysis
     WHERE resume_id=?"
);


mysqli_stmt_bind_param(
    $delete,
    "i",
    $resume_id
);


mysqli_stmt_execute($delete);





// ================= SAVE ANALYSIS =================


$query = "

INSERT INTO resume_analysis

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
(?,?,?,?,?,?,?,?,?)

";



$stmt = mysqli_prepare(
    $conn,
    $query
);



mysqli_stmt_bind_param(

$stmt,

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





if(mysqli_stmt_execute($stmt))
{

    $analysis_id = mysqli_insert_id($conn);


    header(
        "Location: result.php?id=".$analysis_id
    );

    exit();

}

else
{

    echo "Database Error : ".
    mysqli_error($conn);

}



?>