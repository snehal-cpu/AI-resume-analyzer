<?php

session_start();

require_once __DIR__ . "/config/db.php";

require_once __DIR__ . "/vendor/autoload.php";


use Dompdf\Dompdf;


/* =========================
LOGIN CHECK
========================= */

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}



/* =========================
GET ANALYSIS ID
========================= */

if(!isset($_GET['id']))
{
    die("Analysis ID missing");
}


$id = intval($_GET['id']);





/* =========================
FETCH ANALYSIS
========================= */


$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM resume_analysis
     WHERE id=?"
);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);



if(mysqli_num_rows($result)==0)
{
    die("Resume data not found");
}



$data = mysqli_fetch_assoc($result);





/* =========================
CREATE HTML RESUME
========================= */


$html = '

<!DOCTYPE html>

<html>

<head>

<style>

body{

font-family:Arial,sans-serif;

color:#222;

}


h1{

color:#2563eb;

font-size:30px;

}


h2{

color:#2563eb;

border-bottom:1px solid #ddd;

padding-bottom:5px;

}


.section{

margin-bottom:20px;

}


.box{

background:#f5f7fb;

padding:15px;

border-radius:10px;

}


</style>

</head>


<body>


<h1>
AI Generated Resume
</h1>


<div class="section">

<h2>
ATS Score
</h2>

<div class="box">

'.$data['ats_score'].'%

</div>

</div>




<div class="section">

<h2>
Professional Strengths
</h2>

<div class="box">

'.$data['strengths'].'

</div>

</div>




<div class="section">

<h2>
Weaknesses
</h2>

<div class="box">

'.$data['weaknesses'].'

</div>

</div>




<div class="section">

<h2>
Missing Skills
</h2>

<div class="box">

'.$data['missing_skills'].'

</div>

</div>




<div class="section">

<h2>
Suggestions
</h2>

<div class="box">

'.$data['suggestions'].'

</div>

</div>




<div class="section">

<h2>
Recommended Job Roles
</h2>

<div class="box">

'.$data['job_roles'].'

</div>

</div>




<div class="section">

<h2>
Improved Resume
</h2>

<div class="box">

'.$data['improved_resume'].'

</div>

</div>



</body>

</html>

';





/* =========================
GENERATE PDF
========================= */


$dompdf = new Dompdf();


$dompdf->loadHtml($html);


$dompdf->setPaper(
    'A4',
    'portrait'
);


$dompdf->render();



$dompdf->stream(
    "AI_Resume.pdf",
    [
        "Attachment"=>true
    ]
);


?>