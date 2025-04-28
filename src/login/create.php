<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    if (empty($username) || empty($password)) {
        $_SESSION["error"] = "Please fill in all fields.";
        header("Location: createaccount.php");
        exit;
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT UserName FROM users WHERE UserName = ? UNION SELECT UserName FROM pending_users WHERE UserName = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["error"] = "Username already exists.";
        header("Location: createaccount.php");
        exit;
    }

    // Insert into pending_users
    $stmt = $conn->prepare("INSERT INTO pending_users (UserName, Password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Account request submitted. Please wait for admin approval.";
        header("Location: login.php");
    } else {
        $_SESSION["error"] = "Error: " . $stmt->error;
        header("Location: createaccount.php");
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>