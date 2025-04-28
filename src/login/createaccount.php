<?php
session_start();
require '../../connection/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    if (empty($username) || empty($password)) {
        die("Please fill in all fields.");
    }

    $stmt = $conn->prepare("INSERT INTO pending_users (UserName, Password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "Account request submitted. Please wait for admin approval.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
</head>
<body>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>