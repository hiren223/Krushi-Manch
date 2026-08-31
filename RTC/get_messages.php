<?php
$host = "localhost";   // Using localhost for XAMPP
$user = "root";        // Default MySQL username in XAMPP
$pass = "";            // Default password is empty for XAMPP
$db = "krushi-manch";       // Database name you created

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM messages ORDER BY id DESC LIMIT 50");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(array_reverse($messages)); // Reverse to show the oldest first
$conn->close();
?>
