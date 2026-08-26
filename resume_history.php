<?php
session_start();

require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,

"SELECT 
    resume_analysis.*,
    resumes.file_name

FROM resume_analysis

INNER JOIN resumes

ON resume_analysis.resume_id = resumes.id

WHERE resumes.user_id='$user_id'

ORDER BY resume_analysis.id DESC"

);
?>

<!DOCTYPE html>
<html>
<head>

<title>Resume History</title>

<link rel="stylesheet" href="css/history.css">
<link rel="stylesheet" href="assets/css/theme.css">

</head>

<body>

<div class="container">

<h1>📄 Resume History</h1>

<div class="history-grid">

<?php

if(mysqli_num_rows($query) > 0)
{

while($row = mysqli_fetch_assoc($query))
{

?>

<div class="card">

<h3>
<?php echo $row['file_name']; ?>
</h3>


<div class="score">

ATS Score:
<span>
<?php echo $row['ats_score']; ?>%
</span>

</div>


<p>
<b>Skills:</b><br>

<?php echo $row['skills']; ?>

</p>


<p>
<b>Date:</b>

<?php echo $row['created_at']; ?>

</p>


<a href="result.php?id=<?php echo $row['id']; ?>"
class="btn">

View Report

</a>


</div>


<?php

}

}
else
{

echo "<h2>No Resume History Found</h2>";

}

?>


</div>

</div>


</body>
</html>