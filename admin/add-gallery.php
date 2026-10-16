<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$message = "";
$message_type = "";


/* =========================
   HANDLE IMAGE UPLOAD
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $category = trim($_POST['category']);

    /* Check title */
    if (empty($title)) {

        $message = "Please enter an image title.";
        $message_type = "error";

    }

    /* Check image */
    elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {

        $message = "Please select an image.";
        $message_type = "error";

    }

    else {

        $file = $_FILES['image'];

        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];

        /* Get file extension */
        $extension = strtolower(
            pathinfo($file_name, PATHINFO_EXTENSION)
        );

        /* Allowed extensions */
        $allowed_extensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        /* Maximum file size: 5MB */
        $max_size = 5 * 1024 * 1024;


        /* Validate extension */

        if (!in_array($extension, $allowed_extensions)) {

            $message = "Only JPG, JPEG, PNG and WEBP images are allowed.";
            $message_type = "error";

        }

        /* Validate size */

        elseif ($file_size > $max_size) {

            $message = "Image size must not exceed 5MB.";
            $message_type = "error";

        }

        else {

            /* Create unique filename */

            $new_file_name =
                time() . "_" .
                uniqid() . "." .
                $extension;


            /* Upload directory */

            $upload_directory =
                "../assets/uploads/gallery/";


            /* Full file path */

            $upload_path =
                $upload_directory .
                $new_file_name;


            /* Move uploaded image */

            if (move_uploaded_file(
                $file_tmp,
                $upload_path
            )) {

                /* Insert into database */

                $sql = "INSERT INTO gallery
                        (
                            title,
                            category,
                            image
                        )
                        VALUES (?, ?, ?)";

                $stmt = mysqli_prepare(
                    $conn,
                    $sql
                );


                if ($stmt) {

                    mysqli_stmt_bind_param(
                        $stmt,
                        "sss",
                        $title,
                        $category,
                        $new_file_name
                    );


                    if (mysqli_stmt_execute($stmt)) {

                        $message =
                            "Gallery image uploaded successfully.";

                        $message_type = "success";

                    } else {

                        /* Remove uploaded file if database fails */

                        if (file_exists($upload_path)) {
                            unlink($upload_path);
                        }

                        $message =
                            "Database error. Image was not saved.";

                        $message_type = "error";
                    }

                } else {

                    /* Remove uploaded file */

                    if (file_exists($upload_path)) {
                        unlink($upload_path);
                    }

                    $message =
                        "Unable to prepare database query.";

                    $message_type = "error";
                }

            } else {

                $message =
                    "Failed to upload the image.";

                $message_type = "error";
            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Gallery Image | OTS Admin</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fc;
            color: #333;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #171A63;
            color: #fff;
            padding: 25px 18px;
        }

        .brand {
            text-align: center;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .brand img {
            width: 65px;
            height: 65px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .brand h2 {
            font-size: 17px;
            line-height: 1.4;
        }

        .brand p {
            font-size: 11px;
            opacity: 0.75;
            margin-top: 5px;
        }

        .menu {
            margin-top: 25px;
        }

        .menu a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 13px 15px;
            border-radius: 7px;
            margin-bottom: 7px;
            font-size: 14px;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.12);
        }

        .menu a.active {
            background: #D9A400;
        }

        .logout {
            position: absolute;
            bottom: 25px;
            left: 18px;
            right: 18px;
        }

        .logout a {
            display: block;
            text-align: center;
            background: rgba(255,255,255,0.1);
            color: #fff;
            text-decoration: none;
            padding: 12px;
            border-radius: 7px;
        }


        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            padding: 20px 30px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h1 {
            color: #171A63;
            font-size: 24px;
        }

        .admin-name {
            color: #666;
            font-size: 14px;
        }

        .content {
            padding: 30px;
        }


        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            color: #171A63;
            font-size: 22px;
        }

        .page-header p {
            color: #666;
            font-size: 14px;
            margin-top: 6px;
        }


        /* =========================
           FORM
        ========================= */

        .form-card {
            max-width: 700px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #171A63;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #171A63;
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #777;
            font-size: 12px;
        }


        /* =========================
           BUTTON
        ========================= */

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .submit-button {
            background: #171A63;
            color: #fff;
            border: none;
            padding: 13px 22px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .submit-button:hover {
            background: #252A82;
        }

        .back-button {
            background: #f1f1f1;
            color: #333;
            text-decoration: none;
            padding: 13px 22px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
        }


        /* =========================
           MESSAGES
        ========================= */

        .message {
            max-width: 700px;
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .message.success {
            background: #e9f7ef;
            color: #187443;
            border: 1px solid #bce5cc;
        }

        .message.error {
            background: #fdecec;
            color: #a51d2d;
            border: 1px solid #f2b8bd;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .logout {
                position: static;
                margin-top: 20px;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                padding: 18px;
            }

            .content {
                padding: 18px;
            }

            .form-card {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

        }

    </style>

</head>

<body>


<!-- =========================
     SIDEBAR
========================= -->

<aside class="sidebar">

    <div class="brand">

        <img
            src="../assets/images/logo.png"
            alt="Olusegun Theological Seminary"
        >

        <h2>
            Olusegun Theological Seminary
        </h2>

        <p>
            ADMINISTRATION PANEL
        </p>

    </div>


    <nav class="menu">

        <a href="index.php">
            🏠 Dashboard
        </a>

        <a href="applications.php">
            📋 Applications
        </a>

        <a href="messages.php">
            ✉️ Messages
        </a>

        <a href="gallery.php" class="active">
            🖼️ Gallery
        </a>

        <a href="events.php">
            📅 Events
        </a>

        <a href="sermons.php">
            🎥 Sermons
        </a>

        <a href="leadership.php">
            👥 Leadership
        </a>

    </nav>


    <div class="logout">

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</aside>


<!-- =========================
     MAIN
========================= -->

<main class="main">


    <header class="topbar">

        <h1>
            Add Gallery Image
        </h1>


        <div class="admin-name">

            Welcome,

            <strong>

                <?php
                echo htmlspecialchars(
                    $_SESSION['admin_name']
                );
                ?>

            </strong>

        </div>

    </header>


    <section class="content">


        <div class="page-header">

            <h2>
                Upload Gallery Image
            </h2>

            <p>
                Add a new photo to the OTS website gallery.
            </p>

        </div>


        <!-- MESSAGE -->

        <?php if (!empty($message)): ?>

            <div class="message <?php echo $message_type; ?>">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>


        <!-- FORM -->

        <div class="form-card">

            <form
                action=""
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- TITLE -->

                <div class="form-group">

                    <label for="title">
                        Image Title
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="e.g. Seminary Campus"
                        required
                    >

                </div>


            <!-- CATEGORY -->

<div class="form-group">

    <label for="category">
        Category
    </label>

    <select
        id="category"
        name="category"
        required
    >

        <option value="">
            Select Category
        </option>

        <option value="Church">
            Church
        </option>

        <option value="Seminary / School">
            Seminary / School
        </option>

    </select>

</div>


                <!-- IMAGE -->

                <div class="form-group">

                    <label for="image">
                        Select Image
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                    >

                    <span class="help-text">
                        Allowed formats: JPG, JPEG, PNG and WEBP.
                        Maximum size: 5MB.
                    </span>

                </div>


                <!-- BUTTONS -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="submit-button"
                    >
                        Upload Image
                    </button>


                    <a
                        href="gallery.php"
                        class="back-button"
                    >
                        Cancel
                    </a>

                </div>


            </form>

        </div>


    </section>

</main>


</body>

</html>