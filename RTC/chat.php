<?php
// chat.php

session_start();

// Initialize messages array if not set
if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [];
}

// Function to save a new message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']);
    $user = htmlspecialchars($_POST['user']);
    
    // Store message in session (or save to database)
    $_SESSION['messages'][] = ['user' => $user, 'message' => $message];
    
    // Return response
    echo json_encode(['status' => 'success']);
    exit;
}

// Function to get all messages
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Return all messages as JSON
    echo json_encode($_SESSION['messages']);
    exit;
}
?>
