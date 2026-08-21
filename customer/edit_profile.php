<?php

$pageTitle = "Edit Profile";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");
require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

$user_id = $_SESSION["user_id"];

/* ==========================
   Get Customer Details
========================== */

$sql = "SELECT *
        FROM users
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-pencil-square"></i>

            Edit Profile

        </h1>

        <p>

            Update your personal information.

        </p>

    </div>

    <div class="dashboard-card">

        <form action="edit_profile_process.php"
              method="POST">

            <div class="row g-4">

                <!-- First Name -->

                <div class="col-md-6">

                    <label class="form-label">

                        First Name

                    </label>

                    <input
                        type="text"
                        name="first_name"
                        class="form-control"
                        value="<?= htmlspecialchars($user["first_name"]); ?>"
                        required>

                </div>

                <!-- Last Name -->

                <div class="col-md-6">

                    <label class="form-label">

                        Last Name

                    </label>

                    <input
                        type="text"
                        name="last_name"
                        class="form-control"
                        value="<?= htmlspecialchars($user["last_name"]); ?>"
                        required>

                </div>

                <!-- Email -->

                <div class="col-md-6">

                    <label class="form-label">

                        Email Address

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($user["email"]); ?>"
                        required>

                </div>

                <!-- Phone -->

                <div class="col-md-6">

                    <label class="form-label">

                        Phone Number

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($user["phone"]); ?>"
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

                    Save Changes

                </button>

            </div>

        </form>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>