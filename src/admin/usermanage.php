<?php
session_start();
require '../../connection/connect.php';
require 'pendingapp.php';

if (isset($_SESSION["success"])) {
    echo "<p style='color: green;'>" . $_SESSION["success"] . "</p>";
    unset($_SESSION["success"]);
}
if (isset($_SESSION["error"])) {
    echo "<p style='color: red;'>" . $_SESSION["error"] . "</p>";
    unset($_SESSION["error"]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>
    <h1>Pending User Approvals</h1>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row["UserName"]); ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="pending_user_id" value="<?php echo $row["PendingUserID"]; ?>">
                    <button type="submit" name="approve">Approve</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="pending_user_id" value="<?php echo $row["PendingUserID"]; ?>">
                    <button type="submit" name="reject">Reject</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>