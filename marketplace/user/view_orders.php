<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

$farmer_email = $_SESSION['user_email'];
$farmer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE email='$farmer_email' OR mobile='$farmer_email'"));
$farmer_name = $farmer['full_name'];

if (isset($_POST['confirm'])) {
    $order_id = $_POST['order_id'];
    mysqli_query($conn, "UPDATE orders SET status='Confirmed' WHERE id='$order_id'");
}

if (isset($_POST['cancel'])) {
    $order_id = $_POST['order_id'];
    $order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id'"));
    if ($order) {
        $product_id = $order['product_id'];
        $quantity = $order['quantity'];
        mysqli_query($conn, "UPDATE orders SET status='Cancelled' WHERE id='$order_id'");
        mysqli_query($conn, "UPDATE products SET quantity = quantity + $quantity WHERE id = $product_id");
    }
}

// Pagination Setup
$limit = 5; // records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE farmer_name = '$farmer_name'");
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

$query = "SELECT * FROM orders WHERE farmer_name = '$farmer_name' ORDER BY order_date DESC LIMIT $start_from, $limit";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Received Orders</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #eef2f3;
        padding: 20px;
        margin: 0;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ccc;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    .btn-confirm, .btn-cancel {
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        margin: 2px;
    }

    .btn-confirm {
        background-color: #4CAF50;
    }

    .btn-cancel {
        background-color: #e74c3c;
    }

    .btn-confirm:hover {
        background-color: #45a049;
    }

    .btn-cancel:hover {
        background-color: #c0392b;
    }

    .status-confirmed {
        color: green;
        font-weight: bold;
    }

    .status-pending {
        color: orange;
        font-weight: bold;
    }

    .status-cancelled {
        color: red;
        font-weight: bold;
    }

    .back-btn {
        display: inline-block;
        text-align: center;
        margin-top: 25px;
        text-decoration: none;
        background: #1976d2;
        padding: 10px 20px;
        color: white;
        border-radius: 6px;
    }

    .back-btn:hover {
        background: #1565c0;
    }

    .pagination {
        text-align: center;
        margin-top: 40px;
    }

    .pagination a {
        padding: 8px 14px;
        margin: 0 4px;
        text-decoration: none;
        background-color: #4CAF50;
        color: white;
        border-radius: 4px;
    }

    .pagination a.active {
        background-color: #333;
    }

    .pagination a.disabled {
        background-color: #ccc;
        pointer-events: none;
    }

    /* Responsive Styles */
    @media screen and (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
            width: 100%;
        }

        thead {
            display: none;
        }

        tr {
            background: #fff;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 15px;
            box-sizing: border-box;
        }

        td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 10px 0;
            position: relative;
            font-size: 16px;
            border-bottom: 1px solid #eee;
        }

        td:last-child {
            border-bottom: none;
        }

        td::before {
            content: attr(data-label);
            font-weight: bold;
            flex-basis: 40%;
            color: #333;
        }

        td[data-label="Action"] {
            flex-direction: column;
            align-items: stretch;
        }

        td[data-label="Action"] form {
            width: 100%;
        }

        td[data-label="Action"] .btn-confirm,
        td[data-label="Action"] .btn-cancel {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }
    }
</style>

    
</head>
<body>
<div class="container">
    <h2>Received Orders from Buyers</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Buyer Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td data-label="Product Name"><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td data-label="Buyer Name"><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                    <td data-label="Quantity"><?php echo $row['quantity']; ?></td>
                    <td data-label="Total Price">₹<?php echo $row['total_price']; ?></td>
                    <td data-label="Status">
                        <?php
                        if ($row['status'] === 'Pending') {
                            echo "<span class='status-pending'>Pending</span>";
                        } elseif ($row['status'] === 'Confirmed') {
                            echo "<span class='status-confirmed'>Confirmed</span>";
                        } elseif ($row['status'] === 'Cancelled') {
                            echo "<span class='status-cancelled'>Cancelled</span>";
                        }
                        ?>
                    </td>
                    <td data-label="Order Date"><?php echo date("d-m-Y H:i", strtotime($row['order_date'])); ?></td>
                    <td data-label="Action">
                        <?php if ($row['status'] === 'Pending'): ?>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="confirm" class="btn-confirm">Confirm</button>
                            </form>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="cancel" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</button>
                            </form>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="pagination">
    <a href="?page=<?php echo max(1, $page - 1); ?>" class="<?php echo ($page == 1) ? 'disabled' : ''; ?>">« Prev</a>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <a href="?page=<?php echo min($total_pages, $page + 1); ?>" class="<?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">Next »</a>
</div>

    <?php else: ?>
        <p style="text-align:center;">No orders received yet.</p>
    <?php endif; ?>

    <div style="text-align:center;">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>
