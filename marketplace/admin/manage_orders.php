<?php
session_start();
include 'db.php';

// Pagination Setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Export logic
if (isset($_GET['export']) && $_GET['export'] === 'orders') {
    $export_filter = isset($_GET['status']) ? $_GET['status'] : '';
    $filter_export_sql = ($export_filter != '') ? "WHERE status='$export_filter'" : "";
    $export_query = "SELECT * FROM orders $filter_export_sql ORDER BY id DESC";
    $export_result = mysqli_query($conn, $export_query);

    header('Content-Type: text/csv');
    $filename = ($export_filter != '') ? strtolower($export_filter) . '_orders.csv' : 'all_orders.csv';
    header("Content-Disposition: attachment;filename=\"$filename\"");

    $output = fopen("php://output", "w");
    fputcsv($output, ['ID', 'Product', 'Buyer', 'Quantity', 'Total Price', 'Status']);

    while ($row = mysqli_fetch_assoc($export_result)) {
        fputcsv($output, [
            $row['id'],
            $row['product_name'],
            $row['buyer_name'],
            $row['quantity'],
            $row['total_price'],
            ucfirst($row['status'])
        ]);
    }
    fclose($output);
    exit();
}

// Handle status update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'confirm') {
        mysqli_query($conn, "UPDATE orders SET status='Confirmed' WHERE id=$id");
    } elseif ($action === 'cancel') {
        mysqli_query($conn, "UPDATE orders SET status='Cancelled' WHERE id=$id");
    }
}

// Get status filter
$filter = isset($_GET['status']) ? $_GET['status'] : '';
$filter_sql = ($filter != '') ? "WHERE status='$filter'" : "";

// Count orders by status
$count_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$count_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='Pending'"))['total'];
$count_confirmed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='Confirmed'"))['total'];
$count_cancelled = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='Cancelled'"))['total'];

// Get total for pagination (only filtered)
$total_query = "SELECT COUNT(*) as total FROM orders $filter_sql";
$total_result = mysqli_query($conn, $total_query);
$total_rows = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch orders (with limit and offset)
$query = "SELECT * FROM orders $filter_sql ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            margin: 0;
            padding: 40px;
        }
        .container {
            background: white;
            max-width: 1100px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 30px;
        }
        .btn-export {
            display: inline-block;
            text-decoration: none;
            background: #4CAF50;
            padding: 8px 16px;
            color: white;
            border-radius: 5px;
        }
        .filter {
            text-align: center;
            margin-bottom: 25px;
        }
        .filter a {
            margin: 0 12px;
            text-decoration: none;
            font-weight: bold;
            padding: 6px 14px;
            border-radius: 18px;
            background-color: #f0f0f0;
            color: #333;
        }
        .filter a.active, .filter a:hover {
            background-color: #4CAF50;
            color: white;
        }
        .badge {
            padding: 6px 10px;
            border-radius: 12px;
            font-size: 13px;
            color: white;
            display: inline-block;
        }
        .badge-pending { background-color: #f39c12; }
        .badge-confirmed { background-color: #27ae60; }
        .badge-cancelled { background-color: #e74c3c; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        a.btn {
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
        }
        .btn.confirm { background-color: #27ae60; }
        .btn.cancel { background-color: #e74c3c; }
        .btn.disabled {
            background-color: #ccc;
            pointer-events: none;
            color: #666;
        }
        .btn.confirm:hover { background-color: #1e8449; }
        .btn.cancel:hover { background-color: #c0392b; }
        .back {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            background: #1565c0;
            padding: 10px 20px;
            color: white;
            border-radius: 6px;
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
        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th {
                position: absolute;
                left: -9999px;
            }
            td {
                position: relative;
                padding-left: 50%;
                text-align: left;
                border: none;
                border-bottom: 1px solid #ccc;
            }
            td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Orders</h2>

    <div class="filter">
        <a href="manage_orders.php" class="<?= $filter == '' ? 'active' : '' ?>">All (<?= $count_all ?>)</a>
        <a href="manage_orders.php?status=Pending" class="<?= $filter == 'Pending' ? 'active' : '' ?>">Pending (<?= $count_pending ?>)</a>
        <a href="manage_orders.php?status=Confirmed" class="<?= $filter == 'Confirmed' ? 'active' : '' ?>">Confirmed (<?= $count_confirmed ?>)</a>
        <a href="manage_orders.php?status=Cancelled" class="<?= $filter == 'Cancelled' ? 'active' : '' ?>">Cancelled (<?= $count_cancelled ?>)</a>
    </div>

    <div style="text-align: right; margin-bottom: 20px;">
        <a href="manage_orders.php?export=orders<?= $filter ? '&status=' . $filter : '' ?>" class="btn-export">⬇ Export Orders (CSV)</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Buyer</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td data-label="ID"><?= $row['id'] ?></td>
                <td data-label="Product"><?= $row['product_name'] ?></td>
                <td data-label="Buyer"><?= $row['buyer_name'] ?></td>
                <td data-label="Qty"><?= $row['quantity'] ?></td>
                <td data-label="Price">₹<?= $row['total_price'] ?></td>
                <td data-label="Status">
                    <?php if ($row['status'] == 'Pending'): ?>
                        <span class="badge badge-pending">Pending</span>
                    <?php elseif ($row['status'] == 'Confirmed'): ?>
                        <span class="badge badge-confirmed">Confirmed</span>
                    <?php else: ?>
                        <span class="badge badge-cancelled">Cancelled</span>
                    <?php endif; ?>
                </td>
                <td data-label="Actions">
                    <?php if ($row['status'] == 'Pending'): ?>
                        <a href="?action=confirm&id=<?= $row['id'] ?>" class="btn confirm">✔ Confirm</a>
                        <a href="?action=cancel&id=<?= $row['id'] ?>" class="btn cancel">✖ Cancel</a>
                    <?php else: ?>
                        <span class="btn disabled">No Action</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
    <?php
    $prevPage = $page - 1;
    $nextPage = $page + 1;
    $baseLink = "manage_orders.php";
    $queryParams = [];

    if ($filter) {
        $queryParams[] = "status=" . urlencode($filter);
    }

    // Prev link
    if ($page > 1) {
        $queryParamsPrev = array_merge($queryParams, ["page=$prevPage"]);
        echo '<a href="' . $baseLink . '?' . implode('&', $queryParamsPrev) . '">« Prev</a>';
    } else {
        echo '<a class="disabled">« Prev</a>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        $queryParamsPage = array_merge($queryParams, ["page=$i"]);
        $activeClass = $i == $page ? 'active' : '';
        echo '<a class="' . $activeClass . '" href="' . $baseLink . '?' . implode('&', $queryParamsPage) . '">' . $i . '</a>';
    }

    // Next link
    if ($page < $total_pages) {
        $queryParamsNext = array_merge($queryParams, ["page=$nextPage"]);
        echo '<a href="' . $baseLink . '?' . implode('&', $queryParamsNext) . '">Next »</a>';
    } else {
        echo '<a class="disabled">Next »</a>';
    }
    ?>
</div>


    <div style="text-align:center;">
        <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
