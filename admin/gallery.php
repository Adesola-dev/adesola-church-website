<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';


/* =========================
   DELETE GALLERY IMAGE
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    /* Get image filename before deleting */
    $stmt = mysqli_prepare(
        $conn,
        "SELECT image FROM gallery WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        $image = $row['image'];

        /* Delete database record */
        $delete_stmt = mysqli_prepare(
            $conn,
            "DELETE FROM gallery WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $delete_stmt,
            "i",
            $id
        );

        mysqli_stmt_execute($delete_stmt);

        /* Delete image file */
        $image_path = "../assets/uploads/gallery/" . $image;

        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    header("Location: gallery.php");
    exit();
}


/* =========================
   GET GALLERY IMAGES
========================= */

$category = $_GET['category'] ?? 'all';

if ($category === 'Church') {

    $result = mysqli_query(
        $conn,
        "SELECT * FROM gallery
         WHERE category = 'Church'
         ORDER BY created_at DESC"
    );

} elseif ($category === 'Seminary / School') {

    $result = mysqli_query(
        $conn,
        "SELECT * FROM gallery
         WHERE category = 'Seminary / School'
         ORDER BY created_at DESC"
    );

} else {

    $result = mysqli_query(
        $conn,
        "SELECT * FROM gallery
         ORDER BY created_at DESC"
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Gallery | OTS Admin</title>

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
            background: #D9A400;
            color: #fff;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        .add-button:hover
        .gallery-filters {
    display: flex;
    gap: 8px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.gallery-filters a {
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

.gallery-filters a:hover {
    background: #e8e9f5;
}

.gallery-filters a.filter-active {
    background: #171A63;
    color: #fff;
    border-color: #171A63;
} {
            background: #B88600;
        }


        /* =========================
           GALLERY GRID
        ========================= */

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .gallery-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            overflow: hidden;
        }

        .gallery-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }

        .gallery-info {
            padding: 18px;
        }

        .gallery-info h3 {
            color: #171A63;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .category {
            display: inline-block;
            background: #f5f6fc;
            color: #171A63;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .date {
            color: #777;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .view-btn,
        .delete-btn {
            padding: 9px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .view-btn {
            background: #171A63;
            color: #fff;
        }

        .delete-btn {
            background: #f5eeee;
            color: #b00020;
        }

        .view-btn:hover {
            background: #252A82;
        }

        .delete-btn:hover {
            background: #f1dede;
        }


        /* =========================
           EMPTY STATE
        ========================= */

        .empty {
            background: #fff;
            padding: 50px;
            text-align: center;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
        }

        .empty-icon {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .empty h3 {
            color: #171A63;
            margin-bottom: 8px;
        }

        .empty p {
            color: #777;
            margin-bottom: 20px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

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

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .topbar {
                padding: 18px;
            }

            .content {
                padding: 18px;
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

        <img src="../assets/images/logo.png"
             alt="Olusegun Theological Seminary">

        <h2>Olusegun Theological Seminary</h2>

        <p>ADMINISTRATION PANEL</p>

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

        <h1>Gallery</h1>

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

                <h2>Gallery Management</h2>

                <p>
                    Manage photos displayed on the OTS website.
                </p>

            </div>


            <a href="add-gallery.php"
               class="add-button">

                + Add Gallery Image

            </a>
            <div class="gallery-filters">

    <a href="gallery.php"
       class="<?php echo ($category === 'all') ? 'filter-active' : ''; ?>">
        All Images
    </a>

    <a href="gallery.php?category=Church"
       class="<?php echo ($category === 'Church') ? 'filter-active' : ''; ?>">
        Church
    </a>

    <a href="gallery.php?category=Seminary%20%2F%20School"
       class="<?php echo ($category === 'Seminary / School') ? 'filter-active' : ''; ?>">
        Seminary / School
    </a>

</div>

        </div>


        <!-- =========================
             GALLERY
        ========================= -->

        <?php if (mysqli_num_rows($result) > 0): ?>

            <div class="gallery-grid">

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <div class="gallery-card">


                        <img
                            src="../assets/uploads/gallery/<?php echo htmlspecialchars($row['image']); ?>"
                            alt="<?php echo htmlspecialchars($row['title']); ?>"
                            class="gallery-image"
                        >


                        <div class="gallery-info">

                            <h3>
                                <?php
                                echo htmlspecialchars(
                                    $row['title']
                                );
                                ?>
                            </h3>


                            <?php if (!empty($row['category'])): ?>

                                <span class="category">

                                    <?php
                                    echo htmlspecialchars(
                                        $row['category']
                                    );
                                    ?>

                                </span>

                            <?php endif; ?>


                            <p class="date">

                                Added:
                                <?php
                                echo date(
                                    'M d, Y',
                                    strtotime($row['created_at'])
                                );
                                ?>

                            </p>


                            <div class="actions">

                                <a
                                    href="../assets/uploads/gallery/<?php echo htmlspecialchars($row['image']); ?>"
                                    target="_blank"
                                    class="view-btn"
                                >
                                    View
                                </a>


                                <a
                                    href="gallery.php?delete=<?php echo $row['id']; ?>"
                                    class="delete-btn"
                                    onclick="return confirm('Are you sure you want to delete this image?');"
                                >
                                    Delete
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>


        <?php else: ?>


            <div class="empty">

                <div class="empty-icon">
                    🖼️
                </div>

                <h3>No Gallery Images Yet</h3>

                <p>
                    You have not added any gallery images.
                </p>

                <a href="add-gallery.php"
                   class="add-button">

                    Add Your First Image

                </a>

            </div>


        <?php endif; ?>


    </section>

</main>


</body>

</html>