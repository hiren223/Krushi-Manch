<?php
/**
 * ADMIN PASSWORD HASHING SETUP SCRIPT
 * Run this ONCE to secure the existing admin password
 * Then DELETE this file for security
 */

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['secure_admin'])) {
    // Get current admin
    $query = "SELECT * FROM admin LIMIT 1";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin) {
        // Current plaintext password
        $plaintext_password = "abhi12345";

        // Hash it
        $hashed_password = password_hash($plaintext_password, PASSWORD_DEFAULT);

        // Update in database
        $update_query = "UPDATE admin SET password = '$hashed_password' WHERE id = " . $admin['id'];
        
        if (mysqli_query($conn, $update_query)) {
            echo "<div style='background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 4px;'>";
            echo "<h3>✓ Admin password secured!</h3>";
            echo "<p><strong>Email:</strong> " . $admin['email'] . "</p>";
            echo "<p><strong>Password:</strong> abhi12345 (now hashed)</p>";
            echo "<p style='color: red;'><strong>IMPORTANT:</strong> Delete this file (admin_setup.php) immediately for security!</p>";
            echo "</div>";
        } else {
            echo "<div style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 4px;'>";
            echo "<h3>✗ Error: " . mysqli_error($conn) . "</h3>";
            echo "</div>";
        }
    }
} else {
    echo "<div style='background-color: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px;'>";
    echo "<h2>Admin Password Hashing Setup</h2>";
    echo "<p>This will secure the existing admin password using encryption.</p>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='secure_admin' style='padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;'>Secure Admin Password Now</button>";
    echo "</form>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
    </style>
</head>
<body>
</body>
</html>
