<?php
session_start();
include '../components/dbconnect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = isset($_POST['name']) ? $_POST['name'] : 'Customer';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : 0;
    $cash = $_POST['cash'];
    $mfs = $_POST['mfs'];
    $changeAmount = $_POST['change'];
    $user_id = $_SESSION['id']; // Assuming the user is logged in and the ID is stored in the session

    // Validate required fields
    if (empty($customer_name) || empty($phone) || ($cash + $mfs <= 0)) {
        echo json_encode(['success' => false, 'message' => 'Please provide valid payment information and contact details.']);
        exit;
    }

    // Insert the data into the orders table
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, cash, mfs, changeAmount, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $time = time();
    $stmt->bind_param("ssdddii", $customer_name, $phone, $cash, $mfs, $changeAmount, $user_id, $time);
    
    if ($stmt->execute()) {
        // Get the newly inserted cart_code
        $cart_code = $conn->insert_id;

        // Update the cart table with the new cart_code and set validity to 0 for this user
        $updateStmt = $conn->prepare("UPDATE cart SET cart_code = ?, validity = 0 WHERE user_id = ? AND validity = 1");
        $updateStmt->bind_param("ii", $cart_code, $user_id);

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Checkout successful!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cart items.']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create order.']);
    }

    $stmt->close();
    $updateStmt->close();
    $conn->close();
}
?>