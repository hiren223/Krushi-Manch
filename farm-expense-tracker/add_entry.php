<?php
session_start();
include 'db.php';  // Include the database connection

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

if (isset($_POST['submit'])) {
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $farm_name = $_POST['farm_name'];
    $currency = $_POST['currency'];

    // Check if the connection is valid
    if ($conn) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO add_entry (user_id, type, category, amount, description, date, farm_name, currency) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdssss", $user_id, $type, $category, $amount, $description, $date, $farm_name, $currency);

        // Execute the query and provide feedback
        if ($stmt->execute()) {
            echo "<script>alert('Entry added successfully!'); window.location.href = 'index.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Database connection failed.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Entry</title>
    <style>
        body {
            font-family: 'Adani Regular', sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .form-container:hover {
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #00796b;
            font-family: 'Adani Bold', sans-serif;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
            font-family: 'Adani Regular', sans-serif;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #00796b;
            background-color: #ffffff;
            box-shadow: 0 0 5px rgba(0, 121, 107, 0.5);
        }

        button {
            width: 100%;
            padding: 14px;
            background: #00796b;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
            font-family: 'Adani SemiBold', sans-serif;
        }

        button:hover {
            background-color: #004d40;
        }

        textarea {
            height: 100px;
        }

        select {
            background-color: #f9f9f9;
            font-size: 16px;
        }

        .form-container label {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
            display: block;
            font-family: 'Adani Regular', sans-serif;
        }

        .form-container input,
        .form-container select,
        .form-container textarea {
            font-size: 16px;
        }

        .form-container .submit-btn {
            background-color: #00796b;
            color: white;
            text-align: center;
            display: block;
            padding: 15px;
            font-size: 18px;
            border-radius: 8px;
            text-decoration: none;
        }

        .form-container .submit-btn:hover {
            background-color: #004d40;
        }
    </style>
</head>

<body>

<?php session_start(); require '../partials/nav.php'; ?>

    <div class="form-container">
        <h2>Add New Entry</h2>
        <form method="POST" action="">
            <div>
                <label for="type">Type</label>
                <select name="type" id="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>

            <div>
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Seeds">Seeds</option>
                    <option value="Fertilizers">Fertilizers</option>
                    <option value="Tools">Tools</option>
                    <option value="Labor">Labor</option>
                    <option value="Machinery">Machinery</option>
                    <option value="Pesticides">Pesticides</option>
                    <option value="Irrigation">Irrigation</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Sales">Sales</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="amount">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" placeholder="Amount" required>
            </div>

            <div>
                <label for="description">Description (optional)</label>
                <textarea name="description" id="description" placeholder="Enter any description here (optional)"></textarea>
            </div>

            <div>
                <label for="date">Date</label>
                <input type="date" name="date" id="date" required>
            </div>

            <div>
                <label for="farm_name">Farm Name (optional)</label>
                <input type="text" name="farm_name" id="farm_name" placeholder="Farm Name (optional)">
            </div>

            <div>
                <label for="currency">Currency</label>
                <select name="currency" id="currency" required>
                    <option value="INR" selected>INR (₹)</option>
                    <option value="USD">USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="GBP">GBP (£)</option>
                    <option value="JPY">JPY (¥)</option>
                </select>
            </div>

            <button type="submit" name="submit">Add Entry</button>
        </form>
    </div>

</body>

</html>
