<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../../connection/connect.php'; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        $_SESSION["error"] = "CSRF validation failed.";
        header("Location: login.php");
        exit;
    }

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $_SESSION["error"] = "Please enter both username and password.";
        header("Location: login.php");
        exit;
    }

    // Initialize session variables for failed attempts and lockout
    if (!isset($_SESSION["failed_attempts"])) {
        $_SESSION["failed_attempts"] = 0;
    }
    if (!isset($_SESSION["lockout_time"])) {
        $_SESSION["lockout_time"] = null;
    }

    // Check if the account is locked
    if ($_SESSION["lockout_time"] && time() < $_SESSION["lockout_time"]) {
        $remaining_time = $_SESSION["lockout_time"] - time();
        $_SESSION["error"] = "Account locked. Try again in $remaining_time seconds.";
        header("Location: login.php");
        exit;
    }

    // Reset lockout if the lockout period has expired
    if ($_SESSION["lockout_time"] && time() >= $_SESSION["lockout_time"]) {
        $_SESSION["failed_attempts"] = 0;
        $_SESSION["lockout_time"] = null;
    }

    // Query the database for the user
    $stmt = $conn->prepare("SELECT UserID, Password, Role FROM users WHERE UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user["Password"])) {
            // Successful login
            $_SESSION["user_id"] = $user["UserID"];
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $user["Role"];

            // Reset failed attempts and lockout
            $_SESSION["failed_attempts"] = 0;
            $_SESSION["lockout_time"] = null;

            // Redirect based on role
            if ($user["Role"] === "Admin") {
                header("Location: ../admin/dashboard.php");
            } elseif ($user["Role"] === "Resident") {
                header("Location: ../residents/dashboard.php");
            }
            exit;
        } else {
            // Invalid password
            $_SESSION["failed_attempts"]++;
            if ($_SESSION["failed_attempts"] >= 5) {
                $_SESSION["lockout_time"] = time() + 900; // Lock for 15 minutes
                $_SESSION["error"] = "Too many failed attempts. Account locked for 15 minutes.";
                header("Location: login.php");
                exit;
            }
            $_SESSION["error"] = "Invalid credentials. Attempt " . $_SESSION["failed_attempts"] . " of 5.";
            header("Location: login.php");
            exit;
        }
    } else {
        // Invalid username
        $_SESSION["failed_attempts"]++;
        if ($_SESSION["failed_attempts"] >= 5) {
            $_SESSION["lockout_time"] = time() + 900; // Lock for 15 minutes
            $_SESSION["error"] = "Too many failed attempts. Account locked for 15 minutes.";
            header("Location: login.php");
            exit;
        }
        $_SESSION["error"] = "Invalid credentials. Attempt " . $_SESSION["failed_attempts"] . " of 5.";
        header("Location: login.php");
        exit;
    }
}

// Generate a CSRF token
if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
?>