<?php
session_start();
require '../../connection/connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>BCS</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>

<div class="login-container">
    <form method="POST" action="authentication.php">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label>Username:</label>
        <input type="text" placeholder="Username" name="username" required>
        <label>Password:</label>
        <input type="password" placeholder="Password" name="password" required>
        <button type="submit">Login</button>
        <button type="button" onclick="window.location.href='createaccount.php'">Create Account</button>
    </form>
</div>

<?php if (isset($_SESSION["error"])): ?>
<div class="error-popup" id="errorPopup">
    <?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?>
</div>
<script>
    // Show the error pop-up
    document.getElementById("errorPopup").style.display = "block";

    // Automatically hide the pop-up after 5 seconds
    setTimeout(() => {
        document.getElementById("errorPopup").style.display = "none";
    }, 5000);
</script>

<?php endif; ?>

</body>
</html>