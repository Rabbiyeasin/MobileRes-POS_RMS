<?php
session_start(); // Start the session

// Database connection settings
include '../components/dbconnect.php'; // Ensure this file defines the $conn mysqli object

// Assuming the user is logged in and we have their user ID
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to reprint.";
}

$user_id = $_SESSION['id']; // Retrieve the logged-in user's ID


$printing_time = date('Y-m-d H:i:s');
$items = array();
$total_price = 0;
$discount = 0;

if (!isset($_GET['cart_code'])) {
    echo 'No Cart Code Provided';
    exit();
} else {
    $cart_code = intval($_GET['cart_code']);
}

// Fetch the latest cart_code from the orders table for the current user
$order_sql = "SELECT cart_code, customer_name, phone, cash, mfs, changeAmount FROM orders WHERE user_id = '$user_id' AND cart_code = '$cart_code'"; // Assuming order_id is the primary key
$order_result = $conn->query($order_sql);

if ($order_result->num_rows > 0) {
    $order_row = $order_result->fetch_assoc();
    $latest_cart_code = $order_row['cart_code'];
    $customer_name = $order_row['customer_name'];
    $customer_phone = $order_row['phone'];
    $cashPayed = $order_row['cash'];
    $mfsPayed = $order_row['mfs'];
    $changed = $order_row['changeAmount'];

    // Fetch items from the cart with the latest cart_code and validity = 0
    $sql = "SELECT * FROM cart WHERE cart_code = '$latest_cart_code' AND validity = 0 AND user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Fetch product name from products table
            $product_sql = "SELECT item_name FROM item WHERE item_id = '" . $row['Product_ID'] . "'";
            $product_result = $conn->query($product_sql);
            if ($product_result->num_rows > 0) {
                $product_name = $product_result->fetch_assoc()['item_name'];
            } else {
                $product_name = "Unknown Product"; // Fallback if product not found
            }

            $items[] = array(
                'product_name' => $product_name,
                'quantity' => $row['Quantity'],
                'unit_price' => $row['Unit_Price'],
                'price' => $row['Total_Price'],
                'discount_percent' => $row['discount_percent'],
            );
            $total_price += $row['Total_Price'];
        }
    }
}

// Calculate the total discount from the items
foreach ($items as $item) {
    $discount += ($item['price'] * $item['discount_percent'] / 100);
}

// Calculate grand total
$grand_total = $total_price - $discount;

// Close database connection
$conn->close();

// Generate receipt
?>

<style>
    @media print {
        @page {
            size: 57mm 83mm;
            margin: 0;
        }
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 0;
    }

    .receipt {
        width: 57mm;
        height: 83mm;
        padding: 4mm;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .logo {
        text-align: center;
        margin-bottom: 10px;
    }

    .company-info {
        font-size: 8px;
        margin-top: -10px;
        line-height: 5px;
    }

    .bill-info {
        /* margin-bottom: 20px; */
        font-size: 7px;
        line-height: 3px;
    }

    .bill-info-2 {
        /* margin-bottom: 20px; */
        font-size: 7px;
        line-height: 3px;
    }

    .items-table {
        border-collapse: collapse;
        width: 100%;
    }

    .items-table th,
    .items-table td {
        border: 1px solid #ccc;
        padding: 1px;
        text-align: left;
        font-size: 8px;
    }

    .total {
        font-weight: bold;
        text-align: right;
    }

    .grand-total {
        font-weight: bold;
        text-align: right;
        margin-top: 10px;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 7px;
        line-height: 2px;
    }
</style>

<div class="receipt">
    <div class="logo">
        <img src="../image/logo.png" alt="Logo" width="50px" style="float: left; margin-right: 10px;">
    </div>
    <div class="company-info">
        <p>Grand Area Restaurant</p>
        <p>Address: </p>
        <p>Hotline: 01684253435</p>
    </div>
    <div class="bill-info">
        <b style="font-size: 9px;">Invoice #<?= $latest_cart_code; ?></b>
        <p>Printing Time: <?php echo $printing_time; ?></p>
        <p>User Name: <?php echo $_SESSION['user_name']; ?></p>
        <p>Customer Name: <?php echo $customer_name; ?></p>
        <p>Phone: <?php echo $customer_phone; ?></p>
    </div>
    <table class="items-table">
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Disc.</th>
            <th>Price</th>
        </tr>
        <?php foreach ($items as $item) { ?>
            <tr>
                <td><?php echo $item['product_name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $item['unit_price']; ?> Tk</td>
                <td><?php echo $item['discount_percent']; ?>%</td>
                <td><?php echo $item['price']; ?> Tk</td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="4" class="total">Total:</td>
            <td class="total"><?php echo $total_price; ?> Tk</td>
        </tr>
    </table>
    <div class="bill-info-2">
        <p>Cash Payment: <?php echo $cashPayed; ?> Tk</p>
        <p>MFS: <?php echo $mfsPayed; ?> Tk</p>
        <p>Change: <?php echo $changed; ?> Tk</p>
    </div>
    <div class="footer">
        <p>Thanks for your order from Grand Area Restaurant</p>
        <p>Developed by Yeasin Arena Excelytics and Explication Limited</p>
    </div>
</div>

<script>
    window.print();
</script>