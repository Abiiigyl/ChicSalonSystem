<?php

$pageTitle = "Customer Dashboard";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");
require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");


/* ============================
   Upcoming Appointment Query
============================ */

$customer_id = $_SESSION["user_id"];

$sql = "SELECT

            a.appointment_date,
            a.appointment_time,
            a.status,

            s.service_name,

            CONCAT(u.first_name,' ',u.last_name) AS staff_name

        FROM appointments a

        JOIN services s
            ON a.service_id = s.service_id

        JOIN users u
            ON a.staff_id = u.user_id

        WHERE a.customer_id = ?

        AND a.status IN ('Pending','Confirmed')

        AND a.appointment_date >= CURDATE()

        ORDER BY
            a.appointment_date,
            a.appointment_time

        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $customer_id);

mysqli_stmt_execute($stmt);

$upcomingAppointment = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

/* ============================
   Favourite Service
============================ */

$sql = "SELECT

            s.service_name,

            COUNT(*) AS total_bookings

        FROM appointments a

        JOIN services s
            ON a.service_id = s.service_id

        WHERE a.customer_id = ?

        GROUP BY a.service_id

        ORDER BY total_bookings DESC

        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $customer_id);

mysqli_stmt_execute($stmt);

$favouriteService = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

/* ============================
   Previous Services
============================ */

$sql = "SELECT

            s.service_name,

            a.appointment_date,

            CONCAT(u.first_name,' ',u.last_name) AS staff_name

        FROM appointments a

        JOIN services s
            ON a.service_id = s.service_id

        JOIN users u
            ON a.staff_id = u.user_id

        WHERE a.customer_id = ?

        AND a.status = 'Completed'

        ORDER BY a.appointment_date DESC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $customer_id);

mysqli_stmt_execute($stmt);

$previousServices = mysqli_stmt_get_result($stmt);

/* Greeting based on time */

$hour = date("H");

if ($hour < 12) {

    $greeting = "Good Morning";

} elseif ($hour < 18) {

    $greeting = "Good Afternoon";

} else {

    $greeting = "Good Evening";

}

?>

<div class="container py-5">

    <!-- Hero Section -->

    <div class="hero-section mb-5">

        <h1>

            <?= $greeting; ?>,

            <?= htmlspecialchars($_SESSION["first_name"]); ?>! 👋

        </h1>

        <p>

            Ready for your next glow-up?

        </p>

    </div>

    <!-- Dashboard Cards -->

    <div class="row g-4">

        <!-- BOOK APPOINTMENT -->

        <div class="col-lg-8">

            <div class="dashboard-card book-card">

                <i class="bi bi-calendar2-plus dashboard-icon"></i>

                <h2>Book Appointment</h2>

                <p>

                    Ready for your next visit?

                    <br><br>

                    Schedule your appointment with one of our professional stylists in just a few clicks.

                </p>

                <a href="book_appointments.php"

                   class="btn btn-primary btn-lg">

                    <i class="bi bi-arrow-right-circle me-2"></i>

                    Book Now

                </a>

            </div>

        </div>

        <!-- RIGHT COLUMN -->

        <div class="col-lg-4">

            <div class="row g-4">

                <!-- Upcoming -->

               <div class="col-12">

    <div class="dashboard-card small-card">

        <i class="bi bi-clock-history dashboard-icon"></i>

        <h4>

            Upcoming Appointment

        </h4>

        <?php if($upcomingAppointment): ?>

            <h5>

                <?= htmlspecialchars($upcomingAppointment["service_name"]); ?>

            </h5>

            <p>

                <strong>Date:</strong>

                <?= date("d M Y", strtotime($upcomingAppointment["appointment_date"])); ?>

            </p>

            <p>

                <strong>Time:</strong>

                <?= date("g:i A", strtotime($upcomingAppointment["appointment_time"])); ?>

            </p>

            <p>

                <strong>Staff:</strong>

                <?= htmlspecialchars($upcomingAppointment["staff_name"]); ?>

            </p>

            <span class="badge bg-success">

                <?= htmlspecialchars($upcomingAppointment["status"]); ?>

            </span>

        <?php else: ?>

            <p>

                No upcoming appointments.

            </p>

        <?php endif; ?>

    </div>

</div>
                <!-- Favourite -->

                <div class="col-12">

                    <div class="dashboard-card small-card">

                        <i class="bi bi-star-fill dashboard-icon"></i>

                        <h4>Favourite Service</h4>

                        <?php if($favouriteService): ?>

                            <h5>

                                <?= htmlspecialchars($favouriteService["service_name"]); ?>

                            </h5>

                            <p>

                                Booked

                                <strong>

                                    <?= $favouriteService["total_bookings"]; ?>

                                </strong>

                                time(s)

                            </p>

                        <?php else: ?>

                            <p>

                                No favourite service yet.

                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Previous Services -->

    <div class="dashboard-card mt-5">

        <h3 class="mb-4">

            Previous Services

        </h3>

       <?php if(mysqli_num_rows($previousServices) > 0): ?>

            <?php while($service = mysqli_fetch_assoc($previousServices)): ?>

                <div class="border-bottom py-3">

                    <h5>

                        <?= htmlspecialchars($service["service_name"]); ?>

                    </h5>

                    <p class="mb-1">

                        <strong>Date:</strong>

                        <?= date("d M Y", strtotime($service["appointment_date"])); ?>

                    </p>

                    <small class="text-muted">

                        Staff:

                        <?= htmlspecialchars($service["staff_name"]); ?>

                    </small>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="text-center py-5">

                <i class="bi bi-scissors"

                style="font-size:3rem;color:var(--gold);"></i>

                <p class="mt-3">

                    No previous services yet.

                </p>

                <small>

                    Your completed appointments will appear here.

                </small>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>