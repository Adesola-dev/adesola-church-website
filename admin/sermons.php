<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';


/* =========================
   DELETE SERMON
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    /* Get thumbnail before deleting */
    $stmt = mysqli_prepare(
        $conn,
        "SELECT thumbnail FROM sermons WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $sermon = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);


    /* Delete database record */
    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM sermons WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);


    /* Delete thumbnail */
    if ($sermon && !empty($sermon['thumbnail'])) {

        $thumbnail =
            "../assets/uploads/sermons/" .
            $sermon['thumbnail'];

        if (file_exists($thumbnail)) {
            unlink($thumbnail);
        }
    }


    header("Location: sermons.php?deleted=1");
    exit();
}


/* =========================
   GET SERMONS
========================= */

$category = $_GET['category'] ?? 'all';

if ($category === 'Church') {

    $result = mysqli_query(
        $conn,
        "SELECT *
         FROM sermons
         WHERE category = 'Church'
         ORDER BY sermon_date DESC, id DESC"
    );

} elseif ($category === 'Seminary / School') {

    $result = mysqli_query(
        $conn,
        "SELECT *
         FROM sermons
         WHERE category = 'Seminary / School'
         ORDER BY sermon_date DESC, id DESC"
    );

} else {

    $result = mysqli_query(
        $conn,
        "SELECT *
         FROM sermons
         ORDER BY sermon_date DESC, id DESC"
    );

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

    <title>Sermons | OTS Admin</title>


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
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
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


        .add-button {
            display: inline-block;
            background: #D9A400;
            color: #fff;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
        }


        .add-button:hover
        /* =========================
   SERMON FILTERS
========================= */

.sermon-filters {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.sermon-filters a {
    display: inline-block;
    padding: 9px 14px;
    background: #f5f6fc;
    color: #171A63;
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #e5e5e5;
}

.sermon-filters a:hover {
    background: #e8e9f5;
}

.sermon-filters a.filter-active {
    background: #171A63;
    color: #fff;
    border-color: #171A63;
} {
            background: #B88600;
        }


        /* =========================
           ALERT
        ========================= */

        .alert {
            padding: 13px 15px;
            background: #eaf7ed;
            color: #176b2c;
            border: 1px solid #c7e8ce;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 13px;
        }


        /* =========================
           TABLE
        ========================= */

        .table-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }


        th {
            background: #171A63;
            color: #fff;
            text-align: left;
            padding: 14px;
            font-size: 13px;
        }


        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: middle;
        }


        tr:last-child td {
            border-bottom: none;
        }


        .sermon-thumbnail {
            width: 80px;
            height: 55px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e5e5;
        }


        .no-thumbnail {
            width: 80px;
            height: 55px;
            background: #f5f6fc;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-size: 11px;
        }


        .sermon-title {
            color: #171A63;
            font-weight: 700;
        }


        .category {
            display: inline-block;
            background: #f5f6fc;
            color: #171A63;
            padding: 6px 9px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 700;
        }


        .video-link {
            color: #171A63;
            text-decoration: none;
            font-weight: 700;
        }


        .video-link:hover {
            text-decoration: underline;
        }


        .audio-link {
            color: #B88600;
            text-decoration: none;
            font-weight: 700;
        }


        .actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }


        .action-button {
            display: inline-block;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 700;
        }


        .edit-button {
            background: #f5f6fc;
            color: #171A63;
        }


        .delete-button {
            background: #fff0f0;
            color: #a00020;
        }


        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }


        .empty-state h3 {
            color: #171A63;
            margin-bottom: 8px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 800px) {

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


            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>

</head>


<body>


<!-- SIDEBAR -->

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

        <a href="events.php">
            📅 Events
        </a>

        <a href="sermons.php" class="active">
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


<!-- MAIN -->

<main class="main">


    <header class="topbar">

        <h1>
            Sermons
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

            <div>

                <h2>
                    Sermon Management
                </h2>

                <p>
                    Manage sermons, teachings and ministry messages.
                </p>

            </div>


            <a
                href="add-sermon.php"
                class="add-button"
            >
                + Add Sermon
            </a>

        </div>
        <div class="sermon-filters">

    <a
        href="sermons.php"
        class="<?php echo ($category === 'all') ? 'filter-active' : ''; ?>"
    >
        All Sermons
    </a>

    <a
        href="sermons.php?category=Church"
        class="<?php echo ($category === 'Church') ? 'filter-active' : ''; ?>"
    >
        Church
    </a>

    <a
        href="sermons.php?category=Seminary%20%2F%20School"
        class="<?php echo ($category === 'Seminary / School') ? 'filter-active' : ''; ?>"
    >
        Seminary / School
    </a>

</div>


        <!-- SUCCESS -->

        <?php if (isset($_GET['deleted'])): ?>

            <div class="alert">
                Sermon deleted successfully.
            </div>

        <?php endif; ?>


        <!-- TABLE -->

        <div class="table-card">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Thumbnail</th>

                            <th>Title</th>

                            <th>Minister</th>

                            <th>Date</th>

                            <th>Category</th>

                            <th>Media</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <!-- THUMBNAIL -->

                                <td>

                                    <?php if (!empty($row['thumbnail'])): ?>

                                        <img
                                            src="../assets/uploads/sermons/<?php echo htmlspecialchars($row['thumbnail']); ?>"
                                            alt="<?php echo htmlspecialchars($row['title']); ?>"
                                            class="sermon-thumbnail"
                                        >

                                    <?php else: ?>

                                        <div class="no-thumbnail">
                                            No Image
                                        </div>

                                    <?php endif; ?>

                                </td>


                                <!-- TITLE -->

                                <td>

                                    <div class="sermon-title">

                                        <?php
                                        echo htmlspecialchars(
                                            $row['title']
                                        );
                                        ?>

                                    </div>

                                </td>


                                <!-- MINISTER -->

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['minister']
                                    );
                                    ?>

                                </td>


                                <!-- DATE -->

                                <td>

                                    <?php
                                    echo date(
                                        'M d, Y',
                                        strtotime(
                                            $row['sermon_date']
                                        )
                                    );
                                    ?>

                                </td>


                                <!-- CATEGORY -->

                                <td>

                                    <span class="category">

                                        <?php
                                        echo htmlspecialchars(
                                            $row['category']
                                        );
                                        ?>

                                    </span>

                                </td>


                                <!-- MEDIA -->

                                <td>

                                    <?php if (!empty($row['youtube_url'])): ?>

                                        <a
                                            href="<?php echo htmlspecialchars($row['youtube_url']); ?>"
                                            target="_blank"
                                            class="video-link"
                                        >
                                            ▶ YouTube
                                        </a>

                                    <?php endif; ?>


                                    <?php if (!empty($row['audio_url'])): ?>

                                        <a
                                            href="<?php echo htmlspecialchars($row['audio_url']); ?>"
                                            target="_blank"
                                            class="audio-link"
                                            style="margin-left:8px;"
                                        >
                                            🎧 Audio
                                        </a>

                                    <?php endif; ?>


                                    <?php if (
                                        empty($row['youtube_url']) &&
                                        empty($row['audio_url'])
                                    ): ?>

                                        <span>
                                            —
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <div class="actions">

                                        <a
                                            href="edit-sermon.php?id=<?php echo $row['id']; ?>"
                                            class="action-button edit-button"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="sermons.php?delete=<?php echo $row['id']; ?>"
                                            class="action-button delete-button"
                                            onclick="return confirm('Are you sure you want to delete this sermon?');"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty-state">

                    <h3>
                        No Sermons Yet
                    </h3>

                    <p>
                        Start by adding your first sermon.
                    </p>

                </div>

            <?php endif; ?>

        </div>


    </section>

</main>


</body>

</html>