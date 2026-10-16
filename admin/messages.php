<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

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
   GET MESSAGES
========================= */

$sql = "SELECT * FROM messages ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Messages - OTS Admin</title>

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
   PAGE HEADER
========================= */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.page-header h1 {
    margin: 0;
    color: #171a63;
    font-size: 24px;
}

.message-count {
    background: #d9a400;
    color: #171a63;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

/* =========================
   TABLE
========================= */

.table-card {
    background: white;
    border-radius: 8px;
    border: 1px solid #ddd;
    overflow-x: auto;
}

.messages-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}

.messages-table th {
    background: #171a63;
    color: white;
    padding: 13px;
    text-align: left;
    font-size: 12px;
}

.messages-table td {
    padding: 13px;
    border-bottom: 1px solid #eee;
    font-size: 12px;
    vertical-align: middle;
}

.messages-table tr:hover {
    background: #fafafa;
}

.unread-row {
    background: #fffdf2;
    font-weight: 600;
}

.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 10px;
    font-weight: bold;
    text-transform: capitalize;
}

.status.unread {
    background: #fff1c7;
    color: #8a6500;
}

.status.read {
    background: #e8f7ee;
    color: #176b3a;
}

/* =========================
   ACTIONS
========================= */

.action-buttons {
    display: flex;
    gap: 7px;
}

.action-btn {
    display: inline-block;
    padding: 7px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 10px;
    font-weight: bold;
}

.view-btn {
    background: #171a63;
    color: white;
}

.view-btn:hover {
    background: #0f124a;
}

.delete-btn {
    background: #fdecec;
    color: #a12626;
}

.delete-btn:hover {
    background: #f5cccc;
}

/* =========================
   EMPTY STATE
========================= */

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon {
    font-size: 40px;
    margin-bottom: 15px;
}

.empty-state h3 {
    color: #171a63;
    margin-bottom: 8px;
}

.empty-state p {
    color: #777;
    font-size: 13px;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 800px) {

    .sidebar {
        width: 200px;
    }

    .main {
        margin-left: 200px;
        width: calc(100% - 200px);
    }

}

@media (max-width: 650px) {

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

    .page-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
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
                Messages
            </h2>

            <span>
                Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
            </span>

        </div>


        <div class="content">

            <div class="page-header">

                <h1>
                    Messages
                </h1>

                <div class="message-count">

                    <?php

                    $count_result = mysqli_query(
                        $conn,
                        "SELECT COUNT(*) AS total FROM messages"
                    );

                    $count_data = mysqli_fetch_assoc($count_result);

                    echo $count_data['total'];

                    ?>

                    Total Messages

                </div>

            </div>


            <div class="table-card">

                <?php if ($result && mysqli_num_rows($result) > 0): ?>

                    <table class="messages-table">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Name</th>

                                <th>Email</th>

                                <th>Subject</th>

                                <th>Status</th>

                                <th>Date</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                <tr
                                    class="<?php echo ($row['status'] === 'unread') ? 'unread-row' : ''; ?>"
                                >

                                    <td>
                                        #<?php echo $row['id']; ?>
                                    </td>


                                    <td>
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </td>


                                    <td>
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </td>


                                    <td>

                                        <?php

                                        echo !empty($row['subject'])
                                            ? htmlspecialchars($row['subject'])
                                            : 'No Subject';

                                        ?>

                                    </td>


                                    <td>

                                        <span
                                            class="status <?php echo htmlspecialchars($row['status']); ?>"
                                        >
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>

                                    </td>


                                    <td>

                                        <?php

                                        echo date(
                                            "d M Y",
                                            strtotime($row['created_at'])
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <div class="action-buttons">

                                            <a
                                                href="message-view.php?id=<?php echo $row['id']; ?>"
                                                class="action-btn view-btn"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="messages.php?delete=<?php echo $row['id']; ?>"
                                                class="action-btn delete-btn"
                                                onclick="return confirm('Are you sure you want to delete this message?');"
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

                        <div class="empty-state-icon">
                            ✉️
                        </div>

                        <h3>
                            No Messages Yet
                        </h3>

                        <p>
                            Messages submitted through the website
                            will appear here.
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </main>

</div>

</body>

</html>