<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $address = trim($_POST["address"]);
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    if (empty($firstName) || empty($lastName) || empty($address) || empty($username) || empty($password)) {
        $_SESSION["error"] = "Please fill in all fields.";
        header("Location: createaccount.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT UserName FROM users WHERE UserName = ? UNION SELECT UserName FROM pending_users WHERE UserName = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["error"] = "Username already exists.";
        header("Location: createaccount.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO pending_users (FirstName, LastName, Address, UserName, Password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $address, $username, $password);

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