<?php
include 'components\dbconnect.php';
$query = "SELECT * FROM `item` ORDER BY item_id ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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
                <span>Item</span> <!-- Text -->
            </span>

            <!-- Toggler for menu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#logout"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation"
                style="box-shadow: none;">
                <img src="image/nav.png" alt="Menu" style="width: 20px; height: 20px;">
            </button>

            <!-- Collapsible content -->
            <div class="collapse navbar-collapse" id="logout">
                <ul class="navbar-nav" style="float: inline-end;">
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-semibold" href="signout.php">Log out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container container-custom mt-3 ">


        <div class="container d-flex justify-content-center mt-3">
            <!-- Wrapper for search and button -->
            <div class="d-flex justify-content-between align-items-center">

                <!-- Search -->
                <div class="d-flex align-items-center border-bottom border-secondary flex-grow-1 w-100">
                    <input class="form-control border-0" onkeyup="searchItems()" id="searchInput" type="search" placeholder="Search With Name or Title..."
                        aria-label="Search">
                    <img src="image/search.png" alt="searchIcon" class="img-fluid"
                        style="height: 15px; margin-top: 2%;">
                </div>
            </div>
        </div>

        <!-- Centered button -->
        <div class="d-flex justify-content-center mt-3">
            <a href="addItem.php" name="addItem" class="btn btn-warning fw-semibold"
                style="background-color: #EC6509; width: 90%;">Add Item</a>
        </div>

    </div>
    <div class="container container-custom">
        <p class="h5 fw-bold">Product List:</p>
        <div id="itemList">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="card text mb-3 rounded-3" style="max-width: 100%; background-color: #EC6509;">
                    <a href="editItem.php?id=<?php echo $row['item_id'] ?>" class="text-decoration-none text-dark">
                        <div class="card-body ">
                            <p class="card-title fw-bold"><?php echo $row['item_name'] ?> <span class="ms-4"><?php echo $row['unit'] ?> gm</span></p>
                            <p class="card-text fw-semibold"><?php echo $row['item_id'] ?> <span style="float: inline-end;"><?php echo $row['price'] ?> Tk</span></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>


    <script>
        let debounceTimeout;

        function searchItems() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                const query = document.getElementById('searchInput').value;
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'ajax/searchItemAjax.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        document.getElementById('itemList').innerHTML = this.responseText;
                    }
                };
                xhr.send('query=' + query);
            }, 300); // Delay of 300ms
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