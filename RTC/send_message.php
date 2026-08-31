<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "krushi-manch";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['user'];
$message = $_POST['message'];

$sql = "INSERT INTO messages (user, message) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $message);
$stmt->execute();
$conn->close();
?>
