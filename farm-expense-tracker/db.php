<?php
// db.php

$host = "localhost";
$username = "root";
$password = "";
$database = "krushi-manch";
$port= "3307";

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
