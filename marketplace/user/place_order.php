<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] != 'buyer') {
    header("Location: login.php");
    exit();
}

$buyer_email = $_SESSION['user_email'];
$buyer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE email='$buyer_email' OR mobile='$buyer_email'"));
$buyer_name = $buyer['full_name'];

// Get product info from URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'"));

    if (!$product) {
        echo "Product not found.";
        exit();
    }
} else {
    echo "No product selected.";
    exit();
}

// When buyer submits the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $product_name = $product['product_name'];
    $farmer_name = $product['farmer_name'];
    $price_per_item = $product['price'];
    $total_price = $price_per_item * $quantity;

    if ($quantity > $product['quantity']) {
        echo "<script>alert('Not enough quantity available.'); window.location='buyer_dashboard.php';</script>";
        exit();
    }

    // Insert order
    $query = "INSERT INTO orders (product_id, product_name, buyer_name, farmer_name, quantity, total_price, order_date, status) 
              VALUES ('$product_id', '$product_name', '$buyer_name', '$farmer_name', '$quantity', '$total_price', NOW(), 'Pending')";

    if (mysqli_query($conn, $query)) {
        // Update product quantity
        $new_quantity = $product['quantity'] - $quantity;
        mysqli_query($conn, "UPDATE products SET quantity = '$new_quantity' WHERE id = '$product_id'");

        echo "<script>alert('Order placed successfully!'); window.location='dashboard.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            padding: 40px;
        }

        .order-container {
            max-width: 500px;
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

        p {
            margin: 10px 0;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
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

        /* Responsive styles for mobile only */
        @media screen and (max-width: 480px) {
            body {
                padding: 15px;
            }

            .order-container {
                width: 95%;
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }

            p {
                font-size: 13px;
            }

            label {
                font-size: 14px;
            }

            input[type="number"] {
                font-size: 14px;
                padding: 8px;
                width: 95%;
            }

            button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="order-container">
    <h2>Order Product</h2>
    <p><strong>Product:</strong> <?php echo $product['product_name']; ?></p>
    <p><strong>Farmer:</strong> <?php echo $product['farmer_name']; ?></p>
    <p><strong>Address:</strong> <?php echo $product['address']; ?></p>
    <p><strong>Available Quantity:</strong> <?php echo $product['quantity']; ?></p>
    <p><strong>Price per unit (20kg):</strong> ₹<?php echo $product['price']; ?></p>

    <form method="POST">
        <label>Enter Quantity:</label>
        <input type="number" name="quantity" min="1" max="<?php echo $product['quantity']; ?>" required>
        <button type="submit">Place Order</button>
    </form>
    <div style="text-align:center;">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>
