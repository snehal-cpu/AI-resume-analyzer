<?php

session_start();

require_once "config/db.php";


/* ================= LOGIN CHECK ================= */

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


$user_id = $_SESSION['user_id'];





/* ================= USER DETAILS ================= */


$userQuery = mysqli_prepare(
    $conn,
    "SELECT fullname FROM users WHERE id=?"
);


mysqli_stmt_bind_param(
    $userQuery,
    "i",
    $user_id
);


mysqli_stmt_execute($userQuery);


$userResult = mysqli_stmt_get_result($userQuery);


$user = mysqli_fetch_assoc($userResult);






/* ================= TOTAL REPORTS ================= */


$totalReports = 0;


$q1 = mysqli_prepare(
    $conn,
    "
    SELECT COUNT(*) total
    FROM resumes
    WHERE user_id=?
    "
);


mysqli_stmt_bind_param($q1,"i",$user_id);


mysqli_stmt_execute($q1);


$r1=mysqli_stmt_get_result($q1);


if($row=mysqli_fetch_assoc($r1))
{
    $totalReports=$row['total'];
}







/* ================= AVERAGE ATS ================= */


$averageATS=0;


$q2=mysqli_prepare(
$conn,
"
SELECT AVG(ra.ats_score) avgScore

FROM resume_analysis ra

INNER JOIN resumes r

ON ra.resume_id=r.id

WHERE r.user_id=?
"
);


mysqli_stmt_bind_param(
$q2,
"i",
$user_id
);


mysqli_stmt_execute($q2);


$r2=mysqli_stmt_get_result($q2);


if($row=mysqli_fetch_assoc($r2))
{

if($row['avgScore']!=NULL)
{
    $averageATS=round($row['avgScore']);
}

}







/* ================= HIGHEST ATS ================= */


$highestATS=0;


$q3=mysqli_prepare(
$conn,
"
SELECT MAX(ra.ats_score) maxScore

FROM resume_analysis ra

INNER JOIN resumes r

ON ra.resume_id=r.id

WHERE r.user_id=?
"
);



mysqli_stmt_bind_param(
$q3,
"i",
$user_id
);


mysqli_stmt_execute($q3);


$r3=mysqli_stmt_get_result($q3);


if($row=mysqli_fetch_assoc($r3))
{

if($row['maxScore']!=NULL)
{
    $highestATS=$row['maxScore'];
}

}






/* ================= REPORT LIST ================= */


$query=mysqli_prepare(
$conn,

"
SELECT

r.id AS resume_id,

r.resume_name,

r.uploaded_at,

ra.id AS analysis_id,

ra.ats_score


FROM resumes r


LEFT JOIN resume_analysis ra


ON r.id=ra.resume_id


WHERE r.user_id=?


ORDER BY r.id DESC

"
);



mysqli_stmt_bind_param(
$query,
"i",
$user_id
);



mysqli_stmt_execute($query);



$reports=mysqli_stmt_get_result($query);



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport"
content="width=device-width, initial-scale=1.0">


<title>
AI Resume Reports
</title>



<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">



<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">



<link
rel="stylesheet"
href="assets/css/reports.css">



</head>




<body>





<!-- ================= SIDEBAR ================= -->


<div class="sidebar">


<div class="logo">

<i class="fa-solid fa-robot"></i>

<span>
ResumeAI
</span>


</div>



<ul>


<li>

<a href="dashboard.php">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

</li>




<li>

<a href="upload.php">

<i class="fa-solid fa-upload"></i>

Upload Resume

</a>

</li>





<li class="active">

<a href="reports.php">

<i class="fa-solid fa-chart-column"></i>

Reports

</a>

</li>





<li>

<a href="resume_builder.php">

<i class="fa-solid fa-file-pen"></i>

Resume Builder

</a>

</li>





<li>

<a href="profile.php">

<i class="fa-solid fa-user"></i>

Profile

</a>

</li>





<li>

<a href="settings.php">

<i class="fa-solid fa-gear"></i>

Settings

</a>

</li>





<li>

<a href="auth/logout.php">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</li>



</ul>


</div>








<!-- ================= MAIN ================= -->


<div class="main">





<div class="page-title">


<h2>

<i class="fa-solid fa-file-circle-check"></i>

Resume Reports

</h2>


<p>

View and manage your AI analyzed resumes

</p>


</div>







<!-- ================= STATISTICS ================= -->



<div class="row g-4 mb-4">



<div class="col-md-4">

<div class="stat-card">


<h3>

<?php echo $totalReports; ?>

</h3>


<p>

Total Reports

</p>


</div>

</div>






<div class="col-md-4">

<div class="stat-card">


<h3>

<?php echo $averageATS; ?>%

</h3>


<p>

Average ATS Score

</p>


</div>

</div>







<div class="col-md-4">

<div class="stat-card">


<h3>

<?php echo $highestATS; ?>%

</h3>


<p>

Highest ATS Score

</p>


</div>

</div>




</div>









<!-- SEARCH -->


<div class="search-box">


<i class="fa-solid fa-search"></i>


<input

type="text"

id="searchInput"

placeholder="Search resume...">


</div>









<!-- REPORT TABLE -->


<div class="table-card">



<table class="table table-dark table-hover">


<thead>


<tr>

<th>
Resume
</th>


<th>
ATS Score
</th>


<th>
Date
</th>


<th>
Status
</th>


<th>
Action
</th>


</tr>


</thead>





<tbody id="reportTable">



<?php


if(mysqli_num_rows($reports)>0)

{


while($row=mysqli_fetch_assoc($reports))

{


$score=$row['ats_score'] ?? 0;



?>



<tr>



<td>

<i class="fa-solid fa-file-pdf text-danger"></i>


<?php echo htmlspecialchars($row['resume_name']); ?>


</td>





<td>


<?php echo $score; ?>%


</td>





<td>


<?php

echo date(
"d M Y",
strtotime($row['uploaded_at'])
);

?>


</td>







<td>



<?php


if($score>=80)

{

echo '<span class="badge bg-success">
Excellent
</span>';

}

elseif($score>=60)

{

echo '<span class="badge bg-warning text-dark">
Good
</span>';

}

else

{

echo '<span class="badge bg-danger">
Needs Improvement
</span>';

}


?>



</td>







<td>



<?php if($row['analysis_id']){ ?>


<a

href="result.php?resume_id=<?php echo $row['analysis_id']; ?>"

class="btn btn-primary btn-sm">


<i class="fa-solid fa-eye"></i>

View


</a>


<?php } else { ?>


<button class="btn btn-secondary btn-sm" disabled>

Not Analyzed

</button>


<?php } ?>





<a

href="delete_report.php?id=<?php echo $row['resume_id']; ?>"

class="btn btn-danger btn-sm">


<i class="fa-solid fa-trash"></i>


</a>




</td>





</tr>



<?php


}


}

else

{


?>


<tr>


<td colspan="5"
class="text-center">


<i class="fa-solid fa-folder-open fa-3x"></i>


<br><br>


No Reports Found


<br><br>


<a href="upload.php"
class="btn btn-primary">

Upload Resume

</a>


</td>


</tr>


<?php

}


?>



</tbody>



</table>



</div>






</div>







<script src="assets/js/reports.js"></script>


</body>


</html>