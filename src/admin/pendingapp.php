<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["approve"])) {
        $pendingUserID = $_POST["pending_user_id"];
        $stmt = $conn->prepare("SELECT FirstName, LastName, Address, UserName, Password FROM pending_users WHERE PendingUserID = ?");
        $stmt->bind_param("i", $pendingUserID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $stmt = $conn->prepare("INSERT INTO users (UserName, Password, Role) VALUES (?, ?, 'Resident')");
            $stmt->bind_param("ss", $user["UserName"], $user["Password"]);
            $stmt->execute();

            $userID = $conn->insert_id;

            $stmt = $conn->prepare("INSERT INTO residents (FirstName, LastName, Address, UserID) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $user["FirstName"], $user["LastName"], $user["Address"], $userID);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM pending_users WHERE PendingUserID = ?");
            $stmt->bind_param("i", $pendingUserID);
            $stmt->execute();

            echo "User approved and added.";
        }
    } elseif (isset($_POST["reject"])) {
        $pendingUserID = $_POST["pending_user_id"];
        $stmt = $conn->prepare("DELETE FROM pending_users WHERE PendingUserID = ?");
        $stmt->bind_param("i", $pendingUserID);
        $stmt->execute();

        echo "User rejected.";
    }
}

$result = $conn->query("SELECT * FROM pending_users");
?>