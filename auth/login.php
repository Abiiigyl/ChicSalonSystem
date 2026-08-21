<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | Chic Groomers Salon</title>

    <!-- Google Fonts -->
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap"
          rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet"
          href="../assets/css/style.css">

</head>

<body class="auth-page">

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-xl-5 col-lg-6 col-md-8">

            <div class="card auth-card">

                <div class="card-header border-0">

                    <i class="bi bi-scissors salon-icon"></i>

                    <h2>Chic Groomers Salon</h2>

                    <p class="mt-2">

                        Welcome Back

                    </p>

                    <small>

                        Sign in to manage appointments,
                        explore services and enjoy
                        a seamless salon experience.

                    </small>

                </div>

                <div class="card-body">
                  <?php

if (isset($_SESSION["success"])) {

?>

<div class="alert alert-success alert-dismissible fade show" role="alert">

    <?php

    echo $_SESSION["success"];

    unset($_SESSION["success"]);

    ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

<?php

}

?>

<?php

if (isset($_SESSION["error"])) {

?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">

    <?php

    echo $_SESSION["error"];

    unset($_SESSION["error"]);

    ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

<?php

}

?>

                    <form action="login_process.php"
                          method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                <i class="bi bi-envelope-fill"></i>

                                Email Address

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="example@email.com"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                <i class="bi bi-lock-fill"></i>

                                Password

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required>

                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remember">

                                <label
                                    class="form-check-label"
                                    for="remember">

                                    Remember Me

                                </label>

                            </div>

                            <a href="forgot_password.php">

                                Forgot Password?

                            </a>

                        </div>

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary btn-lg">

                                <i class="bi bi-box-arrow-in-right"></i>

                                Login

                            </button>

                        </div>

                    </form>

                    <hr class="my-4">

                    <div class="text-center">

                        <p class="mb-0">

                            Don't have an account?

                            <a href="register.php">

                                Register here

                            </a>

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>