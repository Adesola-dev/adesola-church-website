<?php
include '../includes/db.php';


// ===============================
// DASHBOARD COUNTS
// ===============================

// Total Events
$events_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM events"
);

$events_data = mysqli_fetch_assoc($events_query);
$total_events = $events_data['total'];


// Upcoming Church Events
$upcoming_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM events
     WHERE category = 'Church'
     AND event_date >= CURDATE()"
);

$upcoming_data = mysqli_fetch_assoc($upcoming_query);
$upcoming_events = $upcoming_data['total'];


// Total Appointments
$appointments_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM appointments"
);

$appointments_data = mysqli_fetch_assoc($appointments_query);
$total_appointments = $appointments_data['total'];


// Pending Appointments
$pending_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM appointments
     WHERE status = 'pending'"
);

$pending_data = mysqli_fetch_assoc($pending_query);
$pending_appointments = $pending_data['total'];


// Total Messages
$messages_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM messages"
);

$messages_data = mysqli_fetch_assoc($messages_query);
$total_messages = $messages_data['total'];


// New Messages
$new_messages_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM messages
     WHERE status = 'new'"
);

$new_messages_data = mysqli_fetch_assoc($new_messages_query);
$new_messages = $new_messages_data['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard - Olusegun Church</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f6fc;
    color: #333;
}


/* ===============================
   SIDEBAR
================================ */

.sidebar {

    position: fixed;

    left: 0;
    top: 0;

    width: 250px;
    height: 100vh;

    background: #171A63;

    color: white;

    padding: 25px 15px;

}

.sidebar h2 {

    text-align: center;

    margin-bottom: 35px;

    font-size: 21px;

}

.sidebar a {

    display: block;

    color: white;

    text-decoration: none;

    padding: 13px 15px;

    margin-bottom: 6px;

    border-radius: 5px;

}

.sidebar a:hover {

    background: #252A82;

}

.sidebar a.active {

    background: #D9A400;

    color: #111;

    font-weight: bold;

}


/* ===============================
   MAIN CONTENT
================================ */

.main {

    margin-left: 250px;

    padding: 35px;

}

.topbar {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 35px;

}

.topbar h1 {

    margin: 0;

    color: #171A63;

}

.topbar p {

    margin: 5px 0 0;

    color: #666;

}


/* ===============================
   STAT CARDS
================================ */

.stats {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;

    margin-bottom: 35px;

}

.stat-card {

    background: white;

    padding: 25px;

    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,0.06);

}

.stat-card h3 {

    margin: 0 0 10px;

    font-size: 15px;

    color: #666;

}

.stat-card .number {

    font-size: 34px;

    font-weight: bold;

    color: #171A63;

}

.stat-card small {

    display: block;

    margin-top: 8px;

    color: #888;

}


/* ===============================
   SECTIONS
================================ */

.dashboard-grid {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 25px;

}

.dashboard-card {

    background: white;

    padding: 30px;

    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,0.06);

}

.dashboard-card h2 {

    margin-top: 0;

    color: #171A63;

    font-size: 22px;

}

.dashboard-card p {

    color: #666;

    line-height: 1.7;

}


/* ===============================
   QUICK ACTIONS
================================ */

.quick-actions {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 12px;

    margin-top: 20px;

}

.quick-actions a {

    display: block;

    padding: 14px;

    background: #f5f6fc;

    color: #171A63;

    text-decoration: none;

    border-radius: 5px;

    font-weight: bold;

    text-align: center;

}

.quick-actions a:hover {

    background: #D9A400;

    color: #111;

}


/* ===============================
   BADGES
================================ */

.badge {

    display: inline-block;

    padding: 5px 10px;

    border-radius: 20px;

    background: #f3d36a;

    color: #171A63;

    font-size: 12px;

    font-weight: bold;

}


/* ===============================
   MOBILE
================================ */

@media (max-width: 900px) {

    .stats {

        grid-template-columns:
            repeat(2, 1fr);

    }

    .dashboard-grid {

        grid-template-columns: 1fr;

    }

}


@media (max-width: 600px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

    }

    .main {

        margin-left: 0;

        padding: 20px;

    }

    .stats {

        grid-template-columns: 1fr;

    }

    .topbar {

        display: block;

    }

    .quick-actions {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- ===============================
     SIDEBAR
================================ -->

<div class="sidebar">

    <h2>
        Olusegun Church
    </h2>


    <a
        href="dashboard.php"
        class="active"
    >
        🏠 Dashboard
    </a>


    <a href="events.php">
        📅 Events
    </a>


    <a href="add-event.php">
        ➕ Add Event
    </a>


    <a href="appointments.php">
        📋 Appointments
    </a>


    <a href="messages.php">
        💬 Messages
    </a>


    <a href="../church.php">
        🌐 View Church Website
    </a>


    <a href="logout.php">
        🚪 Logout
    </a>

</div>


<!-- ===============================
     MAIN
================================ -->

<div class="main">


    <!-- TOP BAR -->

    <div class="topbar">

        <div>

            <h1>
                Church Dashboard
            </h1>

            <p>
                Welcome to the Olusegun Church administration panel.
            </p>

        </div>

    </div>


    <!-- ===============================
         STATISTICS
    ================================= -->

    <div class="stats">


        <!-- EVENTS -->

        <div class="stat-card">

            <h3>
                Total Events
            </h3>

            <div class="number">
                <?php echo $total_events; ?>
            </div>

            <small>
                All events
            </small>

        </div>


        <!-- UPCOMING -->

        <div class="stat-card">

            <h3>
                Upcoming Church Events
            </h3>

            <div class="number">
                <?php echo $upcoming_events; ?>
            </div>

            <small>
                Future Church events
            </small>

        </div>


        <!-- APPOINTMENTS -->

        <div class="stat-card">

            <h3>
                Appointments
            </h3>

            <div class="number">
                <?php echo $total_appointments; ?>
            </div>

            <small>
                Total appointment requests
            </small>

        </div>


        <!-- MESSAGES -->

        <div class="stat-card">

            <h3>
                Messages
            </h3>

            <div class="number">
                <?php echo $total_messages; ?>
            </div>

            <small>
                Total enquiries
            </small>

        </div>

    </div>


    <!-- ===============================
         DASHBOARD CONTENT
    ================================= -->

    <div class="dashboard-grid">


        <!-- APPOINTMENTS -->

        <div class="dashboard-card">

            <h2>
                Appointments
            </h2>

            <p>
                You currently have
                <strong>
                    <?php echo $pending_appointments; ?>
                </strong>
                pending appointment request(s).
            </p>

            <span class="badge">
                Pending: <?php echo $pending_appointments; ?>
            </span>

            <div class="quick-actions">

                <a href="appointments.php">
                    View Appointments
                </a>

                <a href="appointment-view.php">
                    Appointment Details
                </a>

            </div>

        </div>


        <!-- MESSAGES -->

        <div class="dashboard-card">

            <h2>
                Messages
            </h2>

            <p>
                You have
                <strong>
                    <?php echo $new_messages; ?>
                </strong>
                new message(s) from visitors.
            </p>

            <span class="badge">
                New: <?php echo $new_messages; ?>
            </span>

            <div class="quick-actions">

                <a href="messages.php">
                    View Messages
                </a>

            </div>

        </div>


        <!-- EVENTS -->

        <div class="dashboard-card">

            <h2>
                Manage Church Events
            </h2>

            <p>
                Add, edit and manage upcoming Church events
                displayed on the Church website.
            </p>

            <div class="quick-actions">

                <a href="events.php">
                    Manage Events
                </a>

                <a href="add-event.php">
                    Add New Event
                </a>

            </div>

        </div>


        <!-- WEBSITE -->

        <div class="dashboard-card">

            <h2>
                Church Website
            </h2>

            <p>
                Visit the public Church website to check
                how your content appears to visitors.
            </p>

            <div class="quick-actions">

                <a href="../church.php">
                    View Website
                </a>

            </div>

        </div>

    </div>


</div>

</body>

</html>