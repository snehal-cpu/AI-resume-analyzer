<?php

session_start();

require_once "../config/db.php";


// Enable errors while developing
error_reporting(E_ALL);
ini_set('display_errors',1);



if($_SERVER["REQUEST_METHOD"]!="POST")
{
    header("Location: login.php");
    exit();
}



$email = trim($_POST['email'] ?? '');

$password = $_POST['password'] ?? '';



// Empty check

if(empty($email) || empty($password))
{
    header("Location: login.php?error=empty");
    exit();
}




// Find user

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, fullname, email, password 
     FROM users 
     WHERE email=?"
);



mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);



mysqli_stmt_execute($stmt);



$result = mysqli_stmt_get_result($stmt);





if(mysqli_num_rows($result)!=1)
{
    header("Location: login.php?error=invalid");
    exit();
}



$user = mysqli_fetch_assoc($result);





// Verify password

if(!password_verify($password,$user['password']))
{
    header("Location: login.php?error=invalid");
    exit();
}





// Create session

session_regenerate_id(true);



$_SESSION['user_id'] = $user['id'];

$_SESSION['user_name'] = $user['fullname'];

$_SESSION['user_email'] = $user['email'];





// Debug check (remove after testing)


// echo "<pre>";
// print_r($_SESSION);
// exit();





// Redirect dashboard

header("Location: ../dashboard.php");

exit();


?>