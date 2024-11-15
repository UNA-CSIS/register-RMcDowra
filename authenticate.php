<?php
// start session
session_start();
include_once 'validate.php';
$endUser = test_input($_POST['enduser']);
$endUserPassword = test_input($_POST['userpass']);

if (strlen($endUser) < 1 || strlen($endUserPassword) < 1) {
    header("location:index.php");
    exit();
}

// login to the softball database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softball"

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

// select password from users where username = <what the user typed in>
$sql = "SELECT password FROM users WHERE username = '$endUser'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        $verified = password_verify( $endUserPassword, trim($row['password']));
        if ($verified) {
            $_SESSION['username'] = $endUser;
            $_SESSION['error'] = '';
        } else {
            $_SESSION['error'] = 'invalid username or password';
        }
    }
} else {
    $_SESSION['error'] = 'invalid username or password';
}
$conn->close();
header("location:index.php");
?>





