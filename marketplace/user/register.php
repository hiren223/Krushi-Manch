<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    $role = trim($_POST['role']);

    if (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
        $_SESSION['popup_message'] = "Full name should only contain letters and spaces.";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $_SESSION['popup_message'] = "Mobile number must be exactly 10 digits.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['popup_message'] = "Invalid email address.";
    } elseif (strlen($password_raw) < 6) {
        $_SESSION['popup_message'] = "Password must be at least 6 characters.";
    } elseif (empty($role)) {
        $_SESSION['popup_message'] = "Please select a role.";
    } else {
        $full_name = mysqli_real_escape_string($conn, $full_name);
        $mobile = mysqli_real_escape_string($conn, $mobile);
        $email = mysqli_real_escape_string($conn, $email);
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $role = mysqli_real_escape_string($conn, $role);

        $query = "INSERT INTO users (full_name, mobile, email, password, role)
                  VALUES ('$full_name', '$mobile', '$email', '$password', '$role')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['popup_message'] = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Krushi Manch</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #2e8b57;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 17px;
            color: #333;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        input:focus,
        select:focus {
            border-color: #2e8b57;
            outline: none;
        }

        button {
            padding: 12px;
            background-color: #2e8b57;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #256d45;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #2e8b57;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 30%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            align-items: center;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            input,
            select,
            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <form method="POST" onsubmit="return validateForm();">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" id="full_name" placeholder="Enter your full name" required>

        <label for="mobile">Mobile Number</label>
        <input type="text" name="mobile" id="mobile" placeholder="Enter your mobile number" required maxlength="10">

        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="Enter your email address" required>

        <label for="password">Password</label>
        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Enter your password (min 6 characters)" required>
            <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
        </div>

        <label for="role">Role</label>
        <select name="role" id="role" required>
            <option value="" disabled selected>Select your role</option>
            <option value="farmer">Farmer</option>
            <option value="buyer">Buyer</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>

    <?php if (isset($_SESSION['popup_message'])): ?>
        <p class="message"><?= $_SESSION['popup_message']; unset($_SESSION['popup_message']); ?></p>
    <?php endif; ?>
</div>

<script>
    // Mobile input restriction
    document.getElementById('mobile').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 10) this.value = this.value.slice(0, 10);
    });

    function validateForm() {
        const fullName = document.getElementById('full_name').value.trim();
        const mobile = document.getElementById('mobile').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const role = document.getElementById('role').value;

        const nameRegex = /^[a-zA-Z\s]+$/;
        const mobileRegex = /^[0-9]{10}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!nameRegex.test(fullName)) {
            alert("Full name should contain only letters and spaces.");
            return false;
        }

        if (!mobileRegex.test(mobile)) {
            alert("Mobile number must be exactly 10 digits.");
            return false;
        }

        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }

        if (password.length < 6) {
            alert("Password must be at least 6 characters.");
            return false;
        }

        if (role === "") {
            alert("Please select a role.");
            return false;
        }

        return true;
    }

    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const passwordType = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = passwordType;
    }
</script>

</body>
</html>
