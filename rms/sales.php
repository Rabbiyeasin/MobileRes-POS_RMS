<?php
include 'components/dbconnect.php';
$query = "SELECT * FROM `item` ORDER BY item_id ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
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
                <button onclick="goBack()" class="btn btn-transparent border-0" style="margin-left: -5rem;">
                    <i class="lni lni-shift-left" style="font-size: 20px; color: #fff;"></i>
                </button><!-- Icon -->
                <span>Sales</span> <!-- Text -->
            </span>

            <!-- Toggler for menu -->
            <a href="cart.php" class="btn btn-transparent border-0" style="box-shadow: none;">
                <img src="image/cart.png" alt="Menu" style="width: 20px; height: 20px;">
                <i class="bi bi-cart"></i> Cart <span id="cartCounter" class="badge bg-primary">0</span>
            </a>


        </div>
    </nav>

    <!-- Bootstrap Toast for Success Notification -->
    <div class="toast align-items-center text-bg-success" id="cartToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Product added to cart successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <div class="container container-custom mt-3">
        <div class="container mt-3">
            <!-- Wrapper for search and button -->
            <div class="d-flex justify-content-between align-items-center">
                <!-- Search -->
                <div class="d-flex align-items-center border-bottom border-secondary  w-100">
                    <input id="search-input" class="form-control border-0" type="search" placeholder="Search With Name or Title..."
                        aria-label="Search" onkeyup="debouncedSearch()">
                    <img src="image/search.png" alt="searchIcon" class="img-fluid" style="height: 15px; margin-top: 2%;">
                </div>
            </div>
        </div>

        <!-- Search button -->
        <div class="mt-3">
            <button type="submit" name="search" class="btn btn-warning" style="background-color: #EC6509; width: 100%;">Search</button>
        </div>
    </div>

    <div class="container container-custom">
        <p class="h5 fw-bold">Product List:</p>
        <div id="itemList">
            <?php while ($product = mysqli_fetch_assoc($result)) { ?>
                <div class="card text mb-3 rounded-3" style="max-width: 100%; background-color: #EC6509;">
                    <button type="button" class="btn text-decoration-none text-dark p-0" data-bs-toggle="modal"
                        data-bs-target="#orderSaleModal" onclick="loadProductData(<?php echo $product['item_id']; ?>)" style="width: 100%;">
                        <div class="card-body text-start">
                            <p class="card-title fw-bold"><?php echo $product['item_name']; ?></p>
                            <span style="float: right;"><?php echo $product['price']; ?> tk</span>
                        </div>
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="orderSaleModal" tabindex="-1" aria-labelledby="orderSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderSaleModalLabel">Item Details <span id="modalItemName" class="fw-bold"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title">Item code - <span id="modalItemCode"></span> <span style="float: inline-end;">Unit - <span id="modalItemUnit"></span></span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title">Price <span style="float: inline-end;"><span id="modalItemPrice"></span> Taka</span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title">Quantity<span style="float: inline-end;">
                                    <input type="number" class="bg-transparent" min="1" id="quantity" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 5rem;" onchange="calculatePercent()" onkeyup="calculatePercent()">
                                </span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title">Discount in %<span style="float: inline-end;">
                                    <input type="number" id="discountPercent" onchange="calculatePercent()" onkeyup="calculatePercent()" class="bg-transparent" max="100" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 5rem;">
                                </span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title">Discount in Amount <span style="float: inline-end;"><input type="number" id="discValue" value="0" readonly class="bg-transparent" style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 5rem;"></span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title">Selling Price<span style="float: inline-end; font-weight: bold; font-size: 20px;" id="totalPrice">0 tk</span></p>
                        </div>
                    </div>

                    <script>
                        function calculatePercent() {
                            // Get item price, quantity, and discount percentage
                            const price = parseFloat(document.getElementById('modalItemPrice').innerText) || 0;
                            const quantity = parseInt(document.getElementById('quantity').value) || 0;
                            let discountPercent = parseInt(document.getElementById('discountPercent').value) || 0;

                            // Limit discount percentage to 100
                            if (discountPercent > 100) {
                                discountPercent = 100;
                                document.getElementById('discountPercent').value = discountPercent; // Update the input field
                            }

                            // Calculate total price
                            const totalPrice = price * quantity;
                            const discountValue = (totalPrice * (discountPercent / 100));

                            // Update discount and total price fields
                            document.getElementById('discValue').value = discountValue.toFixed(2);
                            document.getElementById('totalPrice').innerText = (totalPrice - discountValue).toFixed(2);

                            if (quantity > 0) {
                                document.getElementById('confirmBtn').removeAttribute('disabled');
                            } else {
                                document.getElementById('confirmBtn').setAttribute('disabled', true);
                            }
                        }
                    </script>

                </div>


                <div class="modal-footer border-0 justify-content-center gap-4">
                    <button type="button" class="btn btn-success text-center px-5" data-bs-dismiss="modal"
                        style="background-color:#5C9E31;">Cancel</button>
                    <button disabled id="confirmBtn" type="button" data-bs-dismiss="modal" class="btn btn-warning text-center px-5 text-white"
                        style="background: #EC6509;">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('confirmBtn').addEventListener('click', function() {
            const productID = parseInt(document.getElementById('modalItemCode').innerText);
            const quantity = document.getElementById('quantity').value;
            const totalPrice = parseFloat(document.getElementById('totalPrice').innerText);
            const unitPrice = parseFloat(document.getElementById('modalItemPrice').innerText) || 0;
            const discount_percent = parseFloat(document.getElementById('discountPercent').value) || 0;

            const formData = new FormData();
            formData.append('product_id', productID);
            formData.append('quantity', quantity);
            formData.append('total_price', totalPrice);
            formData.append('unit_price', unitPrice);
            formData.append('discount_percent', discount_percent);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/cartAdd.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);

                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('orderSaleModal'));
                    modal.hide();

                    // Show the toast notification
                    const toast = new bootstrap.Toast(document.getElementById('cartToast'));
                    toast.show();

                    // Update the cart counter
                    updateCartCounter();
                } else {
                    console.error('Failed to add product to cart');
                }
            };
            xhr.send(formData);
        });

        // Function to update the cart counter
        function updateCartCounter() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'ajax/cartCounter.php', true); // Assuming '/cart/count' returns the number of items in the cart.
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);

                    const count = JSON.parse(xhr.responseText).count;
                    document.getElementById('cartCounter').textContent = count;
                }
            };
            xhr.send();
        }
        updateCartCounter();
    </script>
    <script>
        // Function to navigate back
        function goBack() {
            window.history.back();
        }

        // Debouncing logic to limit the number of API calls
        let debounceTimer;

        function debouncedSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(searchItems, 300); // 300ms delay before firing the search
        }

        // Function to make the AJAX call to search items
        function searchItems() {
            const searchTerm = document.getElementById('search-input').value;

            const xhr = new XMLHttpRequest();
            xhr.open('GET', `ajax/getItems.php?search=${encodeURIComponent(searchTerm)}`, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const items = JSON.parse(xhr.responseText);
                    updateItemList(items);
                }
            };
            xhr.send();
        }

        // Function to update the list of items dynamically
        function updateItemList(items) {
            const itemList = document.getElementById('itemList');
            itemList.innerHTML = ''; // Clear existing items

            if (items.length > 0) {
                items.forEach(item => {
                    const itemCard = `
                        <div class="card text mb-3 rounded-3" style="max-width: 100%; background-color: #EC6509;">
                            <button type="button" class="btn text-decoration-none text-dark p-0" data-bs-toggle="modal"
                                data-bs-target="#orderSaleModal" onclick="loadProductData(${item.item_id})" style="width: 100%;">
                                <div class="card-body text-start">
                                    <p class="card-title fw-bold">${item.item_name}</p>
                                    <span style="float: right;">${item.price} tk</span>
                                </div>
                            </button>
                        </div>
                    `;
                    itemList.innerHTML += itemCard;
                });
            } else {
                itemList.innerHTML = '<p>No items found</p>';
            }
        }

        function loadProductData(productId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `ajax/getItemData.php?id=${productId}`, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const itemData = JSON.parse(xhr.responseText)[0]; // Get the first item from the response
                        // Populate modal with item data
                        document.getElementById('modalItemName').innerText = itemData.item_name;
                        document.getElementById('modalItemCode').innerText = itemData.item_id;
                        document.getElementById('modalItemUnit').innerText = `Unit: ${itemData.unit}`;
                        document.getElementById('modalItemPrice').innerText = itemData.price;
                        document.getElementById('quantity').value = null;
                        document.getElementById('discountPercent').value = null;
                        calculatePercent();
                    } else {
                        console.error("Error fetching item data:", xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>