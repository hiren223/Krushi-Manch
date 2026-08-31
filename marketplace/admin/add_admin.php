<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Only owner can access this page
if ($_SESSION['admin_role'] !== 'owner') {
    header("Location: manage_admins.php");
    exit();
}

include 'db.php';

$success = "";
$error = "";

// Handle new admin registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $admin_name = mysqli_real_escape_string($conn, trim($_POST['admin_name']));
    $admin_email = mysqli_real_escape_string($conn, trim($_POST['admin_email']));
    $admin_password = $_POST['admin_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($admin_name) || empty($admin_email) || empty($admin_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($admin_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($admin_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM admin WHERE email = '$admin_email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already registered as admin.";
        } else {
            // Hash the password
            $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

            // Insert new admin
            $insert_query = "INSERT INTO admin (name, email, password, role) VALUES ('$admin_name', '$admin_email', '$hashed_password', 'admin')";
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
    <title>Add Admin - Krushi Manch</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 550px;
            margin: 0 auto;
            background-color: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            color: #4CAF50;
            margin: 0 0 10px 0;
            font-size: 26px;
        }
        .header p {
            color: #777;
            margin: 0;
            font-size: 14px;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 12px 15px;
            margin: 15px 0 20px 0;
            border-radius: 6px;
            font-size: 14px;
        }
        .info-box strong {
            color: #1976D2;
        }
        .form-group {
            margin: 18px 0;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        label i {
            color: #4CAF50;
            margin-right: 6px;
            width: 16px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1.5px solid #ddd;
            font-size: 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        .hint {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .btn-back {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            margin-top: 12px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #1a252f;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }
        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #888;
            font-size: 13px;
        }
        @media (max-width: 480px) {
            .container {
                padding: 25px 20px;
            }
            .header h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-user-plus"></i> Add Admin</h2>
            <p>Create a new admin account</p>
        </div>

        <div class="info-box">
            <strong>Current Owner:</strong> <?php echo htmlspecialchars($_SESSION['admin_name']); ?> (<?php echo htmlspecialchars($_SESSION['admin_email']); ?>)
        </div>

        <?php if ($success): ?>
            <div class="success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="admin_name"><i class="fas fa-user"></i> Admin Name</label>
                <input type="text" name="admin_name" id="admin_name" placeholder="Enter admin full name" required>
            </div>

            <div class="form-group">
                <label for="admin_email"><i class="fas fa-envelope"></i> Admin Email</label>
                <input type="email" name="admin_email" id="admin_email" placeholder="Enter admin email" required>
            </div>

            <div class="form-group">
                <label for="admin_password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="admin_password" id="admin_password" placeholder="Enter password" required>
                <div class="hint">Minimum 6 characters</div>

            <div class="form-group">
                <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>
            </div>

            <button type="submit" name="register"><i class="fas fa-user-plus"></i> Create Admin Account</button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <a href="manage_admins.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Manage Admins</a>
    </div>
</body>
</html>
