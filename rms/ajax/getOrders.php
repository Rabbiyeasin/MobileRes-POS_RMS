<?php
session_start(); // Start the session

// Database connection settings
include '../components/dbconnect.php'; // Ensure this file defines the $conn mysqli object

// Assuming the user is logged in and we have their user ID
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to reprint.";
    exit;
}

$user_id = $_SESSION['id']; // Retrieve the logged-in user's ID

// Check if a search query is passed
$search = isset($_GET['query']) ? trim($_GET['query']) : '';

// Prepare the SQL query
if (!empty($search)) {
    // If search query is provided, search by cart_code (you can adjust the fields to search as necessary)
    $sql = "SELECT `cart_code`, `customer_name`, `cash`+`mfs` AS `cash_paid`, `changeAmount`, `created_at` 
            FROM orders 
            WHERE user_id = '$user_id' 
            AND `cart_code` LIKE '%$search%'
            OR `customer_name` LIKE '%$search%'
            ORDER BY `created_at` DESC";
} else {
    // If no search query, return the latest orders first
    $sql = "SELECT `cart_code`, `customer_name`, `cash`+`mfs` AS `cash_paid`, `changeAmount`, `created_at` 
            FROM orders 
            WHERE user_id = '$user_id' 
            ORDER BY `created_at` DESC";
}

$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Return the orders in JSON format
header('Content-Type: application/json; charset=utf-8');
echo json_encode($orders);

$conn->close();
?>
