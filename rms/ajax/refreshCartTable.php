<?php
session_start();
// Include the database connection
include '../components/dbconnect.php'; // Ensure this file defines the $conn mysqli object

// Assuming the user is logged in and we have their user ID
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view the cart.";
    exit;
}

$user_id = $_SESSION['id']; // Retrieve the logged-in user's ID

// Store 'valid' in a variable
$validity = 1;

// Fetch cart items for the logged-in user
$query = "
    SELECT item.item_name, item.price, cart.Total_Price AS sale_price, cart.Quantity, cart.Product_ID, cart.discount_percent, cart.Cart_ID
    FROM cart
    INNER JOIN item
    ON cart.Product_ID = item.item_id
    WHERE cart.user_id = ? AND cart.validity = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $user_id, $validity); // Pass the variable instead of the literal
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$discount_total = 0;
$net_payable = 0;

// Loop through the cart items and display them
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $current_total_price = ($row['price'] * $row['Quantity']);                                  //Total price of current product (unit price x quantity)
        $total_price += $current_total_price;                                                       //Add to Total price
        $discount_amount = (($row['price'] * $row['Quantity']) * ($row['discount_percent'] / 100)); //current product discount amount
        $discount_total += $discount_amount;                                                        //Add to Total Discount amount
        $salePrice = $current_total_price - $discount_amount;                                       //Current Sale Price
        echo "<tr>
                                    <td class='border-5 rounded-1'>{$row['item_name']}</td>
                                    <td class='border-5 rounded-1'>{$row['price']}</td>
                                    <td id=\"item-quantity-{$row['Cart_ID']}\" class='border-5 rounded-1'>{$row['Quantity']}</td>
                                    <td class='border-5 rounded-1'>{$discount_amount}</td>
                                    <td id=\"item-total-{$row['Cart_ID']}\" class='border-5 rounded-1'>{$salePrice}</td>
                                    ";
?>
        <td class='border-5 rounded-1' id="product-editbtn-<?php echo $row['Cart_ID']; ?>">
            <button class='btn btn-transparent' onclick="showEditModal('<?php echo $row['Cart_ID']; ?>','<?php echo $row['Product_ID']; ?>', '<?php echo $row['item_name']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['Quantity']; ?>', '<?php echo $row['discount_percent']; ?>')">
                <i class='fa-solid fa-pen-to-square' style='color: black;'></i>
            </button>
        </td>

<?php
        echo "</tr>";
    }
    $net_payable = $total_price - $discount_total;
} else {
    echo "<tr><td colspan='6' class='text-center'>Your cart is empty.</td></tr>";
}
?>