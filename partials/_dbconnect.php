<?php

$server = "localhost";
$Username = "root";
$password = "";
$database = "krushi-manch";
$port= "3307";

$conn= mysqli_connect($server, $Username, $password, $database, $port);
if(!$conn){
    die('Error'.mysqli_connect_error());
}

?>