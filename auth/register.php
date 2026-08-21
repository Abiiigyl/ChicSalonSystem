<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register | Chic Groomers Salon</title>

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

                        Begin Your Luxury Experience

                    </p>

                    <small>

                        Create your account to book appointments,
                        manage your visits and enjoy premium
                        salon services.

                    </small>

                </div>

                <div class="card-body">
                   <?php

if (isset($_SESSION['success'])) {

?>

<div class="alert alert-success alert-dismissible fade show" role="alert">

    <?php

    echo $_SESSION['success'];

    unset($_SESSION['success']);

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

if (isset($_SESSION['error'])) {

?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">

    <?php

    echo $_SESSION['error'];

    unset($_SESSION['error']);

    ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

<?php

}

?>
                    <form action="register_process.php"
                          method="POST">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    <i class="bi bi-person-fill"></i>

                                    First Name

                                </label>

                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control"
                                    placeholder="First name"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    <i class="bi bi-person-fill"></i>

                                    Last Name

                                </label>

                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control"
                                    placeholder="Last name"
                                    required>

                            </div>

                        </div>

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

                                <i class="bi bi-telephone-fill"></i>

                                Phone Number

                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                placeholder="07XXXXXXXX"
                                required>

                        </div>
                                                <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    <i class="bi bi-lock-fill"></i>

                                    Password

                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Password"
                                    required>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    <i class="bi bi-shield-lock-fill"></i>

                                    Confirm Password

                                </label>

                                <input
                                    type="password"
                                    name="confirm_password"
                                    class="form-control"
                                    placeholder="Confirm password"
                                    required>

                            </div>

                        </div>

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary btn-lg">

                                <i class="bi bi-person-plus-fill"></i>

                                Create Account

                            </button>

                        </div>

                    </form>

                    <hr class="my-4">

                    <div class="text-center">

                        <p class="mb-0">

                            Already have an account?

                            <a href="login.php">

                                Login here

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