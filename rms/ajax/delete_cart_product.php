<?php
session_start();
include '../components/dbconnect.php';

// Assuming the user is logged in and we have their user ID
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view the cart.";
}

$userId = $_SESSION['id']; // Retrieve the logged-in user's ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = $_POST['cart_id'];   // Get the cart ID from POST data

    // Validate and sanitize inputs
    if (isset($cartId, $userId) && is_numeric($cartId) && is_numeric($userId)) {
        // SQL query to delete the cart item
        $query = "DELETE FROM `cart` WHERE `Cart_ID` = ? AND `User_ID` = ?";
        
        // Prepare the statement (assuming $db is your database connection)
        $stmt = $conn->prepare($query);
        
        // Bind parameters (assuming you are using MySQLi)
        $stmt->bind_param('ii', $cartId, $userId);  // 'ii' means two integers
        
        // Execute the query
        if ($stmt->execute()) {
            // Success: return JSON response
            echo json_encode(['success' => true]);
        } else {
            // Failed to delete: return failure response
            echo json_encode(['success' => false]);
        }
        
        // Close statement
        $stmt->close();
    } else {
        // Invalid input, return failure response
        echo json_encode(['success' => false]);
    }
}

