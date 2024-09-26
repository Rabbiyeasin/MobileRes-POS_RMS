<?php
session_start();
// Include the database connection
include 'components/dbconnect.php'; // Ensure this file defines the $conn mysqli object

// Assuming the user is logged in and we have their user ID
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view the cart.";
    exit;
}

$user_id = $_SESSION['id']; // Retrieve the logged-in user's ID

// Fetch cart items for the logged-in user
$query = "
    SELECT item.item_name, item.price, cart.Total_Price AS sale_price, cart.Quantity, cart.Product_ID, cart.discount_percent, cart.Cart_ID
    FROM cart
    INNER JOIN item
    ON cart.Product_ID = item.item_id
    WHERE cart.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$discount_total = 0;
$net_payable = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css">

    <style>
        body {
            height: max-content;
            font-family: Inria serif;
        }

        .container-custom {
            padding: 20px;
        }

        input:focus,
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>

<body>

    <nav class="navbar fixed-top" style="background: #EC6509;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <a class="navbar-brand" href="userHome.php"><img src="image/logo.png" alt="Logo" style="height: 30px;"></a>

            <!-- Centered Title with Icon -->
            <span class="mx-auto fs-5 fw-bold text-white d-flex align-items-center gap-4">
                <button onclick="goBack()" class="btn btn-transparent border-0" style="margin-left: -5rem;"><i
                        class="lni lni-shift-left" style="font-size: 20px; color: #fff;"></i></button>
                <span>Cart</span>
            </span>

            <!-- Cart Icon -->
            <a href="cart.php" class="btn btn-transparent border-0" style="box-shadow: none;">
                <img src="image/cart.png" alt="Cart" style="width: 20px; height: 20px;">
            </a>
        </div>
    </nav>



    <div class="container container-custom mt-5">
        <div class="table-responsive border border-dark" style="height: max-content; overflow: hidden;">
            <table class="table table-bordered border-light">
                <thead>
                    <tr>
                        <th class="border-5 rounded-2" style="background-color: #EC6509; color: white;">Item Name</th>
                        <th class="border-5 rounded-2" style="background-color: #EC6509; color: white;">Unit Price</th>
                        <th class="border-5 rounded-2" style="background-color: #EC6509; color: white;">QTY</th>
                        <th class="border-5 rounded-2" style="background-color: #EC6509; color: white;">Dis Amt</th>
                        <th class="border-5 rounded-2" style="background-color: #EC6509; color: white;">Total</th>
                        <th class="border-5 rounded-2" style="background-color: #EC6509; color: white;">Edit</th>
                    </tr>
                </thead>
                <tbody id="cartTable">
                    <?php
                    // Loop through the cart items and display them
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $current_total_price = ($row['price'] * $row['Quantity']);                                //Total price of current product (unit price x quantity)
                            $total_price += $current_total_price;                                                     //Add to Total price
                            $discount_amount = (($row['price'] * $row['Quantity']) * ($row['discount_percent'] / 100)); //current product discount amount
                            $discount_total += $discount_amount;                                                      //Add to Total Discount amount
                            $salePrice = $current_total_price - $discount_amount;                                     //Current Sale Price
                            echo "<tr>
                                    <td class='border-5 rounded-1'>{$row['item_name']}</td>
                                    <td class='border-5 rounded-1'>{$row['price']}</td>
                                    <td id=\"item-quantity-{$row['Cart_ID']}\" class='border-5 rounded-1'>{$row['Quantity']}</td>
                                    <td class='border-5 rounded-1'>{$discount_amount}</td>
                                    <td id=\"item-total-{$row['Cart_ID']}\" class='border-5 rounded-1'>{$salePrice}</td>
                                    ";
                    ?>
                            <td class='border-5 rounded-1' id="product-editbtn-<?php echo $row['Cart_ID']; ?>">
                                <button class='btn btn-transparent' onclick="showEditModal('<?php echo $row['Product_ID']; ?>', '<?php echo $row['item_name']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['Quantity']; ?>', '<?php echo $row['discount_percent']; ?>')">
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
                </tbody>
            </table>
        </div>

        <!-- Net Prices -->
        <div class="mt-4">
            <div class="card-body text-end mb-3">
                <p class="card-title fs-1">Total price
                    <span><input type="number" class="bg-transparent" id="totalPrice" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 25%;" value="<?php echo $total_price; ?>" disabled></span>
                </p>
            </div>
            <div class="card-body text-end mb-3">
                <p class="card-title fs-1">Discount
                    <span><input type="number" class="bg-transparent" id="discountTotal" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 25%;" value="<?php echo $discount_total; ?>" disabled></span>
                </p>
            </div>
            <div class="card-body text-end">
                <p class="card-title fs-1">Net Payable
                    <span><input type="number" class="bg-transparent" id="netPayable" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 25%;" value="<?php echo $net_payable; ?>" disabled></span>
                </p>
            </div>
        </div>

        <!-- Payment Options -->
        <p class="h1 text-center mt-5">Payment option</p>

        <div class="card-body text-end mb-3">
            <p class="card-title fs-1">Cash
                <span><input type="number" id="cash" class="bg-transparent" oninput="calculateTotal()" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 25%;"></span>
            </p>
        </div>
        <div class="card-body text-end mb-5">
            <p class="card-title fs-1">MFS
                <span><input type="number" id="mfs" class="bg-transparent" oninput="calculateTotal()" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 25%;"></span>
            </p>
        </div>

        <div class="d-flex mt-5 justify-content-between">
            <span class="text-start" style="width: 50%;">
                <input type="text" class="bg-transparent" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 95%; height: 3rem;" placeholder="Customer Name">
            </span>
            <span class="text-end" style="width: 50%;">
                <input type="text" class="bg-transparent" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 95%; height: 3rem;" placeholder="Phone Number">
            </span>
        </div>

    </div>




    <!-- Bootstrap Modal for Editing Cart Item -->
    <div class="modal fade" id="editCartModal" tabindex="-1" aria-labelledby="editCartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCartModalLabel">Edit Cart Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCartForm">
                        <input type="hidden" id="cartItemId" name="cartItemId">
                        <div class="mb-3">
                            <label for="editItemName" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="editItemName" name="itemName" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="editQuantity" name="quantity" min="1" onkeyup="recalculate()" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDiscount" class="form-label">Discount in %</label>
                            <input type="number" class="form-control" id="editDiscount" name="discount_percent" min="0" max="100" onkeyup="recalculate()" required>
                        </div>
                        <div class="mb-3">
                            <label for="discountCash" class="form-label">Discount in Cash</label>
                            <input type="number" class="form-control" id="discountCash" name="discount_cash" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editPrice" class="form-label">Unit Price</label>
                            <input type="number" class="form-control" id="editPrice" name="price" readonly disabled>
                        </div>
                        <button class="btn btn-danger">Delete Item</button>
                        <button type="submit" class="btn btn-primary float-end">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function recalculate() {
            const quantity = document.getElementById('editQuantity').value;
            const discountPercent = document.getElementById('editDiscount').value;
            const unitPrice = document.getElementById('editPrice').value;

            // Calculate the cash discount
            if (quantity && discountPercent && unitPrice) {
                const totalAmount = quantity * unitPrice; // Total amount before discount
                const cashDiscount = (totalAmount * discountPercent) / 100; // Calculate cash discount
                document.getElementById('discountCash').value = cashDiscount.toFixed(2); // Set the discount cash value
            } else {
                document.getElementById('discountCash').value = 0; // Reset cash discount if inputs are invalid
            }
        }
    </script>





    <script>
        // Function to show the modal with the current cart item data
        function showEditModal(itemId, itemName, price, quantity, discountPercent) {
            document.getElementById('cartItemId').value = itemId;
            document.getElementById('editItemName').value = itemName;
            document.getElementById('editPrice').value = price;
            document.getElementById('editQuantity').value = quantity;
            document.getElementById('editDiscount').value = discountPercent;

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('editCartModal'));
            modal.show();
            recalculate();
        }

        // Handle the form submission for updating the cart item
        document.getElementById('editCartForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            // Send the update request using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/update_cart_item.php', true); // 'update_cart_item.php' is the endpoint where the update logic resides
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    console.log(response);
                    if (response.success) {
                        // Update the overall total, discount, and net payable amounts
                        document.getElementById('totalPrice').value = response.total_price;
                        document.getElementById('discountTotal').value = response.discount_total;
                        document.getElementById('netPayable').value = response.net_payable;

                        // Hide the modal
                        var modal = bootstrap.Modal.getInstance(document.getElementById('editCartModal'));
                        modal.hide();

                        refreshTable();

                    } else {
                        alert("Error updating cart item.");
                    }
                }
            };
            xhr.send(formData);
        });

        function refreshTable() {
            // Refresh the Cart table at id="cartTable"
            var refreshXhr = new XMLHttpRequest();
            refreshXhr.open('GET', 'ajax/refreshCartTable.php', true); // Endpoint to refresh the cart table
            refreshXhr.onload = function() {
                if (refreshXhr.status === 200) {
                    // Assuming the response is the HTML content of the updated cart table
                    document.getElementById('cartTable').innerHTML = refreshXhr.responseText;
                } else {
                    alert("Error refreshing cart table.");
                }
            };
            refreshXhr.send();
        }

        function calculateTotal() {
            // Get the input values
            let cash = parseFloat(document.getElementById('cash').value) || 0;
            let mfs = parseFloat(document.getElementById('mfs').value) || 0;
            let netPayable = parseFloat(document.getElementById('netPayable').value);

            // Calculate the total payment
            let totalPayment = cash + mfs;

            // Check if total exceeds net payable
            if (totalPayment > netPayable) {
                alert("Total of Cash and MFS cannot exceed the Net Payable amount.");
                // Reset the inputs
                document.getElementById('cash').value = '';
                document.getElementById('mfs').value = '';
            }
        }

        function goBack() {
            window.history.back();
        }
    </script>

    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>