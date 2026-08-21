<?php

$pageTitle = "Edit Staff Member";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

/* Validate Staff ID */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_staff.php");
    exit();
}

$user_id = (int)$_GET["id"];

/* Get Staff Member */

$sql = "SELECT *
        FROM users
        WHERE user_id = ?
        AND role = 'staff'";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: manage_staff.php");
    exit();
}

$staff = mysqli_fetch_assoc($result);

require_once("../includes/header.php");
require_once("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- Hero Section -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-pencil-square"></i>

            Edit Staff Member

        </h1>

        <p>

            Update this staff member's information.

        </p>

    </div>

    <div class="dashboard-card">

        <form action="edit_staff_process.php"
              method="POST">

            <input
                type="hidden"
                name="user_id"
                value="<?= $staff["user_id"]; ?>">

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
                        value="<?= htmlspecialchars($staff["first_name"]); ?>"
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
                        value="<?= htmlspecialchars($staff["last_name"]); ?>"
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
                        value="<?= htmlspecialchars($staff["email"]); ?>"
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
                        value="<?= htmlspecialchars($staff["phone"]); ?>"
                        required>

                </div>

                <!-- Specialization -->

                <div class="col-md-6">

                    <label class="form-label">

                        Specialization

                    </label>

                    <select
                        name="specialization"
                        class="form-select"
                        required>

                        <option value="Hair" <?= $staff["specialization"]=="Hair Stylist" ? "selected" : ""; ?>>

                            Hair 

                        </option>

                        <option value="Barber" <?= $staff["specialization"]=="Barber" ? "selected" : ""; ?>>

                            Barber

                        </option>

                        <option value="Nails" <?= $staff["specialization"]=="Nail Technician" ? "selected" : ""; ?>>

                            Nails

                        </option>

                        <option value="Makeup" <?= $staff["specialization"]=="Makeup Artist" ? "selected" : ""; ?>>

                            Makeup

                        </option>

                        <option value="Facial" <?= $staff["specialization"]=="Facialist" ? "selected" : ""; ?>>

                            Facial

                        </option>

                        <option value="Spa" <?= $staff["specialization"]=="Spa Therapist" ? "selected" : ""; ?>>

                            Spa

                        </option>

                        <option value="Reception" <?= $staff["specialization"]=="Receptionist" ? "selected" : ""; ?>>

                            Reception

                        </option>

                    </select>

                </div>

                <!-- Status -->

                <div class="col-md-6">

                    <label class="form-label d-block mb-3">

                        Account Status

                    </label>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="is_active"
                            value="1"
                            <?= $staff["is_active"] ? "checked" : ""; ?>>

                        <label class="form-check-label">

                            Active

                        </label>

                    </div>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="is_active"
                            value="0"
                            <?= !$staff["is_active"] ? "checked" : ""; ?>>

                        <label class="form-check-label">

                            Inactive

                        </label>

                    </div>

                </div>

            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-between">

                <a href="manage_staff.php"
                   class="btn btn-outline-light">

                    <i class="bi bi-arrow-left"></i>

                    Back

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-save"></i>

                    Update Staff Member

                </button>

            </div>

        </form>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>