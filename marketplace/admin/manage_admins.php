<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
include 'db.php';

// Delete action (only owner can delete)
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Prevent owner from deleting themselves
    if ($_SESSION['admin_role'] === 'owner') {
        // Check if trying to delete self
        if ($delete_id === $_SESSION['admin_id']) {
            $error = "You cannot delete your own account!";
        } else {
            // Check if target is another owner - prevent deleting other owners
            $check_query = "SELECT role FROM admin WHERE id = $delete_id";
            $check_result = mysqli_query($conn, $check_query);
            $target_admin = mysqli_fetch_assoc($check_result);
            
            if ($target_admin && $target_admin['role'] === 'owner') {
                $error = "Cannot delete another Owner account!";
            } else {
                mysqli_query($conn, "DELETE FROM admin WHERE id = $delete_id");
                $success = "Admin deleted successfully!";
            }
        }
    } else {
        $error = "Only Owner can delete admins!";
    }
}

// Fetch all admins
$result = mysqli_query($conn, "SELECT id, name, email, role FROM admin ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Admins - Krushi Manch</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 14px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
        }
        .owner-badge {
            background-color: #ff9800;
            color: white;
        }
        .admin-badge {
            background-color: #2196F3;
            color: white;
        }
        .btn {
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            display: inline-block;
            transition: opacity 0.2s;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .btn.delete {
            background-color: #e74c3c;
        }
        .btn.add {
            background-color: #4CAF50;
            margin-bottom: 20px;
        }
        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #2c3e50;
            padding: 10px 20px;
            color: white;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .back:hover {
            background: #1a252f;
        }
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        @media screen and (max-width: 600px) {
            body {
                padding: 20px;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th {
                display: none;
            }
            td {
                position: relative;
                padding-left: 50%;
                border: none;
                border-bottom: 1px solid #ddd;
            }
            td:before {
                position: absolute;
                top: 14px;
                left: 14px;
                width: 45%;
                font-weight: bold;
                color: #333;
            }
            td:nth-of-type(1):before { content: "ID"; }
            td:nth-of-type(2):before { content: "Name"; }
            td:nth-of-type(3):before { content: "Email"; }
            td:nth-of-type(4):before { content: "Role"; }
            td:nth-of-type(5):before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-users-cog"></i> Manage Admins</h2>

        <?php if (isset($success)): ?>
            <div class="success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <div class="action-bar">
            <a href="admin_dashboard.php" class="back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <?php if ($_SESSION['admin_role'] === 'owner'): ?>
                <a href="add_admin.php" class="btn add"><i class="fas fa-user-plus"></i> Add Admin</a>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <?php if ($row['role'] === 'owner') { ?>
                                <span class="badge owner-badge"><i class="fas fa-crown"></i> Owner</span>
                            <?php } else { ?>
                                <span class="badge admin-badge"><i class="fas fa-user-shield"></i> Admin</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($_SESSION['admin_role'] === 'owner' && $row['id'] !== $_SESSION['admin_id'] && $row['role'] !== 'owner') { ?>
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this admin?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            <?php } else { ?>
                                <span style="color: #999; font-size: 13px;">
                                    <?php if ($row['id'] === $_SESSION['admin_id']) { ?>
                                        <i class="fas fa-user"></i> You
                                    <?php } else { ?>
                                        <i class="fas fa-lock"></i> Protected
                                    <?php } ?>
                                </span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

