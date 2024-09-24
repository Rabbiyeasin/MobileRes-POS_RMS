<?php
$signin = false;
$error = false;
$role = '';

if (isset($_POST['signin'])) {
    include 'components\dbconnect.php';
    $userName = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM `users` WHERE Username = '$userName'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($password == $row['password']) {
                // server should keep session data for AT LEAST 7 days
                ini_set('session.gc_maxlifetime', 604800);

                // each client should remember their session id for EXACTLY 7 days
                session_set_cookie_params(604800);

                session_start();
                $signin = true;

                $_SESSION['id'] = $row['user_id'];
                $_SESSION['loggedin'] = true;
                $role = $row['role'];
            } else {
                $error = "Invalid Id or Password";
            }
        }
    } else {
        $error = "Invalid Credentials";
    }

    echo $role;
    if ($role == 'user') {
        header("Location: userHome.php");
    }
    if ($role == 'admin') {
        header("Location: adminHome.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        /* Your custom CSS styles */
        body {

            font-family: 'Inria Serif';

        }

        .custom {
            background-color: #EC6509;

        }

        .navbarcustom {
            background-color: transparent !important;
        }

        .btn-outline-custom {
            --bs-btn-color: #fff;
            --bs-btn-border-color: #EC6509;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #EC6509;
            --bs-btn-hover-border-color: #EC6509;
            --bs-btn-focus-shadow-rgb: 220, 53, 69;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #EC6509;
            --bs-btn-active-border-color: #EC6509;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #EC6509;
            --bs-btn-disabled-bg: transparent;
            --bs-btn-disabled-border-color: #EC6509;
            --bs-gradient: none;
        }

        input:focus,
        .form-control:focus {

            background-color: #EC6509;
            color: #fff;
            outline: none;
            box-shadow: none;


        }
    </style>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body>
    <!-- Your HTML code for the login form -->

    <div class="header">
        <?php

        if ($error) {
            echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> ' . $error . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        ?>
    </div>



    <section class="vh-100 gradient-custom">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="container py-5 h-100 ">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-10 col-md-8 col-lg-6 col-xl-5">
                        <div class="card custom text border-0" style="border-radius: 2rem;">
                            <div class="card-body ps-4 pe-5 ">

                                <div class="mb-md-5 ">

                                    <p class=" mb-2 text-uppercase text-center fs-1 text-white">Login</p>

                                    <div class="form-outline form-white mb-4 fs-4 text-white">
                                        <label class="form-label ml-3" for="id">User/ Branch Name</label>
                                        <div class=" border-bottom rounded-0 border-secondary">

                                            <input type="text" name="username"
                                                class="form-control form-control-md border-0"
                                                placeholder="Enter name here" style="background-color: #EC6509; "
                                                required>

                                        </div>
                                    </div>
                                    <div class="form-outline form-white mb-4 fs-4 text-white">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="border-bottom rounded-0 border-secondary text-white">

                                            <input type="password" name="password"
                                                class="form-control form-control-md  border-0 "
                                                placeholder="Enter your password here"
                                                style="background-color: #EC6509;" required>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button
                                            class="btn bttn btn-outline-custom btn-md text-uppercase px-5 col-sm-12 fs-3 rounded-5 shadow"
                                            type="submit" name="signin">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa"
        crossorigin="anonymous"></script>
</body>

</html>