<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];
$user_role = $_SESSION['user_role'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE email='$user_email' OR mobile='$user_email'");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #333;
        }

        .header {
            background: #00796B;
            color: #fff;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header h2 {
            font-size: 24px;
        }

        .logout-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background-color: #d32f2f;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .dashboard {
            max-width: 1100px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        .info {
            text-align: center;
            margin-bottom: 20px;
        }

        .role {
            color: #555;
        }

        .btn-group {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 8px;
            background: #388e3c;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #2e7d32;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .product {
            background: #fdfdfd;
            border: 1px solid #e0e0e0;
            padding: 20px;
            border-radius: 10px;
            transition: box-shadow 0.3s;
        }

        .product:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .product h4 {
            font-size: 20px;
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .product p {
            margin: 6px 0;
            font-size: 18px;
        }

        .buy-btn {
            display: inline-block;
            margin-top: 12px;
            background: #1e88e5;
            color: white;
            padding: 10px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .buy-btn:hover {
            background: #1565c0;
        }

        @media screen and (max-width: 600px) {
            .btn {
                display: block;
                width: 100%;
                margin: 10px auto;
            }

            .logout-btn {
                position: static;
                margin: 10px auto;
                display: block;
                width: 90%;
            }

            .dashboard {
                margin: 15px;
                padding: 20px;
            }

            .product-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Welcome, <?php echo $user['full_name']; ?></h2>
    <a href="logout.php"><button class="logout-btn">Logout</button></a>
</div>

<div class="dashboard">
    <div class="info">
        <p>You are logged in as <span class="role"><?php echo ucfirst($user_role); ?></span></p>
    </div>

    <div class="btn-group">
        <?php if ($user_role == 'farmer') { ?>
            <a href="add_product.php" class="btn">➕ Add Product</a>
            <a href="view_orders.php" class="btn">📦 View Orders</a>
        <?php } else { ?>
            <a href="orders_history.php" class="btn">🛒 My Orders</a>
        <?php } ?>
    </div>

    <h3 style="text-align:center; margin-bottom: 20px;">Available Products</h3>

    <div class="product-list">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM products WHERE status = 'visible' AND quantity > 0 ORDER BY id DESC");
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product'>";
                echo "<h4>" . htmlspecialchars($row['product_name']) . "</h4>";
                echo "<p><strong>Farmer Name:</strong> " . htmlspecialchars($row['farmer_name']) . "</p>";
                echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
                echo "<p><strong>Quantity (20kg):</strong> " . htmlspecialchars($row['quantity']) . "</p>";
                echo "<p><strong>Price:</strong> ₹" . htmlspecialchars($row['price']) . "</p>";
                if ($user_role == 'buyer') {
                    echo "<a href='place_order.php?product_id=" . $row['id'] . "' class='buy-btn'>Buy Now</a>";
                }
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center;'>No products available.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
