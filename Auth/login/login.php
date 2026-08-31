<?php
$login = false;
$showError = false;
    $server = "localhost";
    $Username = "root";
    $password = "";
    $database = "krushi-manch";
    $port= "3307";

    $conn = mysqli_connect($server, $Username, $password, $database, $port);
    if (!$conn) {
        die('Error' . mysqli_connect_error());
    }

if (isset($_POST['login'])) {
 
    $emailOrPhone = $_POST['emailOrPhone'];
    $password = $_POST['password'];

    // Check if input is email or phone
    if (filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL)) {
        // It's an email
        $sql = "SELECT * FROM `user` WHERE email='$emailOrPhone'";
    } else {
        // It's a phone number
        $sql = "SELECT * FROM `user` WHERE phoneNo='$emailOrPhone'";
    }

    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['Loggedin'] = true;
            $_SESSION['username'] = $row['Username']; 
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phoneNo'];
            $_SESSION['name'] = $row['name']; 
            header("location: /krushi-manch/welcome.php");
            exit;
        } else {
            $showError = "Invalid Password";
        }
    } else {
        $showError = "Email or Phone not found";
    }
}
?>

<?php
if (isset($_SESSION['Loggedin']) && ($_SESSION['Loggedin'] == true)) {
    $loggedin = true;
} else {
    $loggedin = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Krushi Manch</title>
    <link rel="stylesheet" href="login.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body>
    <?php
    echo '<nav>
        <div class="logo">
            <img src="./Krushi_Manch.png" alt="#">
        </div>';


    echo '  <div class="dropdown">
    <i class="ri-user-line dropbtn"></i>
    <div class="dropdown-content">';
    if (!$loggedin) {
        echo ' <a class="nav-link" href="http://localhost/krushi-manch/login/login.php">Login</a>                      
                            <a class="nav-link" href="http://localhost/krushi-manch/Auth/regitration/signup.php">singup</a>
                        ';
    }
    if ($loggedin) {
        echo    ' <a class="nav-link" href="/krushi-manch/login/login.php">logout</a>           
                             ';
    }
    echo '
    </div>
</div>';
    echo '
    </nav>';
    ?>
    <div class="container">
        <!-- Left Side - Signup Form -->
        <div class="signup-section">
            <h2>Log In here</h2>
            <p>"From soil to success"</p>

            <form action="../login/login.php" method="post">
                <label for="emailOrPhone">Email or Phone Number</label>
                <input type="text" id="emailOrPhone" name="emailOrPhone" placeholder="Enter your email or phone number" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required />

                <button type="submit" name="login" class="submit-btn">Log In</button>
                <p>Don't have an account? <a href="../regitration/signup.php">Sign Up</a></p>
            </form>
        </div>

        <!-- Right Side - Info Section -->
        <div class="info-section">
        </div>
    </div>
    <?php
    if ($login) {
        echo '<script>
        Swal.fire({
            title: "You are successfully loggedin!",
            text: "thank you for joining us",
            icon: "success"
            }) </script>';
    }
    if ($showError) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: " Please enter valid details",
                }) </script>';
    }

    ?>
    <!-- <div class="loader">
    <div class="loader-overlay">
        <div class="spinner"></div>
    </div>
</div>
<script src="/script.js"></script> -->

</body>

<script>
    const mnumber = document.getElementById('mnumber');
    const error = document.getElementById('error');

    mnumber.addEventListener('input', () => {
        // Convert to string and remove any non-digit characters
        let val = mnumber.value.toString().replace(/\D/g, '');
// No additional validation needed - users can enter email or phone