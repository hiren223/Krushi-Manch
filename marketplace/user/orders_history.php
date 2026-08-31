<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] != 'buyer') {
    header("Location: login.php");
    exit();
}

$buyer_email = $_SESSION['user_email'];
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE email='$buyer_email' OR mobile='$buyer_email'");
if (!$user_result) {
    die("Error fetching user data: " . mysqli_error($conn));
}
$user = mysqli_fetch_assoc($user_result);
$buyer_name = $user['full_name'];
if (empty($buyer_name)) {
    die("Error: Buyer name not found.");
}

$orders = mysqli_query($conn, "SELECT * FROM orders WHERE buyer_name='$buyer_name' ORDER BY order_date DESC");
if (!$orders) {
    die("Error executing query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #2e7d32;
            --danger: #e53935;
            --warning: #fb8c00;
            --light-bg: #f9f9f9;
            --text-dark: #333;
            --text-muted: #777;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f3;
            padding: 20px;
            color: var(--text-dark);
        }

        .container {
            max-width: 960px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #222;
        }

        .order {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            transition: 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .order:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .order p {
            margin: 6px 0;
            line-height: 1.6;
            font-size: 17px;
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }

        .status-pending {
            background-color: var(--warning);
            color: white;
        }

        .status-confirmed {
            background-color: var(--primary);
            color: white;
        }

        .status-cancelled {
            background-color: var(--danger);
            color: white;
        }

        .back-btn {
            display: inline-block;
            background-color: #1976d2;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: background 0.3s ease;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background-color: #1565c0;
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 22px;
            }

            .order {
                padding: 15px;
            }

            .status-pill {
                font-size: 16px;
            }

            .back-btn {
                font-size: 16px;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        <h2>My Orders</h2>

        <?php if (mysqli_num_rows($orders) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                <div class="order">
                    <p><strong>🛒 Product:</strong> <?= htmlspecialchars($row['product_name']) ?></p>
                    <p><strong>👨‍🌾 Farmer Name:</strong> <?= htmlspecialchars($row['farmer_name']) ?></p>
                    <p><strong>📦 Quantity:</strong> <?= $row['quantity'] ?></p>
                    <p><strong>💰 Total Price:</strong> ₹<?= $row['total_price'] ?></p>
                    <p><strong>📅 Ordered On:</strong> <?= date("d-m-Y h:i A", strtotime($row['order_date'])) ?></p>
                    <p><strong>Status:</strong>
                        <?php
                            $status = $row['status'];
                            if ($status == 'Pending') {
                                echo "<span class='status-pill status-pending'>Pending</span>";
                            } elseif ($status == 'Confirmed') {
                                echo "<span class='status-pill status-confirmed'>Confirmed</span>";
                            } elseif ($status == 'Cancelled') {
                                echo "<span class='status-pill status-cancelled'>Cancelled</span>";
                            }
                        ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; font-size: 18px;">No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
