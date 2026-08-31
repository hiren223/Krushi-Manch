<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "krushi-manch";
$port= "3307";

$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
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

    // Corrected SQL Query (No Duplicate Columns)
    $sql = "INSERT INTO `Plough-rent` (`name`, `number`, `power`, `type`, `rent_days`, `rent_price`, `location`, `vehicle-name`, `front`, `right`, `left`, `back`)
            VALUES ('$name', '$phone', '$power', '$type', '$rentdays', '$rentprice', '$location', '$vehicle', '$front', '$right', '$left', '$back')";
            $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>
        alert("Equipment added for rent successfully!");
        window.location.href = "/krushi-manch/Plough/Plough.php";
        </script>';
    } else {
        echo '<script>
        alert("Equipment not added for rent successfully! Error: ' . mysqli_error($conn) . '");
        window.location.href = "/krushi-manch/Plough/Plough.php";
        </script>';
    }
}
?>
