<?php

session_start();

require_once "config/db.php";


if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}



$user_id = $_SESSION['user_id'];




// Get form data

$name = trim($_POST['name']);

$email = trim($_POST['email']);

$password = $_POST['password'];

$confirm_password = $_POST['confirm_password'];





// ============================
// PASSWORD UPDATE
// ============================


if(!empty($password))
{


    if($password != $confirm_password)
    {
        $_SESSION['error'] = "Passwords do not match";

        header("Location: profile.php");

        exit();
    }



    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );



    $query = mysqli_prepare(
        $conn,
        "
        UPDATE users 
        SET name=?, email=?, password=?
        WHERE id=?
        "
    );



    mysqli_stmt_bind_param(
        $query,
        "sssi",
        $name,
        $email,
        $hashedPassword,
        $user_id
    );



}




// ============================
// ONLY NAME + EMAIL UPDATE
// ============================


else
{


    $query = mysqli_prepare(
        $conn,
        "
        UPDATE users
        SET name=?, email=?
        WHERE id=?
        "
    );



    mysqli_stmt_bind_param(
        $query,
        "ssi",
        $name,
        $email,
        $user_id
    );


}




// Execute update


if(mysqli_stmt_execute($query))
{

    $_SESSION['success'] = "Profile updated successfully";

}
else
{

    $_SESSION['error'] = "Something went wrong";

}



header("Location: profile.php");

exit();


?>