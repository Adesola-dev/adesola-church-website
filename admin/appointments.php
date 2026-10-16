<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';


// =========================================================
// UPDATE APPOINTMENT STATUS
// =========================================================

if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = intval($_GET['id']);
    $status = $_GET['status'];

    $allowed_statuses = [
        'pending',
        'confirmed',
        'completed',
        'cancelled'
    ];

    if (in_array($status, $allowed_statuses)) {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE appointments SET status = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $status,
            $id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }

    header("Location: appointments.php");
    exit();
}


// =========================================================
// GET APPOINTMENTS
// =========================================================

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM appointments
     ORDER BY appointment_date ASC, appointment_time ASC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Appointments | OTS Admin</title>

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


        /* =========================
           LOGOUT
        ========================= */

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


        /* =========================
           TOPBAR
        ========================= */

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


        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 30px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            color: #171A63;
            font-size: 25px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #666;
            font-size: 14px;
        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th {
            background: #171A63;
            color: #fff;
            text-align: left;
            padding: 15px;
            font-size: 13px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #fafafa;
        }


        /* =========================
           STATUS
        ========================= */

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fff4cc;
            color: #8a6500;
        }

        .status-confirmed {
            background: #dff5e5;
            color: #16743a;
        }

        .status-completed {
            background: #dcecff;
            color: #155a9c;
        }

        .status-cancelled {
            background: #ffe0e0;
            color: #a12626;
        }


        /* =========================
           ACTION BUTTONS
        ========================= */

        .actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
        }

        .btn-view {
            background: #171A63;
            color: #fff;
        }

        .btn-confirm {
            background: #D9A400;
            color: #fff;
        }

        .btn-complete {
            background: #198754;
            color: #fff;
        }

        .btn-cancel {
            background: #dc3545;
            color: #fff;
        }

        .btn:hover {
            opacity: 0.85;
        }


        /* =========================
           EMPTY STATE
        ========================= */

        .empty {
            padding: 50px;
            text-align: center;
            color: #777;
        }


        /* =========================
           MOBILE
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
                gap: 10px;
                flex-direction: column;
                align-items: flex-start;
            }

            .content {
                padding: 18px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

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

        <a href="sermons.php">
            🎥 Sermons
        </a>

        <a href="leadership.php">
            👥 Leadership
        </a>

        <a href="appointments.php" class="active">
            📆 Appointments
        </a>

    </nav>


    <div class="logout">

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</aside>



<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<main class="main">


    <header class="topbar">

        <h1>
            Appointments
        </h1>

        <div class="admin-name">

            Welcome,

            <strong>
                <?php
                echo htmlspecialchars($_SESSION['admin_name']);
                ?>
            </strong>

        </div>

    </header>



    <section class="content">


        <div class="page-header">

            <h2>
                Appointment Requests
            </h2>

            <p>
                Manage church and ministry appointment requests.
            </p>

        </div>



        <!-- =================================================
             APPOINTMENTS TABLE
        ================================================== -->

        <div class="table-wrapper">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>Appointment</th>

                            <th>Date</th>

                            <th>Time</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php
                    $number = 1;

                    while ($appointment = mysqli_fetch_assoc($result)):
                    ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <strong>
                                    <?php
                                    echo htmlspecialchars($appointment['name']);
                                    ?>
                                </strong>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars($appointment['email']);
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars($appointment['phone']);
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars($appointment['appointment_type']);
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime($appointment['appointment_date'])
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "h:i A",
                                    strtotime($appointment['appointment_time'])
                                );
                                ?>
                            </td>

                            <td>

                                <span class="status status-<?php echo strtolower($appointment['status']); ?>">

                                    <?php
                                    echo htmlspecialchars($appointment['status']);
                                    ?>

                                </span>

                            </td>


                            <td>

                                <div class="actions">

                                    <a
                                        href="appointment-view.php?id=<?php echo $appointment['id']; ?>"
                                        class="btn btn-view"
                                    >
                                        View
                                    </a>


                                    <?php if ($appointment['status'] === 'pending'): ?>

                                        <a
                                            href="appointments.php?id=<?php echo $appointment['id']; ?>&status=confirmed"
                                            class="btn btn-confirm"
                                        >
                                            Confirm
                                        </a>

                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>

                                        <a
                                            href="appointments.php?id=<?php echo $appointment['id']; ?>&status=completed"
                                            class="btn btn-complete"
                                        >
                                            Complete
                                        </a>

                                    <?php endif; ?>


                                    <?php if ($appointment['status'] !== 'cancelled' && $appointment['status'] !== 'completed'): ?>

                                        <a
                                            href="appointments.php?id=<?php echo $appointment['id']; ?>&status=cancelled"
                                            class="btn btn-cancel"
                                            onclick="return confirm('Are you sure you want to cancel this appointment?');"
                                        >
                                            Cancel
                                        </a>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty">

                    <h3>
                        No Appointments Found
                    </h3>

                    <p>
                        There are currently no appointment requests.
                    </p>

                </div>

            <?php endif; ?>

        </div>


    </section>

</main>


</body>

</html>