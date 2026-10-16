<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: messages.php");
    exit;
}


/* =========================
   DELETE MESSAGE
========================= */

if (isset($_GET['delete'])) {

    $delete_id = intval($_GET['delete']);

    if ($delete_id > 0) {

        $sql = "DELETE FROM messages WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $delete_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }
    }

    header("Location: messages.php");
    exit;
}


/* =========================
   UPDATE MESSAGE STATUS
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = $_POST['status'] ?? 'unread';

    $allowed_statuses = ['unread', 'read'];

    if (in_array($status, $allowed_statuses)) {

        $sql = "UPDATE messages SET status = ? WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $status,
                $id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }
    }

    header("Location: message-view.php?id=" . $id);
    exit;
}


/* =========================
   GET MESSAGE
========================= */

$sql = "SELECT * FROM messages WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$message = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$message) {
    header("Location: messages.php");
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

<title>Message Details - OTS Admin</title>

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


/* =========================
   BACK LINK
========================= */

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
   MESSAGE CARD
========================= */

.message-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.message-header {
    padding: 22px;
    background: #171a63;
    color: white;
}

.message-header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.message-header h1 {
    margin: 0 0 8px;
    font-size: 20px;
}

.message-header p {
    margin: 0;
    font-size: 12px;
    opacity: 0.85;
}

.status-badge {
    background: white;
    color: #171a63;
    padding: 7px 14px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: bold;
    text-transform: capitalize;
    white-space: nowrap;
}


/* =========================
   MESSAGE BODY
========================= */

.message-body {
    padding: 25px;
}

.sender-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.info-label {
    display: block;
    text-transform: uppercase;
    color: #888;
    font-size: 9px;
    margin-bottom: 6px;
}

.info-value {
    font-size: 13px;
    color: #222;
    word-break: break-word;
}

.info-value a {
    color: #171a63;
    text-decoration: none;
}

.info-value a:hover {
    text-decoration: underline;
}

.message-content {
    border-top: 1px solid #eee;
    padding-top: 25px;
}

.message-content h3 {
    color: #171a63;
    font-size: 15px;
    margin: 0 0 15px;
}

.message-text {
    background: #f8f8fb;
    border: 1px solid #eee;
    border-radius: 6px;
    padding: 20px;
    line-height: 1.8;
    font-size: 14px;
    white-space: normal;
}


/* =========================
   ACTION AREA
========================= */

.action-area {
    padding: 20px 25px;
    border-top: 1px solid #eee;
    background: #fafafa;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.status-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-form label {
    font-size: 11px;
    font-weight: bold;
    color: #555;
}

.status-form select {
    padding: 9px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: white;
}

.update-btn {
    padding: 9px 14px;
    border: none;
    border-radius: 5px;
    background: #d9a400;
    color: #171a63;
    font-weight: bold;
    cursor: pointer;
}

.update-btn:hover {
    background: #b88600;
}

.delete-btn {
    display: inline-block;
    padding: 9px 14px;
    background: #fdecec;
    color: #a12626;
    border-radius: 5px;
    text-decoration: none;
    font-size: 11px;
    font-weight: bold;
}

.delete-btn:hover {
    background: #f5cccc;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 700px) {

    .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
    }

    .admin-wrapper {
        display: block;
    }

    .main {
        margin-left: 0;
        width: 100%;
    }

    .logout {
        position: static;
        margin-top: 20px;
    }

    .sender-grid {
        grid-template-columns: 1fr;
    }

    .message-header-top {
        flex-direction: column;
    }

    .action-area {
        flex-direction: column;
        align-items: stretch;
    }

    .status-form {
        flex-wrap: wrap;
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

            <a href="applications.php">
                📄 Applications
            </a>

            <a href="messages.php" class="active">
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
                Message Details
            </h2>

            <span>
                Welcome,
                <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
            </span>

        </div>


        <div class="content">


            <a
                href="messages.php"
                class="back-link"
            >
                ← Back to Messages
            </a>


            <div class="message-card">


                <!-- MESSAGE HEADER -->

                <div class="message-header">

                    <div class="message-header-top">

                        <div>

                            <h1>

                                <?php

                                echo !empty($message['subject'])
                                    ? safe($message['subject'])
                                    : 'No Subject';

                                ?>

                            </h1>


                            <p>

                                Message ID:
                                #<?php echo $message['id']; ?>

                                &nbsp; | &nbsp;

                                <?php

                                echo date(
                                    "d M Y, h:i A",
                                    strtotime($message['created_at'])
                                );

                                ?>

                            </p>

                        </div>


                        <div class="status-badge">

                            <?php echo safe($message['status']); ?>

                        </div>

                    </div>

                </div>


                <!-- MESSAGE BODY -->

                <div class="message-body">


                    <!-- SENDER INFORMATION -->

                    <div class="sender-grid">


                        <div>

                            <span class="info-label">
                                Name
                            </span>

                            <div class="info-value">
                                <?php echo safe($message['name']); ?>
                            </div>

                        </div>


                        <div>

                            <span class="info-label">
                                Email
                            </span>

                            <div class="info-value">

                                <a
                                    href="mailto:<?php echo htmlspecialchars($message['email']); ?>"
                                >
                                    <?php echo safe($message['email']); ?>
                                </a>

                            </div>

                        </div>


                        <div>

                            <span class="info-label">
                                Phone
                            </span>

                            <div class="info-value">

                                <?php if (!empty($message['phone'])): ?>

                                    <a
                                        href="tel:<?php echo htmlspecialchars($message['phone']); ?>"
                                    >
                                        <?php echo safe($message['phone']); ?>
                                    </a>

                                <?php else: ?>

                                    Not provided

                                <?php endif; ?>

                            </div>

                        </div>


                        <div>

                            <span class="info-label">
                                Date Received
                            </span>

                            <div class="info-value">

                                <?php

                                echo date(
                                    "d F Y",
                                    strtotime($message['created_at'])
                                );

                                ?>

                            </div>

                        </div>

                    </div>


                    <!-- MESSAGE -->

                    <div class="message-content">

                        <h3>
                            Message
                        </h3>

                        <div class="message-text">

                            <?php

                            echo nl2br(
                                safe($message['message'])
                            );

                            ?>

                        </div>

                    </div>


                </div>


                <!-- ACTION AREA -->

                <div class="action-area">


                    <form
                        method="POST"
                        class="status-form"
                    >

                        <label for="status">
                            Status:
                        </label>


                        <select
                            name="status"
                            id="status"
                        >

                            <option
                                value="unread"
                                <?php echo ($message['status'] === 'unread') ? 'selected' : ''; ?>
                            >
                                Unread
                            </option>

                            <option
                                value="read"
                                <?php echo ($message['status'] === 'read') ? 'selected' : ''; ?>
                            >
                                Read
                            </option>

                        </select>


                        <button
                            type="submit"
                            class="update-btn"
                        >
                            Update Status
                        </button>

                    </form>


                    <a
                        href="message-view.php?id=<?php echo $message['id']; ?>&delete=1"
                        class="delete-btn"
                        onclick="return confirm('Are you sure you want to delete this message?');"
                    >
                        🗑 Delete Message
                    </a>

                </div>


            </div>

        </div>

    </main>

</div>

</body>

</html>