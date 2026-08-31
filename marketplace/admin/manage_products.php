<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Handle delete product action
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id = $product_id");
    header("Location: manage_products.php");
    exit();
}

// Handle hide/unhide product
if (isset($_GET['hide'])) {
    $product_id = intval($_GET['hide']);
    mysqli_query($conn, "UPDATE products SET status = 'hidden' WHERE id = $product_id");
    header("Location: manage_products.php");
    exit();
}

if (isset($_GET['unhide'])) {
    $product_id = intval($_GET['unhide']);
    mysqli_query($conn, "UPDATE products SET status = 'visible' WHERE id = $product_id");
    header("Location: manage_products.php");
    exit();
}

// Pagination setup
$limit = 5; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of products
$totalProductsResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProductsRow = mysqli_fetch_assoc($totalProductsResult);
$totalProducts = $totalProductsRow['total'];
$totalPages = ceil($totalProducts / $limit);

// Fetch products for the current page
$query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 40px;
        }
        .container {
            background: white;
            max-width: 1100px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 14px;
            color: white;
        }
        .visible {
            background-color: #27ae60;
        }
        .hidden {
            background-color: #f39c12;
        }
        a.btn {
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
            display: inline-block;
        }
        a.delete-btn {
            background-color: #e74c3c;
        }
        a.delete-btn:hover {
            background-color: #c0392b;
        }
        a.hide-btn {
            background-color: #f39c12;
        }
        a.hide-btn:hover {
            background-color: #d68910;
        }
        a.unhide-btn {
            background-color: #27ae60;
        }
        a.unhide-btn:hover {
            background-color: #1e8449;
        }
        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #1565c0;
            padding: 8px 16px;
            color: white;
            border-radius: 5px;
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
        /* Mobile responsive styles */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
            .container {
                padding: 20px;
            }
            .status-badge {
                font-size: 12px;
            }
            .btn {
                font-size: 14px;
                padding: 5px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Products</h2>
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="export_products.php?type=products" class="back" style="background-color: #4CAF50;">⬇ Export Products</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Farmer</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['farmer_name']) ?></td>
                    <td>₹<?= $row['price'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>
                        <span class="status-badge <?= $row['status'] === 'visible' ? 'visible' : 'hidden' ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'visible'): ?>
                            <a class="btn hide-btn" href="?hide=<?= $row['id'] ?>" onclick="return confirm('Hide this product?');">Hide</a>
                        <?php else: ?>
                            <a class="btn unhide-btn" href="?unhide=<?= $row['id'] ?>" onclick="return confirm('Unhide this product?');">Unhide</a>
                        <?php endif; ?>
                        <a class="btn delete-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
            <div class="pagination">
                <!-- Prev button -->
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>">« Prev</a>
                <?php else: ?>
                    <a style="pointer-events: none; opacity: 0.5;">« Prev</a>
                <?php endif; ?>
            
                <!-- Page numbers -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            
                <!-- Next button -->
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>">Next »</a>
                <?php else: ?>
                    <a style="pointer-events: none; opacity: 0.5;">Next »</a>
                <?php endif; ?>
            </div>


        <div style="text-align:center;">
            <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
