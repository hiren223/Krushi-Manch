<?php
session_start();

    $server = "localhost";
    $Username = "root";
    $password = "";
    $database = "krushi-manch";
    $port= "3307";

    $conn = mysqli_connect($server, $Username, $password, $database, $port );
    if (!$conn) {
        die('Error' . mysqli_connect_error());
    }

if (!isset($_SESSION['Loggedin']) || $_SESSION['Loggedin'] !== true) {
    header("Location: /krushi-manch/Auth/login/login.php");
    exit;
}

$username = $_SESSION['username'];
$updated = false;
$error = false;
$passwordMismatch = false;

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $newPass = $_POST['password'];
    $confirmPass = $_POST['confirm_password'];

    if ($newPass === $confirmPass) {
        $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);

        // Update query
        $sql = "UPDATE `user` SET Username='$name', email='$email', phoneNo='$phone', password='$hashedPass' WHERE Username='$username'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['username'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $updated = true;
        } else {
            $error = true;
        }
    } else {
        $passwordMismatch = true;
    }
}

// Fetch user details
$sql = "SELECT * FROM `user` WHERE Username='$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Profile - Krushi Manch</title>
    <link rel="stylesheet" href="signup.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body>

    <?php
    $loggedin = isset($_SESSION['Loggedin']) && $_SESSION['Loggedin'] === true;

    echo '<nav>
    <div class="logo">
        <img src="./Krushi_Manch.png" alt="#">
    </div>';

    echo '<div class="dropdown">
    <i class="ri-user-line dropbtn"></i>
    <div class="dropdown-content">';
    echo ' <a class="nav-link" href="/krushi-manch/welcome.php">Home</a>';
    if (!$loggedin) {
        echo ' <a class="nav-link" href="/krushi-manch/login/login.php">Login</a>
           <a class="nav-link" href="/krushi-manch/regitration/signup.php">Signup</a>';
    } else {
        echo ' <a class="nav-link" href="/krushi-manch/login/logout.php">Logout</a>';
    }
    echo '</div></div></nav>';
    ?>

    <div class="container">
        <!-- Left Side - Profile Form -->
        <div class="signup-section">
            <h2>Update Your Profile</h2>
            <p>"Growing together, thriving forever."</p>

            <form action="" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $row['Username']; ?>" required />

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required />

                <label for="mnumber">Mobile No.</label>
                <input type="number" id="mnumber" name="phone" value="<?php echo $row['phoneNo']; ?>" required />

                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required />

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required />

                <button type="submit" name="update" class="submit-btn">Update</button>
            </form>
        </div>

        <!-- Right Side - Info Section -->
        <div class="info-section">
            <!-- Optional image or information -->
        </div>
    </div>

    <?php if ($updated): ?>
        <script>
            Swal.fire("Updated!", "Your profile has been updated.", "success").then(() => {
                window.location.href = "/krushi-manch/welcome.php";
            });
        </script>
    <?php elseif ($passwordMismatch): ?>
        <script>
            Swal.fire("Error!", "Passwords do not match.", "error");
        </script>
    <?php elseif ($error): ?>
        <script>
            Swal.fire("Error!", "Something went wrong while updating.", "error");
        </script>
    <?php endif; ?>

</body>

</html>