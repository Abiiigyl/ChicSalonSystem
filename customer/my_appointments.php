<?php

$pageTitle = "My Appointments";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");
require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

$customer_id = $_SESSION["user_id"];

$sql = "SELECT

            a.appointment_id,
            a.appointment_date,
            a.appointment_time,
            a.status,
            a.created_at,

            s.service_name,
            s.duration,
            s.price,

            CONCAT(u.first_name,' ',u.last_name) AS staff_name

        FROM appointments a

        JOIN services s
            ON a.service_id = s.service_id

        JOIN users u
            ON a.staff_id = u.user_id

        WHERE a.customer_id = ?

        ORDER BY
            a.appointment_date DESC,
            a.appointment_time DESC";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$customer_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section text-center mb-5">

        <h1>

            <i class="bi bi-calendar-check-fill"></i>

            My Appointments

        </h1>

        <p>

            View your upcoming and previous salon appointments.

        </p>

    </div>

    <?php if(isset($_GET["success"])): ?>

        <div class="alert alert-success">

            <i class="bi bi-check-circle-fill"></i>

            Appointment booked successfully!

        </div>

    <?php endif; ?>

    <?php if(mysqli_num_rows($result)>0): ?>

        <div class="row g-4">

            <?php while($appointment=mysqli_fetch_assoc($result)): ?>

                <div class="col-lg-6">

                    <div class="dashboard-card h-100">

                        <div class="d-flex justify-content-between align-items-center">

                            <h4>

                                <?= htmlspecialchars($appointment["service_name"]); ?>

                            </h4>

                            <?php

                            switch($appointment["status"]){

                                case "Pending":

                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                    break;

                                case "Confirmed":

                                    echo '<span class="badge bg-success">Confirmed</span>';
                                    break;

                                case "Completed":

                                    echo '<span class="badge bg-primary">Completed</span>';
                                    break;

                                case "Cancelled":

                                    echo '<span class="badge bg-danger">Cancelled</span>';
                                    break;

                            }

                            ?>

                        </div>

                        <hr>

                        <p>

                            <strong>Date:</strong><br>

                            <?= date("d F Y",strtotime($appointment["appointment_date"])); ?>

                        </p>

                        <p>

                            <strong>Time:</strong><br>

                            <?= date("g:i A",strtotime($appointment["appointment_time"])); ?>

                        </p>

                        <p>

                            <strong>Staff:</strong><br>

                            <?= htmlspecialchars($appointment["staff_name"]); ?>

                        </p>

                        <p>

                            <strong>Duration:</strong><br>

                            <?= $appointment["duration"]; ?> minutes

                        </p>

                        <p>

                            <strong>Price:</strong><br>

                            KSh <?= number_format($appointment["price"]); ?>

                        </p>

                        <small class="text-muted">

                            Booked on

                            <?= date("d M Y",strtotime($appointment["created_at"])); ?>

                        </small>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="dashboard-card text-center py-5">

            <i class="bi bi-calendar-x"
               style="font-size:4rem;color:var(--gold);"></i>

            <h3 class="mt-4">

                No Appointments Yet

            </h3>

            <p>

                You haven't booked any appointments yet.

            </p>

            <a href="book_appointments.php"
               class="btn btn-primary mt-3">

                Book Your First Appointment

            </a>

        </div>

    <?php endif; ?>

</div>

<?php

require_once("../includes/footer.php");

?>