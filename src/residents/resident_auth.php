<?php
session_start();

// Check if user Resident role
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Resident") {
    header("Location: ../login/logout.php");
    exit;
}
?>