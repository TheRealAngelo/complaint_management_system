<?php
session_start();
require '../../connection/connect.php';
require 'create.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
</head>
<body>
    <form method="POST">
        <label>Email:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>