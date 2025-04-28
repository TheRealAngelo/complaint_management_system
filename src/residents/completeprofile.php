<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complete Profile</title>
</head>
<body>
    <h2>Complete Your Profile</h2>
    <p>Please complete your profile to access your dashboard.</p>
    <form method="POST" action="saveprofile.php">
        <label>Last Name:</label>
        <input type="text" name="last_name" required>
        <label>First Name:</label>
        <input type="text" name="first_name" required>
        <label>Address:</label>
        <input type="text" name="address" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>