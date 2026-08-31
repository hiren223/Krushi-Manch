<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] != true) {
    header("Location: admin_login.php");
    exit();
}

$success = "";
$error = "";

// Handle new admin registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $new_email = mysqli_real_escape_string($conn, $_POST['new_email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($new_email) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM admin WHERE email = '$new_email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already registered as admin.";
        } else {
            // Hash the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Insert new admin
            $insert_query = "INSERT INTO admin (email, password) VALUES ('$new_email', '$hashed_password')";
            if (mysqli_query($conn, $insert_query)) {
                $success = "Admin account created successfully!";
            } else {
                $error = "Error creating admin account: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .btn-logout {
            background-color: #f44336;
            margin-top: 20px;
        }
        .btn-logout:hover {
            background-color: #da190b;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Admin Account</h2>
        
        <div class="info-box">
            <strong>Current Admin:</strong> <?php echo $_SESSION['admin_email']; ?>
        </div>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="new_email">New Admin Email:</label>
                <input type="email" name="new_email" required>
            </div>

            <div class="form-group">
                <label for="new_password">Password:</label>
                <input type="password" name="new_password" required>
                <small style="color: #666;">Minimum 6 characters</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" name="register">Create Admin Account</button>
        </form>

        <form action="admin_dashboard.php">
            <button type="submit" class="btn-logout">Back to Dashboard</button>
        </form>

        <form action="admin_logout.php" style="margin-top: 10px;">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</body>
</html>
