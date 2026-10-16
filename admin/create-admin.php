<?php

include '../includes/db.php';

$name = "OTS Administrator";
$username = "admin";
$password = "admin123";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (name, username, password)
        VALUES (?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $name,
    $username,
    $hashed_password
);

if (mysqli_stmt_execute($stmt)) {

    echo "<h2>Admin account created successfully.</h2>";
    echo "<p>Username: <strong>admin</strong></p>";
    echo "<p>Password: <strong>admin123</strong></p>";
    echo "<p>Please delete <strong>create-admin.php</strong> after this.</p>";

} else {

    echo "Error creating admin: " . mysqli_error($conn);

}

?>