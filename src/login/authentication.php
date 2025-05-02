<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../../connection/connect.php';

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
    $stmt = $conn->prepare("SELECT UserID, Password, Role FROM users WHERE UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["Password"])) {
            $_SESSION["user_id"] = $user["UserID"];
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $user["Role"];

            if ($user["Role"] === "Admin") {
                header("Location: ../admin/dashboard.php");
                exit;
            }

            header("Location: ../residents/dashboard.php");
            exit;
        } else {
            $_SESSION["error"] = "Invalid credentials.";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION["error"] = "Invalid credentials.";
        header("Location: login.php");
        exit;
    }
}
?>