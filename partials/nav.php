<?php

    if (isset($_SESSION['Loggedin']) && ($_SESSION['Loggedin'] == true)) {
        $loggedin=true;
    }
    else{
        $loggedin=false;
    }

    echo '
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Krushi Manch</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="shortcut icon" href="/test website/image/K.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>
<style>
.username-display {
    margin-left: 3px;
    font-weight: 500;
    font-size: 1rem;
    color: black;
}

</style>

<body>
    <!-- Satrt Navbar -->
    <nav>
        <div class="logo">
            <img src="Auth/login/Krushi_Manch.png" alt="#">
        </div>';
        if ($loggedin) {
            echo'
        <input type="checkbox" id="click">
        <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
        </label>
            <ul class="text">
                <li><a class="active" href="#home">Home</a></li>
                <li><a href="#service">Services</a></li>
                <li><a href="#about-us">About Us</a></li>
                <li><a href="#contact">Contact Us</a></li>
    
          </ul>';
        }
     
    $loggedin = isset($_SESSION['Loggedin']) && $_SESSION['Loggedin'] === true;

echo '<div class="dropdown">
    <i class="ri-user-line dropbtn"></i>';

    echo '<div class="dropdown-content" style="
    width: 120px;     right: -2rem;

">';
if ($loggedin) {
    $displayName = isset($_SESSION['name']) ? $_SESSION['name'] : $_SESSION['username'];
    echo '<span class="username-display">' . htmlspecialchars($displayName) . '</span>';
}

if (!$loggedin) {
    echo ' <a class="nav-link" href="http://localhost/krushi-manch/Auth/login/login.php">Login</a>                      
                            <a class="nav-link" href="http://localhost/krushi-manch/Auth/regitration/signup.php">singup</a>
                        ';
}
if ($loggedin) {
    echo    ' <a class="nav-link" href="/krushi-manch/Auth/login/login.php">logout</a>   
    <a class="nav-link" href="/krushi-manch/Auth/Profile/Profile.php">Profile</a>         
                             ';
}
    echo '
    </div>
</div>';
echo '
    </nav>
    <!-- Close Navbar -->

</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll("nav ul.text li a");
        const checkbox = document.getElementById("click");

        navLinks.forEach(link => {
            link.addEventListener("click", function () {
                checkbox.checked = false; // Uncheck the checkbox to close menu
            });
        });
    });
</script>

</html>
' ?>