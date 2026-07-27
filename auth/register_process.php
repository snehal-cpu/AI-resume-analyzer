<?php

session_start();

require "../config/db.php";


// =========================
// CHECK REQUEST METHOD
// =========================

if($_SERVER["REQUEST_METHOD"] !== "POST")
{
    header("Location: ../register.php");
    exit();
}



// =========================
// GET FORM DATA
// =========================

$fullname = trim($_POST['fullname'] ?? '');

$email = strtolower(trim($_POST['email'] ?? ''));

$phone = trim($_POST['phone'] ?? '');

$password = $_POST['password'] ?? '';

$confirm_password = $_POST['confirm_password'] ?? '';




// =========================
// EMPTY CHECK
// =========================

if(
empty($fullname) ||
empty($email) ||
empty($password) ||
empty($confirm_password)
)
{
    die("All required fields must be filled.");
}




// =========================
// NAME VALIDATION
// =========================

if(!preg_match("/^[A-Za-z ]+$/", $fullname))
{
    die("Invalid name. Only alphabets and spaces allowed.");
}





// =========================
// EMAIL VALIDATION
// =========================

if(!preg_match(
"/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/",
$email
))
{
    die("Invalid email. Use lowercase email only.");
}





// =========================
// PHONE VALIDATION
// =========================

if(!empty($phone))
{

    if(!preg_match("/^[0-9]{10,12}$/",$phone))
    {
        die("Invalid mobile number. Enter 10 to 12 digits only.");
    }

}




// =========================
// PASSWORD VALIDATION
// =========================


if(strlen($password) < 8)
{
    die("Password must contain minimum 8 characters.");
}


if(!preg_match("/[A-Z]/",$password))
{
    die("Password must contain one uppercase letter.");
}


if(!preg_match("/[a-z]/",$password))
{
    die("Password must contain one lowercase letter.");
}


if(!preg_match("/[0-9]/",$password))
{
    die("Password must contain one number.");
}





// =========================
// CONFIRM PASSWORD
// =========================


if($password !== $confirm_password)
{
    die("Password and confirm password do not match.");
}




// =========================
// CHECK DUPLICATE EMAIL
// =========================


$check = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE email=?"
);


mysqli_stmt_bind_param(
    $check,
    "s",
    $email
);


mysqli_stmt_execute($check);


mysqli_stmt_store_result($check);



if(mysqli_stmt_num_rows($check)>0)
{
    die("Email already registered.");
}


mysqli_stmt_close($check);





// =========================
// PASSWORD HASH
// =========================


$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);





// =========================
// INSERT USER
// =========================


$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO users
    (
        fullname,
        email,
        phone,
        password
    )
    VALUES
    (?,?,?,?)"
);



mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $fullname,
    $email,
    $phone,
    $hashed_password
);





if(mysqli_stmt_execute($stmt))
{

    header(
        "Location: login.php?registered=1"
    );

    exit();

}
else
{

    echo "Registration failed. Please try again.";

}



mysqli_stmt_close($stmt);

mysqli_close($conn);


?>