<?php
session_start(); // Start the session

// Database connection settings
include 'components/dbconnect.php'; // Ensure this file defines the $conn mysqli object

// Assuming the user is logged in and we have their user ID
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to reprint.";
}


$user_id = $_SESSION['id']; // Retrieve the logged-in user's ID

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Reprint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        class="lni lni-shift-left" style="font-size: 20px; color: #fff;"></i> </button><!-- Icon -->
                <span>Invoice Reprint</span> <!-- Text -->
            </span>

            <!-- Toggler for menu -->
            <a href="cart.html" class="btn btn-transparent border-0" style="box-shadow: none;">
                <img src="image/cart.png" alt="Menu" style="width: 20px; height: 20px;">
            </a>
        </div>
    </nav>

    <div class="container container-custom mt-3">
        <!-- Wrapper for search and button -->
        <div class="d-flex justify-content-between align-items-center">
            <!-- Search -->
            <div class="d-flex align-items-center border-bottom border-secondary w-100">
                <input class="form-control border-0" type="search" id="searchInput" placeholder="Search invoice number ..." onkeyup="searchOrdersDebounced()" aria-label="Search">
                <button type="button" onclick="searchOrders()" class="btn btn-transparent shadow" style="width: max-content;">
                    <img src="image/search.png" alt="searchIcon" class="img-fluid" style="height: 15px; margin-top: 2%;">
                </button>
            </div>
            <script>
                let debounceTimeout;
                function searchOrdersDebounced() {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        searchOrders();
                    }, 300); // Delay of 300ms
                }
            </script>
        </div>
    </div>

    <div class="container container-custom mt-3" id="orders-container">
        <!-- Dynamic orders will be displayed here -->
    </div>




    <!-- Modal Structure for Print Reciept -->
    <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="printFrame" src="" style="width: 100%; height: 30rem;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>



    <!-- JavaScript -->
    <script>
        function fetchOrders(searchQuery = '') {
            const url = searchQuery ? `ajax/getOrders.php?query=${searchQuery}` : 'ajax/getOrders.php';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('orders-container');
                    container.innerHTML = ''; // Clear the container

                    if (data.length === 0) {
                        container.innerHTML = '<p>No orders found.</p>';
                        return;
                    }

                    // Loop through each order and create a card
                    data.forEach(order => {
                        const card = document.createElement('div');
                        card.className = 'card text mb-3 rounded-4';
                        card.style.backgroundColor = '#FF964F';

                        const createdAt = new Date(order.created_at * 1000);
                        const formattedDate = formatExactDate(createdAt);
                        const timeAgo = timeSince(createdAt);

                        card.innerHTML = `
                        <a href="javascript:printInvoice(${order.cart_code});" class="text-decoration-none text text-dark">
                            <div class="card-body text-start">
                                <p class="card-title fw-bold">Invoice #<span>${order.cart_code}</span></p>
                                <p class="card-title">Customer Name: <span>${order.customer_name}</span></p>
                                <p class="card-title">Total Sale: <span>${order.cash_paid} tk</span></p>
                                <p class="card-title">Change Amount: <span>${order.changeAmount} tk</span></p>
                                <p class="card-title">Date Time: 
                                    <span>${formattedDate} (${timeAgo})</span> 
                                </p>
                            </div>
                        </a>
                    `;
                        container.appendChild(card);
                    });
                })
                .catch(error => console.error('Error fetching orders:', error));
        }

        // Function to initiate the search
        function searchOrders() {
            const searchQuery = document.getElementById('searchInput').value.trim();
            fetchOrders(searchQuery);
        }

        window.onload = () => fetchOrders(); // Fetch all orders initially

        function formatExactDate(date) {
            const options = {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true,
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            return new Intl.DateTimeFormat('en-US', options).format(date);
        }

        function timeSince(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            let interval = Math.floor(seconds / 31536000);

            if (interval >= 1) return interval + (interval === 1 ? " year ago" : " years ago");
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) return interval + (interval === 1 ? " month ago" : " months ago");
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) return interval + (interval === 1 ? " day ago" : " days ago");
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) return interval + (interval === 1 ? " hour ago" : " hours ago");
            interval = Math.floor(seconds / 60);
            if (interval >= 1) return interval + (interval === 1 ? " minute ago" : " minutes ago");
            return Math.floor(seconds) + (Math.floor(seconds) === 1 ? " second ago" : " seconds ago");
        }

        function printInvoice(cart_code) {
            document.getElementById('printFrame').src = 'ajax/reprint.php?cart_code=' + cart_code;
            let printModal = new bootstrap.Modal(document.getElementById('printModal'));
            printModal.show();
        }

        function goBack() {
            window.history.back();
        }
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>