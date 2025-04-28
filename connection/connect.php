<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "barangay_comp_system"; //chati ko kung gusto mo updated database 
 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$timeout_duration = 180; //(seconds)

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../src/login/login.php"); 
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time(); 
?>