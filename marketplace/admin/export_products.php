<?php
include 'db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="products.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['Product ID', 'Product Name', 'Farmer Name', 'Price', 'Quantity', 'Status']);

$result = mysqli_query($conn, "SELECT * FROM products");

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['product_name'],
        $row['farmer_name'],
        $row['price'],
        $row['quantity'],
        ucfirst($row['status'])
    ]);
}

fclose($output);
exit();
?>
