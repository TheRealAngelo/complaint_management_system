<?php
session_start();

// Check user is Admin role
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../login/logout.php");
    exit;
}
?>