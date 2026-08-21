<?php

$pageTitle = "Appointment Details";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_appointments.php");
    exit();
}

$appointment_id = (int)$_GET["id"];

$sql = "SELECT

            a.*,

            CONCAT(c.first_name,' ',c.last_name) AS customer_name,
            c.email,
            c.phone,

            CONCAT(st.first_name,' ',st.last_name) AS staff_name,

            s.service_name,
            s.duration,
            s.price

        FROM appointments a

        JOIN users c
            ON a.customer_id = c.user_id

        JOIN users st
            ON a.staff_id = st.user_id

        JOIN services s
            ON a.service_id = s.service_id

        WHERE a.appointment_id = ?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$appointment_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){

    header("Location: manage_appointments.php");
    exit();

}

$appointment=mysqli_fetch_assoc($result);

require_once("../includes/header.php");
require_once("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-calendar-check-fill"></i>

            Appointment Details

        </h1>

        <p>

            Review booking information and manage appointment status.

        </p>

    </div>

    <div class="dashboard-card">

        <div class="row">

        <?php if(isset($_GET["success"])): ?>

    <div class="alert alert-success alert-dismissible fade show">

        <i class="bi bi-check-circle-fill"></i>

        Appointment status updated successfully.

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

    </div>

<?php endif; ?>

            <!-- Customer -->

            <div class="col-md-6 mb-4">

                <h4>

                    <i class="bi bi-person-circle"></i>

                    Customer Information

                </h4>

                <hr>

                <p>

                    <strong>Name:</strong><br>

                    <?= htmlspecialchars($appointment["customer_name"]); ?>

                </p>

                <p>

                    <strong>Email:</strong><br>

                    <?= htmlspecialchars($appointment["email"]); ?>

                </p>

                <p>

                    <strong>Phone:</strong><br>

                    <?= htmlspecialchars($appointment["phone"]); ?>

                </p>

            </div>

            <!-- Appointment -->

            <div class="col-md-6 mb-4">

                <h4>

                    <i class="bi bi-calendar-event"></i>

                    Appointment Information

                </h4>

                <hr>

                <p>

                    <strong>Service:</strong><br>

                    <?= htmlspecialchars($appointment["service_name"]); ?>

                </p>

                <p>

                    <strong>Staff:</strong><br>

                    <?= htmlspecialchars($appointment["staff_name"]); ?>

                </p>

                <p>

                    <strong>Date:</strong><br>

                    <?= date("d F Y",strtotime($appointment["appointment_date"])); ?>

                </p>

                <p>

                    <strong>Time:</strong><br>

                    <?= date("g:i A",strtotime($appointment["appointment_time"])); ?>

                </p>

                <p>

                    <strong>Duration:</strong><br>

                    <?= $appointment["duration"]; ?> minutes

                </p>

                <p>

                    <strong>Price:</strong><br>

                    KSh <?= number_format($appointment["price"]); ?>

                </p>

            </div>

        </div>

        <!-- Notes -->

        <div class="mb-4">

            <h4>

                <i class="bi bi-chat-left-text-fill"></i>

                Customer Notes

            </h4>

            <hr>

            <div class="p-3 rounded bg-dark">

                <?php

                if(!empty($appointment["notes"])){

                    echo nl2br(htmlspecialchars($appointment["notes"]));

                }else{

                    echo "<em>No notes provided.</em>";

                }

                ?>

            </div>

        </div>

        <!-- Status -->

        <div class="mb-5">

            <h4>

                <i class="bi bi-info-circle-fill"></i>

                Appointment Status

            </h4>

            <hr>

            <?php

            switch($appointment["status"]){

                case "Pending":

                    echo '<span class="badge bg-warning text-dark fs-6">Pending</span>';

                    break;

                case "Confirmed":

                    echo '<span class="badge bg-success fs-6">Confirmed</span>';

                    break;

                case "Completed":

                    echo '<span class="badge bg-primary fs-6">Completed</span>';

                    break;

                case "Cancelled":

                    echo '<span class="badge bg-danger fs-6">Cancelled</span>';

                    break;

            }

            ?>

        </div>

        <!-- Buttons -->

        <div class="d-flex justify-content-between flex-wrap gap-2">

            <a href="manage_appointments.php"
               class="btn btn-outline-light">

                <i class="bi bi-arrow-left"></i>

                Back

            </a>

            <div>

                <a href="update_appointment_status.php?id=<?= $appointment["appointment_id"]; ?>&status=Confirmed"
                   class="btn btn-success">

                    <i class="bi bi-check-circle-fill"></i>

                    Confirm

                </a>

                <a href="update_appointment_status.php?id=<?= $appointment["appointment_id"]; ?>&status=Completed"
                   class="btn btn-primary">

                    <i class="bi bi-check2-all"></i>

                    Complete

                </a>

                <a href="update_appointment_status.php?id=<?= $appointment["appointment_id"]; ?>&status=Cancelled"
                   class="btn btn-danger"
                   onclick="return confirm('Cancel this appointment?');">

                    <i class="bi bi-x-circle-fill"></i>

                    Cancel

                </a>

            </div>

        </div>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>