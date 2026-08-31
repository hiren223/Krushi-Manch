<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = mysqli_real_escape_string($conn, trim($_POST['identifier']));
    $password = $_POST['password'];

    if (empty($identifier) || empty($password)) {
        $_SESSION['popup_message'] = "Please fill in all fields.";
    } else {
        // FIRST: Check if it's an ADMIN
        $admin_query = "SELECT * FROM admin WHERE email='$identifier'";
        $admin_result = mysqli_query($conn, $admin_query);
        $admin = mysqli_fetch_assoc($admin_result);

        if ($admin && password_verify($password, $admin['password'])) {
            // Admin login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_role'] = $admin['role'];
            header("Location: ../admin/admin_dashboard.php");
            exit();
        } else {
            // SECOND: Check if it's a USER
            $user_query = "SELECT * FROM users WHERE email='$identifier' OR mobile='$identifier'";
            $user_result = mysqli_query($conn, $user_query);
            $user = mysqli_fetch_assoc($user_result);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] === 'blocked') {
                    $_SESSION['popup_message'] = "Your account has been blocked. Please contact support.";
                } else {
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                $_SESSION['popup_message'] = "Invalid email/mobile or password!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Krushi Manch</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            width: 90%;
            margin: 60px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2e8b57;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            font-size: 17px;
            margin-bottom: -8px;
            font-weight: bold;
            color: #333;
        }

        form input {
            width: 100%;
            padding: 12px 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #2e8b57;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #256d45;
        }

        .toggle-password {
            position: relative;
            display: flex;
            align-items: center;
        }

        .toggle-password input {
            padding-right: 40px;
            width: 100%;
        }

        .toggle-password span {
            position: absolute;
            right: 12px;
            cursor: pointer;
            font-size: 18px;
            color: #666;
        }

        .forgot-link {
            text-align: right;
            margin: -5px 0 15px 0;
        }

        .forgot-link a {
            font-size: 14px;
            color: #2e8b57;
            text-decoration: none;
        }

        .forgot-link a:hover {
            text-decoration: underline;
        }

        p {
            text-align: center;
        }

        a {
            color: #2e8b57;
            text-decoration: none;
        }

        .message {
            color: red;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                margin: 40px auto;
                padding: 25px;
            }

            h2 {
                font-size: 22px;
            }

            form input,
            form button {
                font-size: 14px;
                padding: 10px;
            }

            .toggle-password span {
                top: 32px;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 30px 15px;
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            form input,
            form button {
                font-size: 13px;
                padding: 9px;
            }

            .toggle-password span {
                top: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login - Krushi Manch</h2>
        <!-- <p style="font-size: 14px; color: #666; margin: 0 0 20px 0;">
            Users & Admin Panel
        </p> -->
        <form method="POST" onsubmit="return validateLogin();">
            <label for="identifier">Email or Mobile Number</label>
            <input type="text" name="identifier" id="identifier" placeholder="Enter your email or mobile" required>

            <label for="password">Password</label>
            <div class="toggle-password">
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <span onclick="togglePassword()">👁</span>
            </div>

            <div class="forgot-link">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <button type="submit">Login</button>
        </form>
        <p>New user? <a href="register.php">Register here</a></p>

        <?php if (isset($_SESSION['popup_message'])): ?>
            <p class="message"><?= $_SESSION['popup_message']; unset($_SESSION['popup_message']); ?></p>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
        }

        function validateLogin() {
            const identifier = document.getElementById('identifier').value.trim();
            const password = document.getElementById('password').value;

            const mobileRegex = /^[0-9]{10}$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!identifier) {
                alert("Please enter your email or mobile number.");
                return false;
            }

            if (!mobileRegex.test(identifier) && !emailRegex.test(identifier)) {
                alert("Enter a valid 10-digit mobile number or email address.");
                return false;
            }

            if (password.trim() === '') {
                alert("Please enter your password.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
