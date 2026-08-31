<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
include 'db.php';

// Block/Unblock/Delete actions
if (isset($_GET['block_id'])) {
    $id = $_GET['block_id'];
    mysqli_query($conn, "UPDATE users SET status='blocked' WHERE id=$id");
}
if (isset($_GET['unblock_id'])) {
    $id = $_GET['unblock_id'];
    mysqli_query($conn, "UPDATE users SET status='active' WHERE id=$id");
}
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
}

// Count for filters
$allCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$farmersCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='farmer'"));
$buyersCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='buyer'"));

// Filtering and pagination
$filter = isset($_GET['role']) ? $_GET['role'] : '';
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$baseQuery = "FROM users";
if ($filter === 'farmer' || $filter === 'buyer') {
    $baseQuery .= " WHERE role='$filter'";
}

// Total filtered count for pagination
$totalFiltered = mysqli_num_rows(mysqli_query($conn, "SELECT * $baseQuery"));
$totalPages = ceil($totalFiltered / $limit);

$query = "SELECT * $baseQuery ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            padding: 40px;
        }
        .container {
            max-width: 1100px;
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
        .filter {
            text-align: center;
            margin-bottom: 25px;
        }
        .filter a {
            margin: 0 12px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            padding: 6px 14px;
            border-radius: 18px;
            background-color: #f0f0f0;
        }
        .filter a.active, .filter a:hover {
            background: #4CAF50;
            color: white;
        }
        .export-btn {
            display: inline-block;
            /* margin-top: 20px; */
            text-decoration: none;
            background: #4CAF50;
            padding: 8px 16px;
            color: white;
            border-radius: 5px;
            
            right: 70%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: bold;
        }
        .active-badge {
            background-color: #2ecc71;
            color: white;
        }
        .blocked-badge {
            background-color: #f1c40f;
            color: white;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-size: 13px;
            margin-right: 5px;
            display: inline-block;
        }
        .btn.block { background-color: #f39c12; }
        .btn.unblock { background-color: #2ecc71; }
        .btn.delete { background-color: #e74c3c; }
        .btn:hover { opacity: 0.9; }
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
        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #1565c0;
            padding: 8px 16px;
            color: white;
            border-radius: 5px;
        }
        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr { display: block; }
            th { display: none; }
            td {
                position: relative;
                padding-left: 50%;
                border: none;
                border-bottom: 1px solid #ddd;
            }
            td:before {
                position: absolute;
                top: 10px;
                left: 10px;
                width: 45%;
                font-weight: bold;
            }
            td:nth-of-type(1):before { content: "ID"; }
            td:nth-of-type(2):before { content: "Full Name"; }
            td:nth-of-type(3):before { content: "Mobile"; }
            td:nth-of-type(4):before { content: "Email"; }
            td:nth-of-type(5):before { content: "Role"; }
            td:nth-of-type(6):before { content: "Status"; }
            td:nth-of-type(7):before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>

        <div class="filter">
            <a href="manage_users.php" class="<?= $filter == '' ? 'active' : ''?>">All (<?= $allCount ?>)</a>
            <a href="manage_users.php?role=farmer" class="<?= $filter == 'farmer' ? 'active' : ''?>">Farmers (<?= $farmersCount ?>)</a>
            <a href="manage_users.php?role=buyer" class="<?= $filter == 'buyer' ? 'active' : ''?>">Buyers (<?= $buyersCount ?>)</a>
        </div>
        <div style="text-align: right; margin-bottom: 20px;">
            <a class="export-btn" href="export_users.php?role=<?= $filter ? $filter : 'all' ?>">⬇ Export <?= $filter ? ucfirst($filter) : 'All Users' ?> CSV</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= $row['mobile'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= ucfirst($row['role']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'active') { ?>
                                <span class="badge active-badge">Active</span>
                            <?php } else { ?>
                                <span class="badge blocked-badge">Blocked</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'active') { ?>
                                <a href="?block_id=<?= $row['id'] ?>" class="btn block">Block</a>
                            <?php } else { ?>
                                <a href="?unblock_id=<?= $row['id'] ?>" class="btn unblock">Unblock</a>
                            <?php } ?>
                            <a href="?delete_id=<?= $row['id'] ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php
            $prevPage = $page - 1;
            $nextPage = $page + 1;
            $baseLink = "manage_users.php";
            $baseLink .= $filter ? "?role=$filter" : "";
            $paramJoiner = $filter ? "&" : "?";
            ?>

            <a class="<?= $page <= 1 ? 'disabled' : '' ?>" href="<?= $page > 1 ? "$baseLink{$paramJoiner}page=$prevPage" : '#' ?>">« Prev</a>

            <?php for ($i = 1; $i <= $totalPages; $i++) {
                $link = "$baseLink{$paramJoiner}page=$i";
            ?>
                <a class="<?= $page == $i ? 'active' : '' ?>" href="<?= $link ?>"><?= $i ?></a>
            <?php } ?>

            <a class="<?= $page >= $totalPages ? 'disabled' : '' ?>" href="<?= $page < $totalPages ? "$baseLink{$paramJoiner}page=$nextPage" : '#' ?>">Next »</a>
        </div>

        <div style="text-align:center;">
            <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
