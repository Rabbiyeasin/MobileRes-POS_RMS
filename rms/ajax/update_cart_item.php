<?php
session_start();
include '../components/dbconnect.php'; // Database connection

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['cartItemId'];
    $new_quantity = $_POST['quantity'];
    $discount_percent = $_POST['discount_percent'];

    // Update the cart in the database
    $query = "UPDATE cart SET Quantity = ?, discount_percent = ? WHERE Product_ID = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiii', $new_quantity, $discount_percent, $item_id, $user_id);

    if ($stmt->execute()) {
        // Fetch updated total price and discount information
        $query = "
            SELECT item.price, cart.Total_Price AS sale_price, cart.Quantity, cart.discount_percent, cart.Cart_ID
            FROM cart
            INNER JOIN item ON cart.Product_ID = item.item_id
            WHERE cart.user_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $price = 0;

        $total_price = 0;
        $discount_total = 0;

        while ($row = $result->fetch_assoc()) {
            $total_price += ($row['price'] * $row['Quantity']);
            $discount_amount = (($row['price'] * $row['Quantity']) * ($row['discount_percent']/100));
            $discount_total += $discount_amount;
            $price = $row['price'];
            $cart_id = $row['Cart_ID'];
        }

        $net_payable = $total_price - $discount_total;

        echo json_encode([
            'success' => true,
            'cart_id' => $cart_id,
            'item_id' => $item_id,
            'new_quantity' => $new_quantity,
            'new_total_price' => $new_quantity * $price, // Assuming price is passed in POST data
            'total_price' => $total_price,
            'discount_total' => $discount_total,
            'net_payable' => $net_payable
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart item']);
    }
}
?>
