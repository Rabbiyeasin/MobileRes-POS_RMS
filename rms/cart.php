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
    SELECT item.item_name, item.price, cart.Total_Price AS sale_price, cart.Quantity
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

    <nav class="navbar" style="background: #EC6509;">
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

    <div class="container container-custom mt-3">
        <div class="table-responsive border border-dark rounded-4" style="height: max-content; overflow: hidden;">
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
                <tbody>
                    <?php
                    // Loop through the cart items and display them
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $total_price += ($row['price'] * $row['Quantity']);
                            $discount_amount = (($row['price'] * $row['Quantity']) - $row['sale_price']);
                            $discount_total += $discount_amount;

                            echo "<tr>
                                    <td class='border-5 rounded-1'>{$row['item_name']}</td>
                                    <td class='border-5 rounded-1'>{$row['price']}</td>
                                    <td class='border-5 rounded-1'>{$row['Quantity']}</td>
                                    <td class='border-5 rounded-1'>{$discount_amount}</td>
                                    <td class='border-5 rounded-1'>{$row['sale_price']}</td>
                                    <td class='border-5 rounded-1''>
                                        <button class='btn btn-transparent'><i class='fa-solid fa-pen-to-square' style='color: black;'></i></button>
                                    </td>
                                  </tr>";
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

    <script>
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

</body>

</html>