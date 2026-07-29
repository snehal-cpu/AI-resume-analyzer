<?php

session_start();

require_once "config/db.php";
require_once "ai/parser.php";


// ================= LOGIN CHECK =================

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


$user_id = $_SESSION['user_id'];



// ================= CHECK RESUME ID =================


if(!isset($_GET['id']))
{
    die("Resume ID missing");
}


$resume_id = intval($_GET['id']);



if($resume_id <= 0)
{
    die("Invalid Resume ID");
}





// ================= FETCH RESUME =================




$sql = "SELECT * FROM resumes WHERE id = $resume_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

 

$resume = mysqli_fetch_assoc($result);







// ================= FILE PATH =================


// ================= FILE PATH =================

$file = $resume['resume_path'];

if (empty($file)) {
    die("Resume file path missing");
}

// Build the absolute path
$absolutePath = __DIR__ . DIRECTORY_SEPARATOR . $file;


if (!file_exists($absolutePath)) {
    die("Resume file not found.");
}

$file = $absolutePath;


// ================= EXTRACT TEXT =================


$resumeText = extractResumeText($file);



if(empty(trim($resumeText)))
{
    die("Unable to extract resume text");
}





?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
Resume AI Analysis
</title>



<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">



<style>


body
{

background:#0f172a;
color:white;
font-family:Poppins,Arial;
padding:40px;

}


.card
{

max-width:900px;
margin:auto;
background:#1e293b;
padding:35px;
border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,.3);

}



h1
{

color:#38bdf8;

}



.resume-box
{

background:#111827;
padding:20px;
border-radius:15px;
max-height:400px;
overflow:auto;
white-space:pre-wrap;

}



button
{

margin-top:25px;
padding:15px 40px;
border:none;
border-radius:30px;
background:linear-gradient(135deg,#2563eb,#06b6d4);
color:white;
font-size:16px;
cursor:pointer;

}


button:hover
{

transform:scale(1.05);

}


</style>


</head>



<body>



<div class="card">


<h1>

<i class="fa-solid fa-robot"></i>

Resume Ready For AI Analysis

</h1>




<p>

Resume:

<b>

<?php echo htmlspecialchars($resume['resume_name']); ?>

</b>

</p>






<div class="resume-box">


<?php


echo htmlspecialchars(
substr($resumeText,0,2000)
);


?>


</div>







<form action="process_analysis.php" method="POST">


<input 
type="hidden"
name="resume_id"
value="<?php echo $resume_id; ?>">






<textarea 
name="resume_text"
hidden><?php echo htmlspecialchars($resumeText); ?></textarea>





<button type="submit">


<i class="fa-solid fa-wand-magic-sparkles"></i>

Analyze With AI


</button>



</form>





</div>



</body>


</html>