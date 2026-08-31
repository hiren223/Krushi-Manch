<?php
include 'db.php';

// Get the role from the query string (sent via GET method from the form in Manage Users page)
$role = isset($_GET['role']) ? $_GET['role'] : 'all';

// Set the filename based on the role
$filename = "all_users.csv"; 

// Modify query and filename based on the role
if ($role === 'farmer') {
    $query = "SELECT * FROM users WHERE role = 'farmer'";
    $filename = "farmer.csv";
} elseif ($role === 'buyer') {
    $query = "SELECT * FROM users WHERE role = 'buyer'";
    $filename = "buyer.csv";
} else {
    // Default to fetching all users
    $query = "SELECT * FROM users";
}

// Execute the query
$result = mysqli_query($conn, $query);

// Set headers to force file download
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$filename\"");

// Create CSV output
$output = fopen("php://output", "w");

// Add headers to CSV
fputcsv($output, ['ID', 'Full Name', 'Mobile', 'Email', 'Role', 'Status']);

// Fetch and write each row to CSV
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['full_name'],
        $row['mobile'] .   "\t",
        $row['email'] .   "\t",
        ucfirst($row['role']),
        ucfirst($row['status'])
    ]);
}

// Close the CSV output and exit
fclose($output);
exit();
?>
