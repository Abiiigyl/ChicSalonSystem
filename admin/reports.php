<?php

$pageTitle = "Reports & Analytics";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

/* ==========================
   Dashboard Statistics
========================== */

$totalCustomers = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users WHERE role='customer'"
))["total"];

$totalStaff = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users WHERE role='staff'"
))["total"];

$totalServices = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM services"
))["total"];

$totalAppointments = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM appointments"
))["total"];

$pending = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Pending'"
))["total"];

$confirmed = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Confirmed'"
))["total"];

$completed = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Completed'"
))["total"];

$cancelled = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Cancelled'"
))["total"];

$noShows = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='No Show'"
))["total"];

/* ==========================
   Revenue
========================== */

$sql = "SELECT SUM(s.price) AS revenue

        FROM appointments a

        JOIN services s

        ON a.service_id = s.service_id

        WHERE a.status='Completed'";

$revenue = mysqli_fetch_assoc(mysqli_query($conn, $sql));

$totalRevenue = $revenue["revenue"] ?? 0;

/* ==========================
   Most Popular Service
========================== */

$sql = "SELECT

            s.service_name,

            COUNT(*) AS total

        FROM appointments a

        JOIN services s

        ON a.service_id=s.service_id

        GROUP BY a.service_id

        ORDER BY total DESC

        LIMIT 1";

$popularService = mysqli_fetch_assoc(mysqli_query($conn, $sql));

/* ==========================
   Top Staff
========================== */

$sql = "SELECT

            CONCAT(u.first_name,' ',u.last_name) AS staff_name,

            COUNT(*) AS total

        FROM appointments a

        JOIN users u

        ON a.staff_id=u.user_id

        GROUP BY a.staff_id

        ORDER BY total DESC

        LIMIT 1";

$topStaff = mysqli_fetch_assoc(mysqli_query($conn, $sql));

/* ==========================
   Recent Appointments
========================== */

$sql = "SELECT

            a.appointment_date,
            a.appointment_time,
            a.status,

            s.service_name,

            CONCAT(c.first_name,' ',c.last_name) AS customer,

            CONCAT(st.first_name,' ',st.last_name) AS staff

        FROM appointments a

        JOIN services s
            ON a.service_id=s.service_id

        JOIN users c
            ON a.customer_id=c.user_id

        JOIN users st
            ON a.staff_id=st.user_id

        ORDER BY a.created_at DESC

        LIMIT 5";

$recentAppointments = mysqli_query($conn, $sql);

?>

<div class="container py-5">

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-bar-chart-fill"></i>

            Reports & Analytics

        </h1>

        <p>

            View salon performance, appointments and business insights.

        </p>

    </div>

    <!-- Statistics -->

    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <i class="bi bi-people-fill dashboard-icon"></i>
                <h2><?= $totalCustomers ?></h2>
                <p>Total Customers</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <i class="bi bi-person-badge-fill dashboard-icon"></i>
                <h2><?= $totalStaff ?></h2>
                <p>Total Staff</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <i class="bi bi-scissors dashboard-icon"></i>
                <h2><?= $totalServices ?></h2>
                <p>Total Services</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <i class="bi bi-calendar-check-fill dashboard-icon"></i>
                <h2><?= $totalAppointments ?></h2>
                <p>Total Appointments</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <h3><?= $pending ?></h3>
                <p>Pending</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <h3><?= $confirmed ?></h3>
                <p>Confirmed</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <h3><?= $completed ?></h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card text-center">
                <h3><?= $cancelled ?></h3>
                <p>Cancelled</p>
            </div>
        </div>

        <div class="col-md-3">
    <div class="dashboard-card text-center">
        <h3><?= $noShows ?></h3>
        <p>No Shows</p>
    </div>
</div>

    </div>

    <!-- Revenue / Popular Service / Top Staff -->

    <div class="row g-4 mb-5">

        <div class="col-lg-4">

            <div class="dashboard-card text-center">

                <i class="bi bi-cash-stack dashboard-icon"></i>

                <h2>KSh <?= number_format($totalRevenue) ?></h2>

                <p>Total Revenue</p>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-card text-center">

                <i class="bi bi-star-fill dashboard-icon"></i>

                <h4>Most Popular Service</h4>

                <?php if($popularService){ ?>

                    <h5><?= htmlspecialchars($popularService["service_name"]) ?></h5>

                    <small>

                        Booked <?= $popularService["total"] ?> time(s)

                    </small>

                <?php } else { ?>

                    <p>No bookings yet.</p>

                <?php } ?>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-card text-center">

                <i class="bi bi-trophy-fill dashboard-icon"></i>

                <h4>Top Staff Member</h4>

                <?php if($topStaff){ ?>

                    <h5><?= htmlspecialchars($topStaff["staff_name"]) ?></h5>

                    <small>

                        <?= $topStaff["total"] ?> appointment(s)

                    </small>

                <?php } else { ?>

                    <p>No appointments yet.</p>

                <?php } ?>

            </div>

        </div>

    </div>

    <!-- Recent Appointments -->

    <div class="dashboard-card">

        <h3 class="mb-4">

            Recent Appointments

        </h3>

        <div class="table-responsive">

            <table class="table table-dark table-hover align-middle">

                <thead>

                    <tr>

                        <th>Date</th>
                        <th>Time</th>
                        <th>Customer</th>
                        <th>Staff</th>
                        <th>Service</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($row = mysqli_fetch_assoc($recentAppointments)){ ?>

                    <tr>

                        <td><?= date("d M Y", strtotime($row["appointment_date"])) ?></td>

                        <td><?= date("g:i A", strtotime($row["appointment_time"])) ?></td>

                        <td><?= htmlspecialchars($row["customer"]) ?></td>

                        <td><?= htmlspecialchars($row["staff"]) ?></td>

                        <td><?= htmlspecialchars($row["service_name"]) ?></td>

                        <td><?= htmlspecialchars($row["status"]) ?></td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>