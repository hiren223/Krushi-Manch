<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $update = "UPDATE orders SET status='Confirmed' WHERE id=$order_id";
    if (mysqli_query($conn, $update)) {
        header("Location: view_orders.php");
        exit();
    } else {
        echo "Error updating order status: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
