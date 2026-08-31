<?php
session_start();
include 'db.php';

if (!isset($_SESSION['Loggedin']) || ($_SESSION['Loggedin'] != true)) {
    header("location: ../index.php");
    exit;
}

// Get user_id from username
$username = $_SESSION['username'];
$user_sql = "SELECT Id FROM user WHERE Username = '$username'";
$user_result = $conn->query($user_sql);
if ($user_result->num_rows > 0) {
    $user_row = $user_result->fetch_assoc();
    $user_id = $user_row['Id'];
} else {
    die("User not found.");
}

// Pagination setup
$limit = 5; // Entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Filter handling
$conditions = ["user_id = $user_id"];

if (!empty($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $conditions[] = "type = '$type'";
}
if (!empty($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);
    $conditions[] = "category = '$category'";
}
if (!empty($_GET['currency'])) {
    $currency = $conn->real_escape_string($_GET['currency']);
    $conditions[] = "currency = '$currency'";
}

$where = "WHERE " . implode(" AND ", $conditions);
$sql = "SELECT * FROM add_entry $where ORDER BY date DESC LIMIT $start, $limit";
$result = $conn->query($sql);

// Count total rows for pagination
$countSql = "SELECT COUNT(*) AS total FROM add_entry $where";
$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Farm Expense Tracker</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Adani Regular', sans-serif;
      background-color: #f8f9fa;
      margin: 20px;
    }
    h2 {
      text-align: center;
      font-family: 'Adani Bold', sans-serif;
    }
    form {
      text-align: center;
      margin-bottom: 20px;
    }
    select, button {
      padding: 10px;
      margin: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-family: 'Adani Regular', sans-serif;
    }
    table {
      width: 90%;
      margin: auto;
      border-collapse: collapse;
      background-color: #fff;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
      font-family: 'Adani Regular', sans-serif;
    }
    th {
      background-color: #00796B;
      color: white;
      font-family: 'Adani SemiBold', sans-serif;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    .add-btn {
      display: block;
      width: 200px;
      margin: 20px auto;
      background-color: #28a745;
      color: white;
      text-align: center;
      padding: 12px;
      text-decoration: none;
      border-radius: 6px;
      font-family: 'Adani SemiBold', sans-serif;
    }

    .pagination {
      display: flex;
      justify-content: center;
      margin: 30px 0;
      gap: 6px;
    }
    .pagination a, .pagination span {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      text-decoration: none;
      color: #00796B;
      background: white;
      transition: all 0.3s;
      font-family: 'Adani Regular', sans-serif;
    }
    .pagination a:hover {
      background: #00796B;
      color: white;
    }
    .pagination .active {
      background: #00796B;
      color: white;
      border-color: #00796B;
    }
    .pagination .disabled {
      opacity: 0.5;
      pointer-events: none;
    }

    /* Home Nav Styles */
    .krushi-nav {
      background: linear-gradient(135deg, #00796B 0%, #00897B 100%);
      padding: 15px 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      margin: -20px -20px 20px -20px;
    }
    .krushi-nav-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
    }
    .krushi-nav-home {
      color: white;
      text-decoration: none;
      font-size: 18px;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 6px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .krushi-nav-home:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: translateX(2px);
    }
    .krushi-nav-title {
      color: white;
      font-size: 16px;
      font-weight: 500;
    }
  </style>
</head>
<body>

<!-- Home Nav -->


<h2>Farm Expense Tracker</h2>

<!-- FILTER FORM -->
<form method="GET">
  <select name="type">
    <option value="">-- All Types --</option>
    <option value="income" <?= ($_GET['type'] ?? '') == 'income' ? 'selected' : '' ?>>Income</option>
    <option value="expense" <?= ($_GET['type'] ?? '') == 'expense' ? 'selected' : '' ?>>Expense</option>
  </select>

  <select name="category">
    <option value="">-- All Categories --</option>
    <option value="Seeds" <?= ($_GET['category'] ?? '') == 'Seeds' ? 'selected' : '' ?>>Seeds</option>
    <option value="Fertilizers" <?= ($_GET['category'] ?? '') == 'Fertilizers' ? 'selected' : '' ?>>Fertilizers</option>
    <option value="Tools" <?= ($_GET['category'] ?? '') == 'Tools' ? 'selected' : '' ?>>Tools</option>
    <option value="Labor" <?= ($_GET['category'] ?? '') == 'Labor' ? 'selected' : '' ?>>Labor</option>
    <option value="Sales" <?= ($_GET['category'] ?? '') == 'Sales' ? 'selected' : '' ?>>Sales</option>
  </select>

  <select name="currency">
    <option value="">-- All Currencies --</option>
    <option value="INR" <?= ($_GET['currency'] ?? '') == 'INR' ? 'selected' : '' ?>>INR (₹)</option>
    <option value="USD" <?= ($_GET['currency'] ?? '') == 'USD' ? 'selected' : '' ?>>USD ($)</option>
    <option value="EUR" <?= ($_GET['currency'] ?? '') == 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
  </select>

  <button type="submit">Filter</button>
</form>

<div style="text-align: center; margin-top: 20px;">
  <a href="summary.php" style="background-color: #007BFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 16px;">View Monthly Summary</a>
</div>

<a class="add-btn" href="add_entry.php">+ Add New Entry</a>

<!-- DISPLAY TABLE -->
<table>
  <tr>
    <th>Date</th>
    <th>Type</th>
    <th>Category</th>
    <th>Description</th>
    <th>Amount</th>
    <th>Currency</th>
  </tr>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['date'] ?></td>
        <td><?= ucfirst($row['type']) ?></td>
        <td><?= $row['category'] ?></td>
        <td><?= $row['description'] ?></td>
        <td><?= number_format($row['amount'], 2) ?></td>
        <td><?= $row['currency'] ?></td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr>
      <td colspan="6">No entries found.</td>
    </tr>
  <?php endif; ?>
</table>

<!-- PAGINATION -->
<div class="pagination">
  <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="<?= ($page <= 1) ? 'disabled' : '' ?>">&lt;</a>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
  <?php endfor; ?>

  <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="<?= ($page >= $totalPages) ? 'disabled' : '' ?>">&gt;</a>
</div>

</body>
</html>
