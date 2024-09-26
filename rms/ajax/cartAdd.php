<?php
session_start();

// Include the database connection
include '../components/dbconnect.php';

// Retrieve form data
$user_id = $_SESSION['id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$total_price = $_POST['total_price'];
$unit_price = $_POST['unit_price'];
$discount_percent = $_POST['discount_percent'];


// Insert into cart
$sql = "INSERT INTO cart (User_ID, Product_ID, Quantity, discount_percent, Unit_Price, Total_Price)
        VALUES ('$user_id', '$product_id', '$quantity', '$discount_percent', '$unit_price', '$total_price')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
        'status' => 'error',
        'error' => $conn->error,
    ]);
}

// Close connection
$conn->close();
