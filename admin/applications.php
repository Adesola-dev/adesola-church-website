<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$sql = "SELECT * FROM applications ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Applications | OTS Admin</title>

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
            color: white;
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
            color: white;
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
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 7px;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
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

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            color: #171A63;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #666;
            font-size: 14px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #171A63;
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 13px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eeeeee;
            font-size: 14px;
        }

        tr:hover {
            background: #fafafd;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .approved {
            background: #d4edda;
            color: #155724;
        }

        .rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .view-btn {
            display: inline-block;
            background: #171A63;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
        }

        .view-btn:hover {
            background: #252A82;
        }

        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #777;
        }

        .empty-icon {
            font-size: 45px;
            margin-bottom: 15px;
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

        <img
            src="../assets/images/logo.png"
            alt="Olusegun Theological Seminary"
        >

        <h2>Olusegun Theological Seminary</h2>

        <p>ADMINISTRATION PANEL</p>

    </div>

    <nav class="menu">

        <a href="index.php">
            🏠 Dashboard
        </a>

        <a href="applications.php" class="active">
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

        <h1>Applications</h1>

        <div class="admin-name">

            Welcome,
            <strong>
                <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
            </strong>

        </div>

    </header>


    <section class="content">

        <div class="page-header">

            <h2>Student Applications</h2>

            <p>
                Review and manage applications submitted through the OTS website.
            </p>

        </div>


        <div class="table-container">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Qualification</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($application = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $application['id']; ?>
                            </td>

                            <td>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                   $application['full_name']
                                    );
                                    ?>
                                </strong>

                            </td>

                            <td>
                                <?php echo htmlspecialchars($application['email']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($application['phone']); ?>
                            </td>

                            <td>
                               <?php echo htmlspecialchars($application['highest_qualification']); ?>
                            </td>

                            <td>

                                <?php

                                $status = strtolower($application['status']);

                                ?>

                                <span class="status <?php echo $status; ?>">

                                    <?php
                                    echo htmlspecialchars($application['status']);
                                    ?>

                                </span>

                            </td>

                            <td>

                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime($application['created_at'])
                                );
                                ?>

                            </td>

                            <td>

                                <a
                                    href="application-view.php?id=<?php echo $application['id']; ?>"
                                    class="view-btn"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty">

                    <div class="empty-icon">
                        📋
                    </div>

                    <h3>No Applications Yet</h3>

                    <p>
                        Applications submitted through the website
                        will appear here.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

</main>

</body>

</html>