<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: applications.php");
    exit;
}

/* =========================
   UPDATE APPLICATION STATUS
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = $_POST['status'] ?? 'Pending';

    $allowed_statuses = ['Pending', 'Approved', 'Rejected'];

    if (in_array($status, $allowed_statuses)) {

        $update_sql = "UPDATE applications SET status = ? WHERE id = ?";

        $update_stmt = mysqli_prepare($conn, $update_sql);

        if ($update_stmt) {

            mysqli_stmt_bind_param(
                $update_stmt,
                "si",
                $status,
                $id
            );

            mysqli_stmt_execute($update_stmt);

            mysqli_stmt_close($update_stmt);
        }
    }

    header("Location: application-view.php?id=" . $id);
    exit;
}


/* =========================
   GET APPLICATION
========================= */

$sql = "SELECT * FROM applications WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$application = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$application) {
    header("Location: applications.php");
    exit;
}


/* =========================
   HELPER
========================= */

function safe($value)
{
    if ($value === null || $value === '') {
        return 'Not provided';
    }

    return htmlspecialchars($value);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Application Details - OTS Admin</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f5fa;
    color: #333;
}

.admin-wrapper {
    display: flex;
    min-height: 100vh;
}

/* =========================
   SIDEBAR
========================= */

.sidebar {
    width: 240px;
    background: #171a63;
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    padding: 25px 15px;
}

.sidebar-logo {
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    padding-bottom: 18px;
    margin-bottom: 20px;
}

.sidebar-logo img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    background: white;
    margin-bottom: 10px;
}

.sidebar-logo h3 {
    font-size: 14px;
    margin: 5px 0;
}

.sidebar-logo p {
    font-size: 9px;
    opacity: 0.7;
    margin: 0;
}

.sidebar-menu a {
    display: block;
    text-decoration: none;
    color: white;
    padding: 12px 10px;
    margin-bottom: 5px;
    border-radius: 5px;
    font-size: 13px;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: #d9a400;
    color: #171a63;
}

.logout {
    position: absolute;
    bottom: 20px;
    left: 15px;
    right: 15px;
}

.logout a {
    display: block;
    text-align: center;
    background: rgba(255,255,255,0.12);
    color: white;
    text-decoration: none;
    padding: 10px;
    border-radius: 5px;
    font-size: 13px;
}

/* =========================
   MAIN
========================= */

.main {
    margin-left: 240px;
    width: calc(100% - 240px);
}

.topbar {
    height: 65px;
    background: white;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 25px;
}

.topbar h2 {
    margin: 0;
    font-size: 18px;
    color: #171a63;
}

.topbar span {
    font-size: 11px;
    color: #666;
}

.content {
    padding: 25px;
}

.back-link {
    display: inline-block;
    color: #171a63;
    text-decoration: none;
    font-size: 13px;
    margin-bottom: 15px;
}

.back-link:hover {
    text-decoration: underline;
}

/* =========================
   HEADER CARD
========================= */

.application-header {
    background: #171a63;
    color: white;
    border-radius: 8px;
    padding: 22px;
    margin-bottom: 20px;
}

.application-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.application-header h1 {
    margin: 0 0 7px;
    font-size: 20px;
}

.application-header p {
    margin: 0;
    font-size: 12px;
    opacity: 0.85;
}

.status-badge {
    background: white;
    color: #171a63;
    padding: 7px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    white-space: nowrap;
}

/* =========================
   GRID
========================= */

.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 15px;
}

.card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 15px;
}

.card h3 {
    color: #171a63;
    font-size: 15px;
    margin: 0 0 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e5e5;
}

/* =========================
   INFORMATION GRID
========================= */

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    margin-bottom: 5px;
}

.info-item.full {
    grid-column: 1 / -1;
}

.info-label {
    display: block;
    text-transform: uppercase;
    font-size: 9px;
    color: #888;
    margin-bottom: 5px;
}

.info-value {
    font-size: 13px;
    color: #222;
    word-break: break-word;
}

/* =========================
   STATUS FORM
========================= */

.status-form label {
    display: block;
    font-size: 11px;
    font-weight: bold;
    margin-bottom: 7px;
    color: #555;
}

.status-form select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: white;
}

.status-form button {
    width: 100%;
    margin-top: 12px;
    padding: 11px;
    border: none;
    border-radius: 5px;
    background: #d9a400;
    color: #171a63;
    font-weight: bold;
    cursor: pointer;
}

.status-form button:hover {
    background: #b88600;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 900px) {

    .sidebar {
        width: 200px;
    }

    .main {
        margin-left: 200px;
        width: calc(100% - 200px);
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 700px) {

    .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
    }

    .logout {
        position: static;
        margin-top: 20px;
    }

    .main {
        margin-left: 0;
        width: 100%;
    }

    .admin-wrapper {
        display: block;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .info-item.full {
        grid-column: auto;
    }

    .application-header-top {
        flex-direction: column;
        align-items: flex-start;
    }

    .content {
        padding: 15px;
    }

}

</style>

</head>

<body>

<div class="admin-wrapper">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            <img
                src="../assets/images/logo.png"
                alt="OTS Logo"
            >

            <h3>
                Olusegun Theological Seminary
            </h3>

            <p>
                ADMINISTRATION PANEL
            </p>

        </div>


        <div class="sidebar-menu">

            <a href="index.php">
                🏠 Dashboard
            </a>

            <a href="applications.php" class="active">
                📄 Applications
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

        </div>


        <div class="logout">

            <a href="logout.php">
                🚪 Logout
            </a>

        </div>

    </aside>


    <!-- MAIN -->

    <main class="main">

        <div class="topbar">

            <h2>
                Application Details
            </h2>

            <span>
                Welcome, <?php echo safe($_SESSION['admin_name']); ?>
            </span>

        </div>


        <div class="content">

            <a
                href="applications.php"
                class="back-link"
            >
                ← Back to Applications
            </a>


            <!-- APPLICATION HEADER -->

            <div class="application-header">

                <div class="application-header-top">

                    <div>

                        <h1>
                            <?php echo safe($application['full_name']); ?>
                        </h1>

                        <p>
                            Application ID: #<?php echo $application['id']; ?>
                            &nbsp; | &nbsp;
                            Submitted:
                            <?php echo date("d M Y", strtotime($application['created_at'])); ?>
                        </p>

                    </div>


                    <div class="status-badge">

                        <?php echo safe($application['status']); ?>

                    </div>

                </div>

            </div>


            <div class="dashboard-grid">

                <!-- LEFT COLUMN -->

                <div>


                    <!-- PERSONAL INFORMATION -->

                    <div class="card">

                        <h3>
                            Personal Information
                        </h3>

                        <div class="info-grid">

                            <div class="info-item full">

                                <span class="info-label">
                                    Full Name
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['full_name']); ?>
                                </div>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Gender
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['gender']); ?>
                                </div>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Date of Birth
                                </span>

                                <div class="info-value">

                                    <?php

                                    if (!empty($application['date_of_birth'])) {

                                        echo date(
                                            "d F Y",
                                            strtotime($application['date_of_birth'])
                                        );

                                    } else {

                                        echo "Not provided";

                                    }

                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- CONTACT INFORMATION -->

                    <div class="card">

                        <h3>
                            Contact Information
                        </h3>

                        <div class="info-grid">

                            <div class="info-item">

                                <span class="info-label">
                                    Email
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['email']); ?>
                                </div>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Phone
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['phone']); ?>
                                </div>

                            </div>


                            <div class="info-item full">

                                <span class="info-label">
                                    Address
                                </span>

                                <div class="info-value">
                                    <?php echo nl2br(safe($application['address'])); ?>
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- EDUCATION -->

                    <div class="card">

                        <h3>
                            Educational Information
                        </h3>

                        <div class="info-grid">

                            <div class="info-item">

                                <span class="info-label">
                                    Highest Qualification
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['highest_qualification']); ?>
                                </div>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Institution
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['institution']); ?>
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- CHURCH / MINISTRY -->

                    <div class="card">

                        <h3>
                            Church & Ministry Information
                        </h3>

                        <div class="info-grid">

                            <div class="info-item">

                                <span class="info-label">
                                    Church
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['church_name']); ?>
                                </div>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Ministry Role
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['ministry_role']); ?>
                                </div>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Years in Ministry
                                </span>

                                <div class="info-value">
                                    <?php echo safe($application['years_in_ministry']); ?>
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- APPLICATION STATEMENT -->

                    <div class="card">

                        <h3>
                            Application Statement
                        </h3>

                        <div class="info-value" style="line-height: 1.7;">

                            <?php echo nl2br(safe($application['reason'])); ?>

                        </div>

                    </div>


                </div>


                <!-- RIGHT COLUMN -->

                <div>


                    <!-- STATUS -->

                    <div class="card">

                        <h3>
                            Application Status
                        </h3>

                        <form
                            method="POST"
                            class="status-form"
                        >

                            <label for="status">
                                Change Status
                            </label>

                            <select
                                name="status"
                                id="status"
                            >

                                <option
                                    value="Pending"
                                    <?php echo ($application['status'] === 'Pending') ? 'selected' : ''; ?>
                                >
                                    Pending
                                </option>

                                <option
                                    value="Approved"
                                    <?php echo ($application['status'] === 'Approved') ? 'selected' : ''; ?>
                                >
                                    Approved
                                </option>

                                <option
                                    value="Rejected"
                                    <?php echo ($application['status'] === 'Rejected') ? 'selected' : ''; ?>
                                >
                                    Rejected
                                </option>

                            </select>


                            <button type="submit">
                                Update Status
                            </button>

                        </form>

                    </div>


                    <!-- SUBMISSION INFORMATION -->

                    <div class="card">

                        <h3>
                            Submission Information
                        </h3>

                        <div class="info-item">

                            <span class="info-label">
                                Application ID
                            </span>

                            <div class="info-value">
                                #<?php echo $application['id']; ?>
                            </div>

                        </div>


                        <div class="info-item" style="margin-top: 20px;">

                            <span class="info-label">
                                Date Submitted
                            </span>

                            <div class="info-value">

                                <?php

                                echo date(
                                    "d F Y",
                                    strtotime($application['created_at'])
                                );

                                ?>

                            </div>

                        </div>


                        <div class="info-item" style="margin-top: 20px;">

                            <span class="info-label">
                                Time Submitted
                            </span>

                            <div class="info-value">

                                <?php

                                echo date(
                                    "h:i A",
                                    strtotime($application['created_at'])
                                );

                                ?>

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>

    </main>

</div>

</body>

</html>