<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';


/* =========================
   FORM VARIABLES
========================= */

$title = "";
$category = "Church";
$description = "";
$event_date = "";
$event_time = "";
$location = "";

$success = "";
$error = "";


/* =========================
   HANDLE FORM SUBMISSION
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

   $title = trim($_POST['title']);
$category = trim($_POST['category']);
$description = trim($_POST['description']);
if ($category !== 'Church' && $category !== 'Seminary / School') {
 $category = "";
}
    $event_date = trim($_POST['event_date'] ?? "");
    $event_time = trim($_POST['event_time'] ?? "");
    $location = trim($_POST['location'] ?? "");


    /* =========================
       VALIDATION
    ========================= */

    if ($title === "") {

        $error = "Please enter the event title.";

    } elseif ($event_date === "") {

        $error = "Please select the event date.";

    } else {


        /* =========================
           IMAGE UPLOAD
        ========================= */

        $image_name = "";


        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
        ) {


            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {

                $error = "There was a problem uploading the image.";

            } else {


                $file_tmp =
                    $_FILES['image']['tmp_name'];

                $original_name =
                    $_FILES['image']['name'];

                $file_size =
                    $_FILES['image']['size'];


                /* Get extension */

                $extension =
                    strtolower(
                        pathinfo(
                            $original_name,
                            PATHINFO_EXTENSION
                        )
                    );


                /* Allowed extensions */

                $allowed_extensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ];


                /* Maximum size: 5MB */

                $max_size = 5 * 1024 * 1024;


                if (
                    !in_array(
                        $extension,
                        $allowed_extensions
                    )
                ) {

                    $error =
                        "Invalid image format. Please upload JPG, JPEG, PNG or WEBP.";

                } elseif ($file_size > $max_size) {

                    $error =
                        "Image is too large. Maximum size is 5MB.";

                } else {


                    /* Upload directory */

                    $upload_directory =
                        "../assets/uploads/events/";


                    /* Create directory if it doesn't exist */

                    if (
                        !is_dir(
                            $upload_directory
                        )
                    ) {

                        mkdir(
                            $upload_directory,
                            0755,
                            true
                        );

                    }


                    /* Generate unique filename */

                    $image_name =
                        time() .
                        "_" .
                        uniqid() .
                        "." .
                        $extension;


                    $destination =
                        $upload_directory .
                        $image_name;


                    if (
                        !move_uploaded_file(
                            $file_tmp,
                            $destination
                        )
                    ) {

                        $error =
                            "Failed to save the uploaded image.";

                    }

                }

            }

        }


        /* =========================
           SAVE EVENT
        ========================= */

        if ($error === "") {


            $sql = "
       INSERT INTO events (title, category, description, event_date, event_time, location, image)
VALUES (?, ?, ?, ?, ?, ?, ?)
            ";


            $stmt =
                mysqli_prepare(
                    $conn,
                    $sql
                );


            if ($stmt) {

              $stmt->bind_param(
    "sssssss",
    $title,
    $category,
    $description,
    $event_date,
    $event_time,
    $location,
$image_name
);


                if (
                    mysqli_stmt_execute(
                        $stmt
                    )
                ) {

                    $success =
                        "Event added successfully.";

                    /* Clear form */

                    $title = "";
                    $description = "";
                    $event_date = "";
                    $event_time = "";
                    $location = "";

                } else {

                    /* Delete uploaded image if DB insert fails */

                    if (
                        !empty($image_name)
                    ) {

                        $uploaded_file =
                            "../assets/uploads/events/" .
                            $image_name;

                        if (
                            file_exists(
                                $uploaded_file
                            )
                        ) {

                            unlink(
                                $uploaded_file
                            );

                        }

                    }


                    $error =
                        "Failed to save the event: " .
                        mysqli_error($conn);

                }


                mysqli_stmt_close(
                    $stmt
                );

            } else {

                $error =
                    "Could not prepare the database query.";

            }

        }

    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Event | OTS Admin</title>


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
            margin-top: 5px;
        }


        /* =========================
           FORM CARD
        ========================= */

        .form-card {
            max-width: 850px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 30px;
        }


        .form-group {
            margin-bottom: 20px;
        }


        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }


        label {
            display: block;
            color: #171A63;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }


        .required {
            color: #b00020;
        }


       input,
textarea,
select {
    width: 100%;
    border: 1px solid #dcdcdc;
    border-radius: 7px;
    padding: 12px 13px;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    color: #333;
    outline: none;
    background: #fff;
}


        input:focus,
textarea:focus,
select:focus {
    border-color: #171A63;
}


        textarea {
            min-height: 150px;
            resize: vertical;
            line-height: 1.6;
        }


        input[type="file"] {
            padding: 10px;
            background: #fafafd;
        }


        .help-text {
            color: #777;
            font-size: 11px;
            margin-top: 6px;
        }


        /* =========================
           IMAGE PREVIEW
        ========================= */

        .image-preview {
            display: none;
            margin-top: 12px;
            max-width: 300px;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
        }


        /* =========================
           ALERTS
        ========================= */

        .alert {
            padding: 13px 15px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 13px;
        }


        .alert-success {
            background: #eaf7ed;
            color: #176b2c;
            border: 1px solid #c7e8ce;
        }


        .alert-error {
            background: #fff0f0;
            color: #a00020;
            border: 1px solid #f1c7cd;
        }


        /* =========================
           BUTTONS
        ========================= */

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }


        .submit-button {
            border: none;
            background: #D9A400;
            color: #fff;
            padding: 12px 20px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }


        .submit-button:hover {
            background: #B88600;
        }


        .back-button {
            display: inline-block;
            background: #f5f6fc;
            color: #171A63;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
        }


        .back-button:hover {
            background: #e8e9f5;
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


            .content {
                padding: 18px;
            }


            .topbar {
                padding: 18px;
            }


            .form-card {
                padding: 20px;
            }


            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }


            .page-header h2 {
                font-size: 20px;
            }


            .form-actions {
                flex-direction: column;
            }


            .submit-button,
            .back-button {
                text-align: center;
                width: 100%;
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


        <a href="gallery.php">
            🖼️ Gallery
        </a>


        <a
            href="events.php"
            class="active"
        >
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
            Add Event
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


        <!-- PAGE HEADER -->

        <div class="page-header">

            <h2>
                Create New Event
            </h2>

            <p>
                Add an event that will be displayed on the OTS website.
            </p>

        </div>


        <!-- ALERTS -->

        <?php if ($success !== ""): ?>

            <div class="alert alert-success">

                <?php
                echo htmlspecialchars(
                    $success
                );
                ?>

            </div>

        <?php endif; ?>


        <?php if ($error !== ""): ?>

            <div class="alert alert-error">

                <?php
                echo htmlspecialchars(
                    $error
                );
                ?>

            </div>

        <?php endif; ?>


        <!-- FORM -->

        <div class="form-card">

            <form
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- TITLE -->

                <div class="form-group">

                    <label for="title">

                        Event Title

                        <span class="required">
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?php echo htmlspecialchars($title); ?>"
                        placeholder="e.g. Annual Seminary Conference"
                        required
                    >

                </div>
              <!-- EVENT CATEGORY -->

<div class="form-group">

    <label for="category">

        Event Category

        <span class="required">
            *
        </span>

    </label>

    <select
        id="category"
        name="category"
        required
    >

        <option
            value=""
            <?php echo ($category === "") ? "selected" : ""; ?>
        >
            Select Event Category
        </option>

        <option
            value="Church"
            <?php echo ($category === "Church") ? "selected" : ""; ?>
        >
            Church
        </option>

        <option
            value="Seminary / School"
            <?php echo ($category === "Seminary / School") ? "selected" : ""; ?>
        >
            Seminary / School
        </option>

    </select>

</div>


                <!-- DATE / TIME -->

                <div class="form-row">


                    <div class="form-group">

                        <label for="event_date">

                            Event Date

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="date"
                            id="event_date"
                            name="event_date"
                            value="<?php echo htmlspecialchars($event_date); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="event_time">
                            Event Time
                        </label>

                        <input
                            type="time"
                            id="event_time"
                            name="event_time"
                            value="<?php echo htmlspecialchars($event_time); ?>"
                        >

                    </div>


                </div>


                <!-- LOCATION -->

                <div class="form-group">

                    <label for="location">
                        Location
                    </label>

                    <input
                        type="text"
                        id="location"
                        name="location"
                        value="<?php echo htmlspecialchars($location); ?>"
                        placeholder="e.g. OTS Main Campus, Ogun State"
                    >

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label for="description">
                        Event Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        placeholder="Write a short description of the event..."
                    ><?php echo htmlspecialchars($description); ?></textarea>

                </div>


                <!-- IMAGE -->

                <div class="form-group">

                    <label for="image">
                        Event Image
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <p class="help-text">
                        Accepted formats: JPG, JPEG, PNG and WEBP.
                        Maximum size: 5MB.
                    </p>


                    <img
                        id="imagePreview"
                        class="image-preview"
                        src=""
                        alt="Image preview"
                    >

                </div>


                <!-- BUTTONS -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="submit-button"
                    >
                        Add Event
                    </button>


                    <a
                        href="events.php"
                        class="back-button"
                    >
                        Back to Events
                    </a>

                </div>


            </form>

        </div>


    </section>

</main>


<script>

/* =========================
   IMAGE PREVIEW
========================= */

const imageInput =
    document.getElementById("image");

const imagePreview =
    document.getElementById("imagePreview");


imageInput.addEventListener(
    "change",
    function () {

        const file =
            this.files[0];

        if (!file) {

            imagePreview.style.display =
                "none";

            imagePreview.src =
                "";

            return;
        }


        const reader =
            new FileReader();


        reader.onload =
            function (event) {

                imagePreview.src =
                    event.target.result;

                imagePreview.style.display =
                    "block";

            };


        reader.readAsDataURL(file);

    }
);

</script>


</body>

</html>