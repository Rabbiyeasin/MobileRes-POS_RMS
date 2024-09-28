<?php
// Database connection settings
// Include the database connection
include 'components/dbconnect.php'; // Ensure this file defines the $conn mysqli object
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from database
$user_id = $_SESSION['id'];
$printing_time = date('Y-m-d H:i:s');
$items = array();
$total_price = 0;
$discount = 0;

// Fetch items from database
$sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND printing_time = '$printing_time'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Fetch product name from products table
        $product_sql = "SELECT `Product_Name` FROM `products` WHERE `Product_ID` = '" . $row['product_id'] . "'";
        $product_result = $conn->query($product_sql);
        $product_name = $product_result->fetch_assoc()['Product_Name'];

        $items[] = array(
            'product_name' => $product_name,
            'quantity' => $row['quantity'],
            'price' => $row['Total_Price']
        );
        $total_price += $row['Total_Price'];
    }
}

// Fetch discount from database
$sql = "SELECT discount FROM cart WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $discount = $result->fetch_assoc()['discount'];
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
    }
    .receipt {
        width: 57mm;
        height: 83mm;
        padding: 5mm;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .logo {
        text-align: center;
        margin-bottom: 10px;
    }
    .company-info {
        margin-bottom: 20px;
    }
    .bill-info {
        margin-bottom: 20px;
    }
    .items-table {
        border-collapse: collapse;
        width: 100%;
    }
    .items-table th, .items-table td {
        border: 1px solid #ccc;
        padding: 5px;
        text-align: left;
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
    }
</style>

<div class="receipt">
    <div class="logo">
        <img src="logo.png" alt="Logo" width="50px">
    </div>
    <div class="company-info">
        <p>Grand Area Restaurant</p>
        <p>Address: </p>
        <p>Hotline: 01684253435</p>
    </div>
    <div class="bill-info">
        <p>Bill</p>
        <p>Printing Time: <?php echo $printing_time; ?></p>
        <p>User Name: <?php echo $user_name; ?></p>
    </div>
    <table class="items-table">
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php foreach ($items as $item) { ?>
        <tr>
            <td><?php echo $item['product_name']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo $item['price']; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2" class="total">Total:</td>
            <td class="total"><?php echo $total_price; ?></td>
        </tr>
        <?php if ($discount > 0) { ?>
        <tr>
            <td colspan="2" class="total">Discount:</td>
            <td class="total"><?php echo $discount; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2" class="grand-total">Grand Total:</td>
            <td class="grand-total"><?php echo $grand_total; ?></td>
        </tr>
    </table>
    <div class="footer">
        <p>Thanks for order from Grand Area Restaurant</p>
        <p>Developed by Yeasin Arena Excelytics and Explication Limited</p>
    </div>
</div>

<script>
    //window.print();
</script>