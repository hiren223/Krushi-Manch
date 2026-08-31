<?php
$login = false;
$showError = false;

if (isset($_POST['login'])) {
    include './partials/_dbconnect.php';
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `user` WHERE Username='$username' AND phoneNo='$phone'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['Loggedin'] = true;
            $_SESSION['username'] = $row['Username']; // ✅ this is key
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phoneNo'];
            $_SESSION['name'] = $row['name']; // ✅ only if you have a 'name' column in DB
            header("location: /krushi-manch/welcome.php");
            exit;
        }
    } else {
        $showError = "Invalid Credentials";
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
    <link rel="stylesheet" href="./Auth/login/login.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body>
    <?php
    echo '<nav>
        <div class="logo">
            <img src="Auth/login/Krushi_Manch.png" alt="#">
        </div>';


    echo '  <div class="dropdown">
    <i class="ri-user-line dropbtn"></i>
    <div class="dropdown-content">';
    if (!$loggedin) {
        echo ' <a class="nav-link" href="http://localhost/krushi-manch/Auth/login/login.php">Login</a>                      
                            <a class="nav-link" href="http://localhost/krushi-manch/Auth/regitration/signup.php">singup</a>
                        ';
    }
    if ($loggedin) {
        echo    ' <a class="nav-link" href="/krushi-manch/Auth/login/login.php">logout</a>           
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

            <form action="/krushi-manch/index.php" method="post">
                <label for="name">Name</label>
                <input type="text" id="name" name="username" placeholder="Enter your name" required />

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required />

                <label for="email">Mobile No.</label>
                <input type="number" name="phone" id="mnumber" maxlength="10" placeholder="Enter your phone" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required />

                <button type="submit" name="login" class="submit-btn">Log In</button>
                <p>Don't have an account? <a href="./Auth/regitration/signup.php">Sign Up</a></p>
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
</body>
<script>
    const mnumber = document.getElementById('mnumber');
    const error = document.getElementById('error');

    mnumber.addEventListener('input', () => {
        // Convert to string and remove any non-digit characters
        let val = mnumber.value.toString().replace(/\D/g, '');

        // Trim to max 10 digits
        if (val.length > 10) {
            val = val.slice(0, 10);
        }

        // Set trimmed value back
        mnumber.value = val;

        // Show/hide error
        error.style.display = (val.length === 10) ? 'none' : 'block';
    });
</script>

</html>