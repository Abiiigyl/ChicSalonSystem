<?php

$pageTitle = "Appointment Details";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "staff") {

    header("Location: ../login.php");
    exit();

}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

if (!isset($_GET["id"])) {

    header("Location: appointments.php");
    exit();

}

$appointment_id = (int)$_GET["id"];
$staff_id = $_SESSION["user_id"];

/* ==========================
   Appointment Details
========================== */

$sql = "SELECT

            a.*,

            CONCAT(c.first_name,' ',c.last_name) AS customer_name,
            c.email,
            c.phone,

            s.service_name,
            s.category,
            s.duration,
            s.price

        FROM appointments a

        JOIN users c
            ON a.customer_id = c.user_id

        JOIN services s
            ON a.service_id = s.service_id

        WHERE a.appointment_id = ?
        AND a.staff_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "ii", $appointment_id, $staff_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){

    header("Location: appointments.php");
    exit();

}

$appointment = mysqli_fetch_assoc($result);

?>

<div class="container py-5">

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-calendar-check-fill"></i>

            Appointment Details

        </h1>

        <p>

            Review appointment information before providing the service.

        </p>

    </div>

    <!-- Customer -->

    <div class="dashboard-card mb-4">

        <h3 class="mb-4">

            Customer Information

        </h3>

        <div class="row">

            <div class="col-md-6">

                <p><strong>Name</strong><br>

                    <?= htmlspecialchars($appointment["customer_name"]); ?>

                </p>

                <p><strong>Email</strong><br>

                    <?= htmlspecialchars($appointment["email"]); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p><strong>Phone</strong><br>

                    <?= htmlspecialchars($appointment["phone"]); ?>

                </p>

            </div>

        </div>

    </div>

    <!-- Service -->

    <div class="dashboard-card mb-4">

        <h3 class="mb-4">

            Service Details

        </h3>

        <div class="row">

            <div class="col-md-6">

                <p><strong>Service</strong><br>

                    <?= htmlspecialchars($appointment["service_name"]); ?>

                </p>

                <p><strong>Category</strong><br>

                    <?= htmlspecialchars($appointment["category"]); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p><strong>Duration</strong><br>

                    <?= $appointment["duration"]; ?> Minutes

                </p>

                <p><strong>Price</strong><br>

                    KSh <?= number_format($appointment["price"]); ?>

                </p>

            </div>

        </div>

    </div>

    <!-- Appointment -->

    <div class="dashboard-card mb-4">

        <h3 class="mb-4">

            Appointment

        </h3>

        <p>

            <strong>Date</strong><br>

            <?= date("d F Y", strtotime($appointment["appointment_date"])); ?>

        </p>

        <p>

            <strong>Time</strong><br>

            <?= date("g:i A", strtotime($appointment["appointment_time"])); ?>

        </p>

        <p>

            <strong>Customer Notes</strong><br>

            <?= !empty($appointment["notes"])
                ? nl2br(htmlspecialchars($appointment["notes"]))
                : "<em>No notes provided.</em>"; ?>

        </p>

        <p>

            <strong>Status</strong><br>

            <?= htmlspecialchars($appointment["status"]); ?>

        </p>

    </div>

    <!-- Actions -->

    <div class="dashboard-card">

        <h3 class="mb-4">

            Appointment Actions

        </h3>

        <?php if($appointment["status"] == "Confirmed"){ ?>

            <div class="d-flex gap-3">

                <a href="update_appointment_status.php?id=<?= $appointment["appointment_id"]; ?>&status=Completed"

                   class="btn btn-success"

                   onclick="return confirm('Mark this appointment as completed?');">

                    <i class="bi bi-check-circle-fill"></i>

                    Mark Completed

                </a>

                <a href="update_appointment_status.php?id=<?= $appointment["appointment_id"]; ?>&status=No Show"

                   class="btn btn-danger"

                   onclick="return confirm('Mark this customer as a No Show?');">

                    <i class="bi bi-x-circle-fill"></i>

                    Customer No Show

                </a>

            </div>

        <?php } else { ?>

            <div class="alert alert-info mb-0">

                <strong>Current Status:</strong>

                <?= htmlspecialchars($appointment["status"]); ?>

            </div>

        <?php } ?>

    </div>

    <div class="mt-4">

        <a href="appointments.php"

           class="btn btn-outline-light">

            <i class="bi bi-arrow-left"></i>

            Back to Appointments

        </a>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>