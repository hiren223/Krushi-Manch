<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "krushi-manch";
$port= "3307";

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $rentdays = $_POST['rent_days'];
    $rentprice = $_POST['rent_price'];
    $location = $_POST['location'];
    $vehicle = $_POST['vehicle'];
    $power = $_POST['power'];
    $type = $_POST['type'];

    // File uploads
    $front = $left = $right = $back = "";

    if (!empty($_FILES['front']['name'])) {
        $frimage = $_FILES['front']['name'];
        $fimage_tmp = $_FILES['front']['tmp_name'];
        $front = 'images/' . $frimage;
        move_uploaded_file($fimage_tmp, $front);
    }

    if (!empty($_FILES['left']['name'])) {
        $leimage = $_FILES['left']['name'];
        $limage_tmp = $_FILES['left']['tmp_name'];
        $left = 'images/' . $leimage;
        move_uploaded_file($limage_tmp, $left);
    }

    if (!empty($_FILES['right']['name'])) {
        $riimage = $_FILES['right']['name'];
        $rimage_tmp = $_FILES['right']['tmp_name'];
        $right = 'images/' . $riimage;
        move_uploaded_file($rimage_tmp, $right);
    }

    if (!empty($_FILES['back']['name'])) {
        $baimage = $_FILES['back']['name'];
        $bimage_tmp = $_FILES['back']['tmp_name'];
        $back = 'images/' . $baimage;
        move_uploaded_file($bimage_tmp, $back);
    }

    $sql = "INSERT INTO `harvester-rent` (`name`, `number`, `rent_days`, `rent_price`, `location`, `vehicle`, `power`, `type`, `front`, `left`, `right`, `back`) VALUES ('$name', '$number', '$rentdays', '$rentprice', '$location', '$vehicle', '$power', '$type', '$front', '$left', '$right', '$back')";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../harvester.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
