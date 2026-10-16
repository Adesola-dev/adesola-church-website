<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$applications_count = 0;
$pending_applications_count = 0;

$messages_count = 0;
$unread_messages_count = 0;

$appointments_count = 0;
$pending_appointments_count = 0;

$gallery_count = 0;
$events_count = 0;
$sermons_count = 0;
$leadership_count = 0;

/* Applications */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM applications");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $applications_count = $row['total'];
}
/* Pending Applications */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM applications
     WHERE status = 'Pending'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pending_applications_count = $row['total'];
}

/* Messages */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $messages_count = $row['total'];
}
/* Unread Messages */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM messages
     WHERE status = 'unread'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $unread_messages_count = $row['total'];
}
/* Appointments */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $appointments_count = $row['total'];
}

/* Pending Appointments */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM appointments
     WHERE status = 'pending'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pending_appointments_count = $row['total'];
}
/* Gallery */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM gallery");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $gallery_count = $row['total'];
}

/* Events */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $events_count = $row['total'];
}

/* Sermons */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM sermons");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $sermons_count = $row['total'];
}

/* Leadership */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM leadership");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $leadership_count = $row['total'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Olusegun Theological Seminary</title>

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

        .welcome {
            background: linear-gradient(135deg, #171A63, #252A82);
            color: #fff;
            padding: 28px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .welcome h2 {
            margin-bottom: 8px;
        }

        .welcome p {
            opacity: 0.85;
            font-size: 14px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        }

        .card-icon {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .card h3 {
            font-size: 14px;
            color: #666;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .card-number {
            font-size: 30px;
            font-weight: 700;
            color: #171A63;
        }
        .card-detail {
    margin: 8px 0 10px;
    font-size: 12px;
    font-weight: 600;
    color: #D9A400;
}

.card-link {
    display: inline-block;
    margin-top: 5px;
    color: #171A63;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

.card-link:hover {
    color: #D9A400;
}

        .quick-actions {
            margin-top: 35px;
        }

        .quick-actions h2 {
            color: #171A63;
            margin-bottom: 18px;
            font-size: 20px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .action {
            background: #fff;
            border: 1px solid #e5e5e5;
            padding: 18px;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .action:hover {
            border-color: #D9A400;
            color: #171A63;
        }

        @media (max-width: 1000px) {

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-grid {
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

            .cards,
            .action-grid {
                grid-template-columns: 1fr;
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

<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="brand">

        <img src="../assets/images/logo.png"
             alt="Olusegun Theological Seminary">

        <h2>Olusegun Theological Seminary</h2>

        <p>ADMINISTRATION PANEL</p>

    </div>

    <nav class="menu">

        <a href="index.php" class="active">
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

        <a href="sermons.php">
            🎥 Sermons
        </a>

        <a href="leadership.php">
            👥 Leadership
        </a>
        <a href="appointments.php">
    📆 Appointments
</a>

    </nav>

    <div class="logout">

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</aside>


<!-- MAIN CONTENT -->

<main class="main">

    <header class="topbar">

        <h1>Dashboard</h1>

        <div class="admin-name">

            Welcome,
            <strong>
                <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
            </strong>

        </div>

    </header>


    <section class="content">

        <div class="welcome">

            <h2>Welcome to the OTS Administration Panel</h2>

            <p>
                Manage applications, messages, gallery, events,
                sermons and leadership information from one place.
            </p>

        </div>


        <!-- STATISTICS -->

        <div class="cards">

            <div class="card">

                <div class="card-icon">📋</div>

                <h3>Applications</h3>

                <div class="card-number">
                    <?php echo $applications_count; ?>
                </div>

            </div>


            <div class="card">

    <div class="card-icon">✉️</div>

    <h3>Messages</h3>

    <div class="card-number">
        <?php echo $messages_count; ?>
    </div>

    <p class="card-detail">
        <?php echo $unread_messages_count; ?> Unread
    </p>

    <a href="messages.php" class="card-link">
        View Messages →
    </a>

</div>


            <div class="card">

                <div class="card-icon">🖼️</div>

                <h3>Gallery Items</h3>

                <div class="card-number">
                    <?php echo $gallery_count; ?>
                </div>

            </div>


            <div class="card">

                <div class="card-icon">📅</div>

                <h3>Events</h3>

                <div class="card-number">
                    <?php echo $events_count; ?>
                </div>

            </div>


            <div class="card">

                <div class="card-icon">🎥</div>

                <h3>Sermons</h3>

                <div class="card-number">
                    <?php echo $sermons_count; ?>
                </div>

            </div>


          <div class="card">

    <div class="card-icon">📆</div>

    <h3>Appointments</h3>

    <div class="card-number">
        <?php echo $appointments_count; ?>
    </div>

    <p class="card-detail">
        <?php echo $pending_appointments_count; ?> Pending
    </p>

    <a href="appointments.php" class="card-link">
        View Appointments →
    </a>

</div>

        </div>


     <!-- QUICK ACTIONS -->

<div class="quick-actions">

    <h2>Quick Actions</h2>

    <div class="action-grid">

        <a href="applications.php" class="action">
            📋 View Applications
        </a>

        <a href="appointments.php" class="action">
            📆 View Appointments
        </a>

        <a href="messages.php" class="action">
            ✉️ View Messages
        </a>

        <a href="add-gallery.php" class="action">
            🖼️ Add Gallery Image
        </a>

        <a href="add-event.php" class="action">
            📅 Add Event
        </a>

        <a href="add-sermon.php" class="action">
            🎥 Add Sermon
        </a>

        <a href="add-leader.php" class="action">
            👥 Add Leader
        </a>

    </div>

</div>