<?php
include 'db.php';

// Get filter status from URL
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Set filename and query based on filter
switch ($status) {
    case 'pending':
        $filename = 'pending_orders.csv';
        $query = "SELECT * FROM orders WHERE status = 'pending'";
        break;
    case 'confirmed':
        $filename = 'confirmed_orders.csv';
        $query = "SELECT * FROM orders WHERE status = 'confirmed'";
        break;
    case 'cancelled':
        $filename = 'cancelled_orders.csv';
        $query = "SELECT * FROM orders WHERE status = 'cancelled'";
        break;
    default:
        $filename = 'all_orders.csv';
        $query = "SELECT * FROM orders";
        break;
}

// Set headers for CSV download
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$filename\"");

// Create CSV output
$output = fopen("php://output", "w");

// Write CSV headers
fputcsv($output, ['Order ID', 'Buyer Name', 'Product Name', 'Quantity', 'Total Price', 'Order Status']);

// Fetch and write rows
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['buyer_name'],
        $row['product_name'],
        $row['quantity'],
        $row['total_price'],
        ucfirst($row['status'])
    ]);
}

fclose($output);
exit();
?>
