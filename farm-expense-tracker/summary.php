<?php
session_start();
if (!isset($_SESSION['Loggedin']) || ($_SESSION['Loggedin'] != true)) {
    header("location: ../index.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "krushi-manch");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

// Initialize variables for chart data
$category_data = [];
$total_income = 0;
$total_expense = 0;

// Initialize filter array
$filter = ["user_id = $user_id"];

// Check if month and year are set and add them to the filter
if (isset($_GET['month']) && !empty($_GET['month'])) {
    $month = $_GET['month'];
    $filter[] = "MONTH(date) = $month";
}

if (isset($_GET['year']) && !empty($_GET['year'])) {
    $year = $_GET['year'];
    $filter[] = "YEAR(date) = $year";
}

// Prepare the base SQL query
$sql = "SELECT * FROM add_entry";

// Add the filters to the SQL query if they exist
if (count($filter) > 0) {
    $sql .= " WHERE " . implode(" AND ", $filter);
}

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Fetch expenses and income by category
while ($row = $result->fetch_assoc()) {
    $category = $row['category'];
    $amount = $row['amount'];
    if ($row['type'] == 'income') {
        $total_income += $amount;
    } else {
        $total_expense += $amount;
    }

    // Group data by category
    if (!isset($category_data[$category])) {
        $category_data[$category] = 0;
    }
    $category_data[$category] += $amount;
}

// Graph data for filtered results
$graph_data = [];
if (isset($_GET['month']) || isset($_GET['year'])) {
    $result = $conn->query($sql);

    // Re-fetch and categorize the data for the graph
    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        $amount = $row['amount'];
        if (!isset($graph_data[$category])) {
            $graph_data[$category] = 0;
        }
        $graph_data[$category] += $amount;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Expense Tracker - Summary</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Body Style */
body {
    font-family: 'Adani Regular', sans-serif;
    background-color: #f1f1f1;
    margin: 0;
    padding: 0;
}

/* Container for the whole content */
.container {
    display: flex;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 20px auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

/* Left Section: Summary and Filters */
.left-section {
    flex: 1;
    padding-right: 20px;
    border-right: 2px solid #ddd;
}

h2 {
    text-align: center;
    color: #00796b;
    margin-bottom: 30px;
    font-size: 2rem;
}

/* Form styling */
form {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 30px;
}

/* Styling for select inputs and button */
select, button {
    padding: 10px 15px;
    border-radius: 5px;
    border: 1px solid #00796b;
    font-size: 1rem;
    background-color: #00796b;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Hover effects for button and select */
select:hover, button:hover {
    background-color: #004d40;
}

/* Summary Container */
.summary-container {
    margin-top: 40px;
    text-align: center;
}

.summary-container h3 {
    font-size: 1.5rem;
    color: #333;
}

.summary-container p {
    font-size: 1.1rem;
    color: #555;
    margin: 10px 0;
}

.summary-container ul {
    list-style: none;
    padding: 0;
    text-align: left;
    margin-top: 20px;
}

.summary-container li {
    font-size: 1.1rem;
    color: #555;
    margin-bottom: 10px;
}

.summary-container li strong {
    color: #00796b;
}

/* Right Section: Graphs */
.right-section {
    flex: 1;
    padding-left: 20px;
}

.graph-container {
    margin-top: 40px;
    text-align: center;
}

.graph-container canvas {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
}

/* Button for submitting form */
button[type="submit"] {
    font-size: 1rem;
    background-color: #00796b;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #004d40;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        padding: 10px;
    }

    .left-section {
        border-right: none;
        padding-right: 0;
        margin-bottom: 20px;
    }

    .right-section {
        margin-top: 20px;
        padding-left: 0;
    }

    h2 {
        font-size: 1.8rem;
    }

    form {
        flex-direction: column;
        align-items: center;
    }

    select, button {
        width: 100%;
        max-width: 200px;
        margin-bottom: 10px;
    }

    .summary-container {
        margin-top: 20px;
    }

    .summary-container p {
        font-size: 1rem;
    }

    .summary-container li {
        font-size: 1rem;
    }

    .graph-container canvas {
        max-width: 100%;
    }
}

/* Back to Dashboard Button */
.back-btn-container {
    text-align: center;
    margin-top: 30px;
}

.back-btn {
    background-color: #00796b;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 200px;
    margin: 0 auto;
}

.back-btn:hover {
    background-color: #004d40;
}

/* Responsive Design for Back Button */
@media (max-width: 768px) {
    .back-btn {
        width: 100%;
        max-width: 250px;
    }
}

    </style>
</head>
<body>

<?php session_start(); require '../partials/nav.php'; ?>

    <div class="container">
        <!-- Left Section: Filters and Summary -->
        <div class="left-section">
            <h2>Farm Expense Tracker - Summary</h2>

            <!-- Form to select month or year -->
            <div style="text-align: center;">
                <form method="GET">
                    <select name="month">
                        <option value="">-- Select Month --</option>
                        <option value="1" <?= isset($_GET['month']) && $_GET['month'] == '1' ? 'selected' : '' ?>>January</option>
                        <option value="2" <?= isset($_GET['month']) && $_GET['month'] == '2' ? 'selected' : '' ?>>February</option>
                        <option value="3" <?= isset($_GET['month']) && $_GET['month'] == '3' ? 'selected' : '' ?>>March</option>
                        <option value="4" <?= isset($_GET['month']) && $_GET['month'] == '4' ? 'selected' : '' ?>>April</option>
                        <option value="5" <?= isset($_GET['month']) && $_GET['month'] == '5' ? 'selected' : '' ?>>May</option>
                        <option value="6" <?= isset($_GET['month']) && $_GET['month'] == '6' ? 'selected' : '' ?>>June</option>
                        <option value="7" <?= isset($_GET['month']) && $_GET['month'] == '7' ? 'selected' : '' ?>>July</option>
                        <option value="8" <?= isset($_GET['month']) && $_GET['month'] == '8' ? 'selected' : '' ?>>August</option>
                        <option value="9" <?= isset($_GET['month']) && $_GET['month'] == '9' ? 'selected' : '' ?>>September</option>
                        <option value="10" <?= isset($_GET['month']) && $_GET['month'] == '10' ? 'selected' : '' ?>>October</option>
                        <option value="11" <?= isset($_GET['month']) && $_GET['month'] == '11' ? 'selected' : '' ?>>November</option>
                        <option value="12" <?= isset($_GET['month']) && $_GET['month'] == '12' ? 'selected' : '' ?>>December</option>
                    </select>

                    <select name="year">
                        <option value="">-- Select Year --</option>
                        <option value="2025" <?= isset($_GET['year']) && $_GET['year'] == '2025' ? 'selected' : '' ?>>2025</option>
                        <option value="2024" <?= isset($_GET['year']) && $_GET['year'] == '2024' ? 'selected' : '' ?>>2024</option>
                        <option value="2023" <?= isset($_GET['year']) && $_GET['year'] == '2023' ? 'selected' : '' ?>>2023</option>
                        <option value="2022" <?= isset($_GET['year']) && $_GET['year'] == '2022' ? 'selected' : '' ?>>2022</option>
                    </select>

                    <button type="submit">Filter</button>
                    <button type="submit" name="graph">Show Graph</button>
                </form>
            </div>

            <!-- Summary of Expenses -->
            <div class="summary-container">
                <h3>Summary</h3>
                <ul>
                    <li><strong>Total Income:</strong> ₹<?= number_format($total_income, 2); ?></li>
                    <li><strong>Total Expenses:</strong> ₹<?= number_format($total_expense, 2); ?></li>
                    <li><strong>Net Balance:</strong> ₹<?= number_format($total_income - $total_expense, 2); ?></li>
                </ul>
            </div>

        </div>

        <!-- Right Section: Graphs -->
        <div class="right-section">
            <div class="graph-container">
                <!-- Category Graph -->
                <canvas id="categoryChart"></canvas>
                <script>
                    var ctx1 = document.getElementById('categoryChart').getContext('2d');
                    var categoryChart = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode(array_keys($category_data)); ?>,
                            datasets: [{
                                label: 'Expense by Category',
                                data: <?php echo json_encode(array_values($category_data)); ?>,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>

                <!-- Income vs Expense Pie Chart -->
                <canvas id="incomeExpenseComparison" width="723" height="723" style="display: block; box-sizing: border-box; height: 250px; width: 250px;"></canvas>
 
                <script>
                    var ctx2 = document.getElementById('incomeExpenseComparison').getContext('2d');
                    var incomeExpenseChart = new Chart(ctx2, {
                        type: 'pie',
                        data: {
                            labels: ['Income', 'Expense'],
                            datasets: [{
                                data: [<?php echo $total_income; ?>, <?php echo $total_expense; ?>],
                                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ₹' + tooltipItem.raw.toFixed(2);
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
