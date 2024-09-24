<?php
    include 'components\dbconnect.php';
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            height: max-content;
            font-family: Inria serif;
        }      

        .btn-dashboard {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px solid #EC6509;
            border-radius: 10px;
            padding: 15px;
            width: 100px;
            height: 100px;
            background-color: #fff;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-dashboard img {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
        }

        .btn-dashboard span {
            font-size: 14px;
            font-weight: bold;
            color: black;
        }

        .btn-dashboard:hover {
            background-color: #ec64093a;
        }

        .container-custom {
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar" style="background: #EC6509;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <a class="navbar-brand" href="#"><img src="image/logo.png" alt="" style="height: 30px;"></a>

            <!-- Centered Title -->
            <span class="mx-auto fs-5 fw-bold text-white">
                Grand Area Restaurant
            </span>

            <!-- Toggler for menu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation"
                style="box-shadow: none;">
                <img src="image/nav.png" alt="Menu" style="width: 20px; height: 20px;">
            </button>


            <!-- Collapsible content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav" style="float: inline-end;">
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-semibold" href="signout.php">Log out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container container-custom mt-3">
        <!-- Card section -->
        
        <div class="row justify-content-between">
            <div class="card text mb-3 rounded-3" style="width: 38%; background-color: #FF964F;">

                <div class="card-body ">
                    <p class="card-title fw-semibold">Branch Kagojitola</p>
                    <p class="card-text fw-semibold">Branch Id: <span style="float: inline-end;">200 </span></p>
                </div>

            </div>
            <div class="card text text-dark mb-3 " style="max-width: 20rem; background-color: #EC6509; width: 60%;">
                <div class="card-header">
                    <div id="date" class="carousel slide">
                        <div class="carousel-inner text-center fs-6">
                            <div class="carousel-item active">
                                <p>July 2024</p>
                            </div>
                            <div class="carousel-item">
                                <p>August 2024</p>
                            </div>
                            <div class="carousel-item">
                                <p>September 2024</p>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#date" data-bs-slide="prev">
                            <span><i class="fa-solid fa-caret-left" style="color: black;"></i></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#date" data-bs-slide="next">
                            <span><i class="fa-solid fa-caret-right" style="color: black;"></i></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="invoice-box">

                        <p class=" fs-6 fw-semibold">Invoice Available: <span id="invoicesAvailable">209</span></p>
                        <p class="card-text fs-6 fw-semibold">Invoices Done: <span id="invoicesDone">91</span></p>
                        <!-- <p class="card-text fs-6 fw-semibold">Invoices Failed: <span id="invoicesFailed">0</span></p> -->
                        <p class="card-text fs-6 fw-bold">Total Sale: <span id="totalSale">31466.66</span></p>
                    </div>

                </div>
            </div>
        </div>

        <!-- button section -->
        <div class="row mt-5 ms-2">
            <div class="row justify-content-center">
                <!-- First row of buttons -->
                <div class="col-4 d-flex justify-content-center">
                    <a href="sales.php" class="btn-dashboard text-decoration-none">
                        <img src="image/sales.png" alt="Sales">
                        <span>Sales</span>
                    </a>
                </div>
                
                <div class="col-4 d-flex justify-content-center">
                    <a href="item.php" class="btn-dashboard text-decoration-none">
                        <img src="image/items.png" alt="Items">
                        <span>Items</span>
                    </a>
                </div>

                <div class="col-4 d-flex justify-content-center">
                    <a class="btn-dashboard text-decoration-none">
                        <img src="image/report.png" alt="Report">
                        <span>Report</span>
                    </a>
                </div>
                
            </div>

            <!-- Second row of buttons -->
            <div class="row justify-content-center">

                

                <div class="col-4 d-flex justify-content-center">
                    <a href="ivcReprint.html" class="btn-dashboard text-decoration-none">
                        <img src="image/reprint.png" alt="Reprint">
                        <span>Reprint</span>
                    </a>
                </div>
                <div class="col-4 d-flex justify-content-center">
                    <a class="btn-dashboard text-decoration-none">
                        <img src="image/settings.png" alt="Settings">
                        <span>Settings</span>
                    </a>
                </div>
                <!-- <div class="col-4 d-flex justify-content-center">
                    <a class="btn-dashboard text-decoration-none">
                        <img src="image/failed.png" alt="Failed Invoices">
                        <span>Failed Invoices</span>
                    </a>
                </div> -->
            </div>

            <!-- Third row of buttons -->
            <div class="row justify-content-center">

            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa"
        crossorigin="anonymous"></script>
</body>

</html>