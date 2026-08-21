<?php

$pageTitle = "Add Staff Member";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
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

            <i class="bi bi-person-plus-fill"></i>

            Add Staff Member

        </h1>

        <p>

            Create a new staff account for your salon.

        </p>

    </div>

    <div class="dashboard-card">

        <form action="add_staff_process.php"
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
                        placeholder="Enter first name"
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
                        placeholder="Enter last name"
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
                        placeholder="example@email.com"
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
                        placeholder="07XXXXXXXX"
                        required>

                </div>

                <!-- Password -->

                <div class="col-md-6">

                    <label class="form-label">

                        Password

                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>

                </div>

                <!-- Confirm Password -->

                <div class="col-md-6">

                    <label class="form-label">

                        Confirm Password

                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control"
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

                        <option value="">

                            Select Specialization

                        </option>

                        <option>Hair</option>

                        <option>Barber</option>

                        <option>Nails</option>

                        <option>Makeup</option>

                        <option>Facial</option>

                        <option>Spa</option>

                        <option>Reception</option>

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
                            checked>

                        <label class="form-check-label">

                            Active

                        </label>

                    </div>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="is_active"
                            value="0">

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

                    <i class="bi bi-person-check-fill"></i>

                    Save Staff Member

                </button>

            </div>

        </form>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>