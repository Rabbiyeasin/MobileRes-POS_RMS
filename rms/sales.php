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
                <button onclick="goBack()" class="btn btn-transparent border-0" style="margin-left: -5rem;"><i
                    class="lni lni-shift-left" style="font-size: 20px; color: #fff;"></i> </button><!-- Icon -->
                <span>Sales</span> <!-- Text -->
            </span>

            <!-- Toggler for menu -->
            <a href="cart.html" class="btn btn-transparent border-0" style="box-shadow: none;">
                <img src="image/cart.png" alt="Menu" style="width: 20px; height: 20px;">
            </a>


        </div>
    </nav>
    <div class="container container-custom mt-3 ">

        <div class="container   mt-3">
            <!-- Wrapper for search and button -->
            <div class="d-flex justify-content-between align-items-center">

                <!-- Search -->
                <div class="d-flex align-items-center border-bottom border-secondary  w-100">
                    <input class="form-control  border-0" type="search" placeholder="Search With Name or Title..."
                        aria-label="Search">
                    <img src="image/search.png" alt="searchIcon" class="img-fluid"
                        style="height: 15px; margin-top: 2%;">
                </div>

                
                
            </div>
        </div>

        <!-- search button -->
        <div class=" mt-3">
            <button type="submit" name="search" class="btn btn-warning "
                    style="background-color: #EC6509; width: 100%;">Search</button>
        </div>

    </div>
    <div class="container container-custom">
        <p class="h5 fw-bold">Product List:</p>
        <div class="card text mb-3 rounded-3" style="max-width: 100%; background-color: #EC6509;">
            <button type="button" class="btn text-decoration-none text-dark p-0 " data-bs-toggle="modal"
                data-bs-target="#orderSale" style="width: 100%;">
                <div class="card-body text-start">
                    <p class="card-title fw-bold">Chicken Chola Batora</p>
                    <p class="card-text fw-semibold">01 <span style="float: inline-end;">200 tk</span></p>
                </div>
            </button>

        </div>
    </div>



    <!-- Modal Structure -->
    <div class="modal fade" id="orderSale" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center" style="background-color: #EC6509;">
                    <h5 class="modal-title text-center" id="orderModalLabel">Chicken Chola Batora</h5>
                </div>

                <div class="modal-body">
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title ">Item code - 02<span style="float: inline-end;">Unit -01 </span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title ">Price<span style="float: inline-end;">200.00 tk </span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title ">Quantity<span class="" style="float: inline-end;">
                                    <input type="number" class="bg-transparent "
                                        style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 5rem;">
                                </span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title ">Disc. in %<span style="float: inline-end;"><input type="number"
                                        class="bg-transparent "
                                        style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 5rem;">
                                </span></p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title ">Disc. in Amt<span style="float: inline-end;"><input type="number"
                                        class="bg-transparent "
                                        style="border: 2px solid; border-image: linear-gradient(0deg, #EC6509, #FD6A06) 1; width: 5rem;"></span>
                            </p>
                        </div>
                    </div>
                    <div class="card text mb-3 rounded-4" style="max-width: 100%; background-color:#FF964F;">
                        <div class="card-body text-start">
                            <p class="card-title ">Selling Price<span style="float: inline-end;">400 tk </span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-4">
                    <button type="button" class="btn btn-success text-center px-5" data-bs-dismiss="modal"
                        style="background-color:#5C9E31;">Cancel</button>
                    <button type="button" class="btn btn-warning text-center px-5 text-white"
                        style="background: #EC6509;">Confirm</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        function goBack() {
            window.history.back();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa"
        crossorigin="anonymous"></script>
</body>

</html>