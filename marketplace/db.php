<?php
$host = "localhost";
$user = "root"; // Change if needed
$pass = ""; // Change if needed
$dbname = "krushi-manch";
$port= "3307";


$conn = new mysqli($host, $user, $pass, $dbname, $port);

mysqli_set_charset($conn, "utf8mb4");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>



