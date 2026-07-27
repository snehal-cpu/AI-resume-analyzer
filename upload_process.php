<?php
session_start();

require_once "config/db.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Check form submission
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: upload.php");
    exit();
}

// Check file
if (!isset($_FILES['resume']) || $_FILES['resume']['error'] != 0) {
    die("No resume uploaded.");
}

$user_id = $_SESSION['user_id'];

$fileName = $_FILES['resume']['name'];
$tmpName  = $_FILES['resume']['tmp_name'];
$fileSize = $_FILES['resume']['size'];

$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Only PDF
if ($extension != "pdf") {
    die("Only PDF files are allowed.");
}

// Maximum 5 MB
if ($fileSize > 5 * 1024 * 1024) {
    die("Maximum file size is 5 MB.");
}

// Upload folder
$uploadFolder = "uploads/";

if (!is_dir($uploadFolder)) {
    mkdir($uploadFolder, 0777, true);
}

// Unique filename
$newFileName = time() . "_" . uniqid() . ".pdf";

$filePath = $uploadFolder . $newFileName;

// Upload
if (!move_uploaded_file($tmpName, $filePath)) {
    die("Failed to upload resume.");
}

// Save in database
$sql = "INSERT INTO resumes
(user_id, resume_name, resume_path)
VALUES (?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "iss",
    $user_id,
    $fileName,
    $filePath
);

if (!mysqli_stmt_execute($stmt)) {
    die("Database Error : " . mysqli_error($conn));
}

// Get inserted Resume ID
$resumeId = mysqli_insert_id($conn);

// Redirect
header("Location: analyze_resume.php?id=" . $resumeId);
exit();
?>