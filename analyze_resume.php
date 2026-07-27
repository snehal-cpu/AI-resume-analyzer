<?php

session_start();

require_once "config/db.php";
require_once "ai/parser.php";


// Login Check
if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


// Check Resume ID

if(!isset($_GET['id']))
{
    die("Resume ID missing");
}


$resumeId = intval($_GET['id']);



// Fetch Resume

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM resumes
     WHERE id=?"
);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $resumeId
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);



if(mysqli_num_rows($result)==0)
{
    die("Resume not found");
}



$resume = mysqli_fetch_assoc($result);



// File Path

$file = $resume['resume_path'];



if(!file_exists($file))
{
    die("Resume file not found");
}



// Extract Text

$resumeText = extractResumeText($file);



if(empty($resumeText))
{
    die("Unable to extract resume text");
}



?>

<!DOCTYPE html>

<html>

<head>

<title>
Resume Analysis
</title>


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>


body{

background:#0f172a;
color:white;
font-family:Poppins,Arial;
padding:40px;

}


.card{

background:#1e293b;
padding:30px;
border-radius:20px;
max-width:900px;
margin:auto;

}


h1{

color:#38bdf8;

}


.resume-box{

background:#111827;
padding:20px;
border-radius:15px;
max-height:400px;
overflow:auto;
white-space:pre-wrap;

}


button{

margin-top:25px;
padding:15px 35px;
border:none;
border-radius:30px;
background:linear-gradient(135deg,#2563eb,#06b6d4);
color:white;
font-size:16px;
cursor:pointer;

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

echo "<pre>";
echo substr($resumeText, 0, 1000);
echo "</pre>";

?>

</div>





<form action="process_analysis.php" method="POST">



<input 
type="hidden"
name="resume_id"
value="<?php echo $resume['id']; ?>">





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