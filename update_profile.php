<?php

session_start();

require_once "config/db.php";


/* =========================================================
   CHECK LOGIN
========================================================= */

if (!isset($_SESSION['user_id'])) {

    header("Location: auth/login.php");
    exit();

}

$user_id = $_SESSION['user_id'];


/* =========================================================
   UPDATE NAME + EMAIL
========================================================= */

if (
    isset($_POST['fullname']) &&
    isset($_POST['email'])
) {

    $fullname = trim($_POST['fullname']);

    $email = trim($_POST['email']);


    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET fullname = ?, email = ?
         WHERE id = ?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $fullname,
        $email,
        $user_id
    );


    mysqli_stmt_execute($stmt);

}


/* =========================================================
   PROFILE PHOTO UPLOAD
========================================================= */

if (
    isset($_FILES['profile_photo']) &&
    $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK
) {


    $file = $_FILES['profile_photo'];


    /* Maximum 5 MB */

    if ($file['size'] > 5 * 1024 * 1024) {

        die("Profile photo must be smaller than 5 MB.");

    }


    /* Check actual image */

    $imageInfo = getimagesize(
        $file['tmp_name']
    );


    if ($imageInfo === false) {

        die("Invalid image file.");

    }


    $mime = $imageInfo['mime'];


    /* Allowed formats */

    $allowedTypes = [

        "image/jpeg" => "jpg",

        "image/png" => "png",

        "image/webp" => "webp"

    ];


    if (!isset($allowedTypes[$mime])) {

        die(
            "Only JPG, PNG and WEBP images are allowed."
        );

    }


    $extension =
        $allowedTypes[$mime];


    /* =====================================================
       CREATE UPLOAD DIRECTORY
    ===================================================== */

    $uploadDir =
        __DIR__ .
        "/uploads/profile_photos/";


    if (!is_dir($uploadDir)) {

        if (!mkdir(
            $uploadDir,
            0777,
            true
        )) {

            die(
                "Unable to create profile photo folder."
            );

        }

    }


    /* =====================================================
       GET OLD PHOTO
    ===================================================== */

    $oldStmt = mysqli_prepare(
        $conn,
        "SELECT profile_photo
         FROM users
         WHERE id = ?"
    );


    mysqli_stmt_bind_param(
        $oldStmt,
        "i",
        $user_id
    );


    mysqli_stmt_execute(
        $oldStmt
    );


    $oldResult =
        mysqli_stmt_get_result(
            $oldStmt
        );


    $oldUser =
        mysqli_fetch_assoc(
            $oldResult
        );


    $oldPhoto =
        $oldUser['profile_photo'] ?? "";


    /* =====================================================
       UNIQUE FILE NAME
    ===================================================== */

    $fileName =
        "user_" .
        $user_id .
        "_" .
        time() .
        "." .
        $extension;


    $destination =
        $uploadDir .
        $fileName;


    /* =====================================================
       MOVE FILE
    ===================================================== */

    if (
        !move_uploaded_file(
            $file['tmp_name'],
            $destination
        )
    ) {

        die(
            "Unable to upload profile photo."
        );

    }


    /* =====================================================
       DATABASE PATH
    ===================================================== */

    $photoPath =
        "uploads/profile_photos/" .
        $fileName;


    /* =====================================================
       SAVE PHOTO PATH
    ===================================================== */

    $photoStmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET profile_photo = ?
         WHERE id = ?"
    );


    mysqli_stmt_bind_param(
        $photoStmt,
        "si",
        $photoPath,
        $user_id
    );


    if (
        !mysqli_stmt_execute(
            $photoStmt
        )
    ) {

        if (is_file($destination)) {

            unlink($destination);

        }


        die(
            "Unable to save profile photo."
        );

    }


    /* =====================================================
       DELETE OLD PHOTO
    ===================================================== */

    if (!empty($oldPhoto)) {

        $oldPath =
            __DIR__ .
            "/" .
            $oldPhoto;


        if (
            is_file($oldPath) &&
            strpos(
                realpath($oldPath),
                realpath($uploadDir)
            ) === 0
        ) {

            unlink($oldPath);

        }

    }

}


/* =========================================================
   RETURN TO PROFILE
========================================================= */

header(
    "Location: profile.php"
);

exit();

?>