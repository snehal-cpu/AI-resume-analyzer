<?php

session_start();

require_once "config/db.php";


// Login check

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}


$user_id = $_SESSION['user_id'];



// Check ID

if(!isset($_GET['id']))
{
    header("Location: reports.php");
    exit();
}


$resume_id = intval($_GET['id']);





// Verify ownership

$check = mysqli_prepare(
    $conn,
    "
    SELECT id 
    FROM resumes
    WHERE id=? 
    AND user_id=?
    "
);


mysqli_stmt_bind_param(
    $check,
    "ii",
    $resume_id,
    $user_id
);


mysqli_stmt_execute($check);


$result = mysqli_stmt_get_result($check);



if(mysqli_num_rows($result)==0)
{
    die("Unauthorized action.");
}







// Delete analysis first

$deleteAnalysis = mysqli_prepare(
    $conn,
    "
    DELETE FROM resume_analysis
    WHERE resume_id=?
    "
);


mysqli_stmt_bind_param(
    $deleteAnalysis,
    "i",
    $resume_id
);


mysqli_stmt_execute($deleteAnalysis);







// Delete resume

$deleteResume = mysqli_prepare(
    $conn,
    "
    DELETE FROM resumes
    WHERE id=?
    AND user_id=?
    "
);



mysqli_stmt_bind_param(
    $deleteResume,
    "ii",
    $resume_id,
    $user_id
);



mysqli_stmt_execute($deleteResume);






// Redirect

header(
"Location: reports.php?deleted=success"
);

exit();


?>