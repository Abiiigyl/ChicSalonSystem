<?php

$pageTitle = "Change Password";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "staff") {

    header("Location: ../login.php");
    exit();

}

require_once("../includes/header.php");
require_once("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- Hero Section -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-key-fill"></i>

            Change Password

        </h1>

        <p>

            Keep your account secure by choosing a strong password.

        </p>

    </div>

    <div class="dashboard-card">

        <form action="change_password_process.php"
              method="POST">

            <div class="row g-4">

                <!-- Current Password -->

                <div class="col-12">

                    <label class="form-label">

                        Current Password

                    </label>

                    <input
                        type="password"
                        name="current_password"
                        class="form-control"
                        required>

                </div>

                <!-- New Password -->

                <div class="col-md-6">

                    <label class="form-label">

                        New Password

                    </label>

                    <input
                        type="password"
                        name="new_password"
                        class="form-control"
                        minlength="8"
                        required>

                    <small class="text-muted">

                        Must be at least 8 characters long.

                    </small>

                </div>

                <!-- Confirm Password -->

                <div class="col-md-6">

                    <label class="form-label">

                        Confirm New Password

                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control"
                        minlength="8"
                        required>

                </div>

            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-between">

                <a href="profile.php"
                   class="btn btn-outline-light">

                    <i class="bi bi-arrow-left"></i>

                    Back

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    Update Password

                </button>

            </div>

        </form>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>