<?php

session_start();

include '../includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {

        $error = "Please enter your username and password.";

    } else {

        $sql = "SELECT id, name, username, password
                FROM admins
                WHERE username = ?
                LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $username);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {

            $admin = mysqli_fetch_assoc($result);

            if (password_verify($password, $admin['password'])) {

                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_username'] = $admin['username'];

                header("Location: index.php");
                exit();

            } else {

                $error = "Invalid username or password.";

            }

        } else {

            $error = "Invalid username or password.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Olusegun Theological Seminary</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background:
                linear-gradient(
                    rgba(23, 26, 99, 0.92),
                    rgba(15, 18, 74, 0.96)
                ),
                url("../assets/images/ministry.jpg") center/cover no-repeat;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 430px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo img {
            width: 85px;
            height: 85px;
            object-fit: contain;
        }

        .login-title {
            text-align: center;
            color: #171A63;
            font-size: 25px;
            margin-bottom: 8px;
        }

        .login-subtitle {
            text-align: center;
            color: #666666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #171A63;
            box-shadow: 0 0 0 3px rgba(23, 26, 99, 0.08);
        }

        .login-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #D9A400;
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-button:hover {
            background: #B88600;
        }

        .error-message {
            background: #ffe8e8;
            color: #b00020;
            border: 1px solid #f3b5b5;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .back-home {
            text-align: center;
            margin-top: 22px;
        }

        .back-home a {
            color: #171A63;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-home a:hover {
            text-decoration: underline;
        }

        .footer-text {
            text-align: center;
            color: #777777;
            font-size: 12px;
            margin-top: 25px;
        }

        @media (max-width: 480px) {

            .login-card {
                padding: 30px 22px;
            }

            .login-title {
                font-size: 22px;
            }

        }

    </style>

</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">
            <img
                src="../assets/images/logo.png"
                alt="Olusegun Theological Seminary"
            >
        </div>

        <h1 class="login-title">
            Admin Login
        </h1>

        <p class="login-subtitle">
            Olusegun Theological Seminary
        </p>

        <?php if (!empty($error)): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>

            <button
                type="submit"
                class="login-button"
            >
                Login to Dashboard
            </button>

        </form>

        <div class="back-home">

            <a href="../index.php">
                ← Back to Website
            </a>

        </div>

        <div class="footer-text">

            © <?php echo date("Y"); ?>
            Olusegun Theological Seminary

        </div>

    </div>

</div>

</body>

</html>