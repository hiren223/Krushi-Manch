<?php
session_start();
include 'db.php';

$step = 1;
$identifier = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['step']) && $_POST['step'] == 1) {
        $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
        $query = "SELECT * FROM users WHERE email='$identifier' OR mobile='$identifier'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['identifier'] = $identifier;
            $step = 2;
        } else {
            $_SESSION['popup_message'] = "User not found!";
        }
    } elseif (isset($_POST['step']) && $_POST['step'] == 2) {
        $identifier = $_SESSION['identifier'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $_SESSION['popup_message'] = "Passwords do not match!";
            $step = 2;
        } elseif (strlen($new_password) < 6) {
            $_SESSION['popup_message'] = "Password should be at least 6 characters!";
            $step = 2;
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = "UPDATE users SET password='$hashed_password' WHERE email='$identifier' OR mobile='$identifier'";
            if (mysqli_query($conn, $update)) {
                unset($_SESSION['identifier']);
                $_SESSION['popup_message'] = "Password reset successful. Please login.";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['popup_message'] = "Error updating password.";
                $step = 2;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
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
            width: 100%;
            max-width: 420px;
            margin: 60px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2e8b57;
            margin-bottom: 25px;
        }

        form input, form button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        form button {
            background-color: #2e8b57;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        form button:hover {
            background-color: #246b46;
        }

        .message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2e8b57;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 18px;
            cursor: pointer;
        }

        label {
            font-size: 16px;
            margin-bottom: -7px;
            display: block;
            color: #333;
            font-weight: bold;
        }

        @media (max-width: 500px) {
            .container {
                margin: 30px 20px;
                padding: 20px;
            }

            form input, form button {
                font-size: 14px;
                padding: 10px;
            }

            h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>

    <form method="POST">
        <?php if ($step == 1): ?>
            <input type="hidden" name="step" value="1">
            <label for="identifier">Email or Mobile</label>
            <input type="text" name="identifier" id="identifier" placeholder="Enter your Email or Mobile" required>
            <button type="submit">Next</button>
        <?php elseif ($step == 2): ?>
            <input type="hidden" name="step" value="2">
            <label for="new_password">New Password</label>
            <div style="position: relative;">
                <input type="password" name="new_password" id="new_password" placeholder="Enter New Password" required>
                <span toggle="#new_password" class="toggle-password">👁️</span>
            </div>

            <label for="confirm_password">Confirm New Password</label>
            <div style="position: relative;">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" required>
                <span toggle="#confirm_password" class="toggle-password">👁️</span>
            </div>

            <button type="submit">Reset Password</button>
        <?php endif; ?>
    </form>

    <a href="login.php">Back to Login</a>

    <?php if (isset($_SESSION['popup_message'])): ?>
        <p class="message"><?= $_SESSION['popup_message']; unset($_SESSION['popup_message']); ?></p>
    <?php endif; ?>
</div>

<script>
    const togglePasswordVisibility = (target) => {
        const passwordField = document.querySelector(target);
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;
    };

    document.querySelectorAll('.toggle-password').forEach(item => {
        item.addEventListener('click', () => {
            const target = item.getAttribute('toggle');
            togglePasswordVisibility(target);
        });
    });
</script>

</body>
</html>
