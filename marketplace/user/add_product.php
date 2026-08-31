<?php
session_start();
include 'db.php'; // Include your database connection

// Check if the user is a farmer and logged in
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

$farmer_email = $_SESSION['user_email'];
$farmer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE email='$farmer_email' OR mobile='$farmer_email'"));
$farmer_name = $farmer['full_name']; // Get farmer's name

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and escape special characters
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Insert product into database
    $query = "INSERT INTO products (farmer_name, product_name, address, quantity, price) 
              VALUES ('$farmer_name', '$product_name', '$address', '$quantity', '$price')";

    if (mysqli_query($conn, $query)) {
        // Product added successfully, show a success message
        echo "<script>
                alert('Product added successfully!');
                window.location.href = 'dashboard.php'; // Redirect to dashboard
              </script>";
    } else {
        echo "<script>alert('Error adding product. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product - Krushi Manch</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e8f5e9, #ffffff);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .form-container {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 16px;
      width: 100%;
      max-width: 520px;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #2f855a;
      font-size: 28px;
    }

    .form-group {
      margin-bottom: 22px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #2d3748;
      font-size: 15px;
    }

    input[type="text"],
    input[type="number"],
    select {
      width: 100%;
      padding: 14px 16px;
      border: 1px solid #cbd5e0;
      border-radius: 10px;
      font-size: 15px;
      background-color: #f7fafc;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus,
    select:focus {
      border-color: #38a169;
      box-shadow: 0 0 0 3px rgba(56, 161, 105, 0.3);
      outline: none;
      background-color: #ffffff;
    }

    select {
      appearance: none;
      background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='16' viewBox='0 0 24 24' width='16' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 16px;
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #2f855a;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    button:hover {
      background-color: #276749;
      transform: translateY(-1px);
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

    @media (max-width: 500px) {
      .form-container {
        padding: 30px 20px;
      }

      .form-container h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Add New Product</h2>
    <form method="POST">

      <div class="form-group">
        <label for="product_name">Product Name</label>
        <select name="product_name" id="product_name" required>
           <option value="" disabled selected>Select crop name</option>
           <option value="Wheat (ઘઉં)">Wheat (ઘઉં)</option>
           <option value="Rice (ચોખા)">Rice (ચોખા)</option>
           <option value="Cotton (કપાસ)">Cotton (કપાસ)</option>
           <option value="Sugarcane (શેરડી)">Sugarcane (શેરડી)</option>
           <option value="Groundnut (મગફળી)">Groundnut (મગફળી)</option>
           <option value="Maize (મકાઈ)">Maize (મકાઈ)</option>
           <option value="Bajra (બાજરી)">Bajra (બાજરી)</option>
           <option value="Jowar (જુવાર)">Jowar (જુવાર)</option>
           <option value="Turmeric (હળદર)">Turmeric (હળદર)</option>
           <option value="Onion (ડુંગળી)">Onion (ડુંગળી)</option>
           <option value="Potato (બટાકા)">Potato (બટાકા)</option>
           <option value="Tomato (ટમેટાં)">Tomato (ટમેટાં)</option>
        </select>

      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" placeholder="Enter your address" required>
      </div>

      <div class="form-group">
        <label for="quantity">Quantity (20KG)</label>
        <input type="number" name="quantity" id="quantity" placeholder="Enter quantity (20kg)" required>
      </div>

      <div class="form-group">
        <label for="price">Price</label>
        <input type="number" name="price" id="price" placeholder="Enter price (₹)" required>
      </div>

      <button type="submit">Add Product</button>
      <div style="text-align:center;">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
      </div>
    </form>
  </div>
</body>
</html>
