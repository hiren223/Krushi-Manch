 <!-- #region --><?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
include 'db.php';
$admin_email = $_SESSION['admin_email'];

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_farmers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='farmer'"))['total'];
$total_buyers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='buyer'"))['total'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];
?><!--  -->
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Krushi Manch</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
        }
        .navbar {
            background-color: #2c3e50;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            margin: 0;
            font-size: 24px;
        }
        .navbar .email {
            font-size: 16px;
            opacity: 0.8;
        }
        .dashboard {
            max-width: 1300px;
            margin: 30px auto;
            padding: 20px;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }
        .stats-box {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
            transition: 0.3s ease;
            text-align: center;
        }
        .stats-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .stats-box i {
            font-size: 30px;
            color: #4CAF50;
            margin-bottom: 15px;
        }
        .stats-box h3 {
            font-size: 32px;
            color: #333;
            margin: 0;
        }
        .stats-box p {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }
        .btn-group {
            text-align: center;
            margin-top: 40px;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 12px 25px;
            font-size: 15px;
            border-radius: 8px;
            border: none;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .logout-btn {
            background-color: #e74c3c;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .admin-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 16px;
            font-size: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .admin-btn:hover {
            background-color: #45a049;
        }
        @media (max-width: 768px) {
            .navbar h2 {
                font-size: 20px;
            }
            .navbar .email {
                font-size: 13px;
            }
            .nav-right {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Krushi Manch Admin Dashboard</h2>
        <div class="nav-right">
            <div class="email">Logged in as: <?php echo htmlspecialchars($admin_email); ?></div>
            <a href="manage_admins.php" class="admin-btn"><i class="fas fa-user-shield"></i> Admin</a>
        </div>
    </div>

    <div class="dashboard">
        <div class="stats-container">
            <div class="stats-box">
                <i class="fas fa-users"></i>
                <h3><?php echo $total_users; ?></h3>
                <p>Total Users</p>
            </div>
            <div class="stats-box">
                <i class="fas fa-tractor"></i>
                <h3><?php echo $total_farmers; ?></h3>
                <p>Total Farmers</p>
            </div>
            <div class="stats-box">
                <i class="fas fa-user-tag"></i>
                <h3><?php echo $total_buyers; ?></h3>
                <p>Total Buyers</p>
            </div>
            <div class="stats-box">
                <i class="fas fa-seedling"></i>
                <h3><?php echo $total_products; ?></h3>
                <p>Total Products</p>
            </div>
            <div class="stats-box">
                <i class="fas fa-box"></i>
                <h3><?php echo $total_orders; ?></h3>
                <p>Total Orders</p>
            </div>
        </div>

        <div class="btn-group">
            <a href="manage_users.php" class="btn">Manage Users</a>
            <a href="manage_products.php" class="btn">Manage Products</a>
            <a href="manage_orders.php" class="btn">Manage Orders</a>
            <a href="admin_logout.php" class="btn logout-btn">Logout</a>
        </div>
    </div>

</body>
</html>
