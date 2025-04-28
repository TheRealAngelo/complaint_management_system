<?php
session_start();
require '../../connection/connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lastName = trim($_POST["last_name"]);
    $firstName = trim($_POST["first_name"]);
    $address = trim($_POST["address"]);
    $userID = $_SESSION["user_id"];

    if (empty($lastName) || empty($firstName) || empty($address)) {
        $_SESSION["error"] = "All fields are required.";
        header("Location: completeprofile.php");
        exit;
    }

    // Insert to database
    $stmt = $conn->prepare("INSERT INTO residents (LastName, FirstName, Address, UserID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $lastName, $firstName, $address, $userID);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Profile completed successfully.";
        header("Location: dashboard.php"); 
    } else {
        $_SESSION["error"] = "Error: " . $stmt->error;
        header("Location: completeprofile.php");
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>