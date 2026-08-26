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



// ================= CHECK INPUT =================

if(
    !isset($_POST['resume_id']) ||
    !isset($_POST['resume_text'])
)
{
    die("Resume data missing");
}


$resume_id = intval($_POST['resume_id']);

$resumeText = trim($_POST['resume_text']);



if($resume_id <= 0)
{
    die("Invalid Resume ID");
}


if(empty($resumeText))
{
    die("Resume text empty");
}





// ================= ATS ANALYSIS =================


$ats = calculateATS($resumeText);


$atsScore = intval(
    $ats['ats_score'] ?? 0
);






// ================= GEMINI AI ANALYSIS =================


$aiResult = analyzeResumeAI($resumeText);

if (is_array($aiResult) && isset($aiResult['api_error'])) {
    die(
        "<h2>Gemini Error</h2><pre>" .
        htmlspecialchars($aiResult['api_error']) .
        "</pre>"
    );
}


// Gemini failed fallback

if(
    !is_array($aiResult) ||
    isset($aiResult['error'])
)
{

    $aiResult = [

        "strengths"=>[
            "Resume analyzed successfully",
            "ATS score generated"
        ],


        "weaknesses"=>[
            "AI suggestions unavailable"
        ],


        "missing_skills"=>
        $ats['missing_skills'] ?? [],


        "suggestions"=>[
            "Add measurable achievements",
            "Improve project descriptions",
            "Add relevant certifications"
        ],


        "job_roles"=>[
            "Software Developer",
            "Web Developer"
        ],


        "interview_questions"=>[
            "Explain your major projects",
            "Describe your technical skills"
        ],


        "improved_resume"=>
        "Improve your resume by adding skills, projects and achievements."

    ];

}





// ================= ARRAY TO TEXT =================


function arrayToText($data)
{

    if(is_array($data))
    {
        return implode("\n",$data);
    }


    return $data ?? "";

}




$strengths =
arrayToText(
    $aiResult['strengths'] ?? []
);



$weaknesses =
arrayToText(
    $aiResult['weaknesses'] ?? []
);



$missingSkills =
arrayToText(
    $aiResult['missing_skills'] ?? []
);



$suggestions =
arrayToText(
    $aiResult['suggestions'] ?? []
);



$jobRoles =
arrayToText(
    $aiResult['job_roles'] ?? []
);



$interviewQuestions =
arrayToText(
    $aiResult['interview_questions'] ?? []
);



$improvedResume =
$aiResult['improved_resume'] ?? "";







// ================= REMOVE OLD REPORT =================


$delete = mysqli_prepare(
    $conn,
    "
    DELETE FROM resume_analysis
    WHERE resume_id=?
    "
);



mysqli_stmt_bind_param(
    $delete,
    "i",
    $resume_id
);



mysqli_stmt_execute($delete);







// ================= INSERT NEW REPORT =================


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




if (mysqli_stmt_execute($stmt)) {

    // Verify that the analysis was actually inserted
    $check = mysqli_prepare(
        $conn,
        "SELECT id, resume_id, ats_score
         FROM resume_analysis
         WHERE resume_id = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param($check, "i", $resume_id);
    mysqli_stmt_execute($check);

    $checkResult = mysqli_stmt_get_result($check);
    $savedAnalysis = mysqli_fetch_assoc($checkResult);

    if (!$savedAnalysis) {

        die(
            "<h2>Analysis was generated but was NOT saved.</h2>" .
            "<p>Resume ID: " . htmlspecialchars($resume_id) . "</p>" .
            "<p>Please check the resume_analysis table and database connection.</p>"
        );

    }

    // Everything is OK
    header(
        "Location: result.php?resume_id=" .
        $resume_id
    );

    exit();

} else {

    die(
        "<h2>Database Error</h2><pre>" .
        htmlspecialchars(mysqli_stmt_error($stmt)) .
        "</pre>"
    );

}





?>