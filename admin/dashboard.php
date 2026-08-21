<?php

$pageTitle = "Admin Dashboard";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

/* Greeting based on time */

$hour = date("H");

if ($hour < 12) {

    $greeting = "Good Morning";

} elseif ($hour < 18) {

    $greeting = "Good Afternoon";

} else {

    $greeting = "Good Evening";

}

/* ==========================
   Today's Summary
========================== */

$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE appointment_date = CURDATE()";

$todayAppointments = mysqli_fetch_assoc(mysqli_query($conn, $sql))["total"];

$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE status = 'Pending'";

$pendingAppointments = mysqli_fetch_assoc(mysqli_query($conn, $sql))["total"];

$sql = "SELECT
            SUM(s.price) AS revenue
        FROM appointments a
        JOIN services s
            ON a.service_id = s.service_id
        WHERE a.status = 'Completed'
        AND a.appointment_date = CURDATE()";

$result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

$todayRevenue = $result["revenue"] ?? 0;

$sql = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'customer'";

$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn, $sql))["total"];

?>

<div class="container py-5">

    <!-- Hero Section -->

    <div class="hero-section mb-5">

        <h1>

            <?= $greeting; ?>,

            <?= htmlspecialchars($_SESSION["first_name"]); ?>! 👋

        </h1>

        <p>

            Welcome back. Here's what's happening at Chic Groomers Salon today.

        </p>

    </div>

    <!-- ==========================
         MANAGEMENT MODULES
    =========================== -->

    <div class="row g-4">

        <!-- Service Management -->

        <div class="col-lg-6">

            <div class="dashboard-card management-card">

                <i class="bi bi-scissors management-icon"></i>

                <h3>Service Management</h3>

                <p>

                    Create, edit and organize salon services, pricing and durations.

                </p>

                <a href="manage_services.php"
                   class="btn btn-primary">

                    Manage Services

                </a>

            </div>

        </div>

        <!-- Staff Management -->

        <div class="col-lg-6">

            <div class="dashboard-card management-card">

                <i class="bi bi-people-fill management-icon"></i>

                <h3>Staff Management</h3>

                <p>

                    Manage employees, schedules and service assignments.

                </p>

                <a href="manage_staff.php"
                   class="btn btn-primary">

                    Manage Staff

                </a>

            </div>

        </div>

        <!-- Appointment Management -->

        <div class="col-lg-6">

            <div class="dashboard-card management-card">

                <i class="bi bi-calendar-check management-icon"></i>

                <h3>Appointment Management</h3>

                <p>

                    View, confirm and manage customer appointments.

                </p>

                <a href="manage_appointments.php"
                   class="btn btn-primary">

                    Manage Appointments

                </a>

            </div>

        </div>

        <!-- Customer Management -->

        <div class="col-lg-6">

            <div class="dashboard-card management-card">

                <i class="bi bi-person-lines-fill management-icon"></i>

                <h3>Customer Management</h3>

                <p>

                    View customer profiles and appointment history.

                </p>

                <a href="manage_customers.php"
                   class="btn btn-primary">

                    Manage Customers

                </a>

            </div>

        </div>

        <!-- Reports -->

        <div class="col-12">

            <div class="dashboard-card management-card reports-card">

                <i class="bi bi-bar-chart-line-fill management-icon"></i>

                <h3>Reports & Analytics</h3>

                <p>

                    Monitor revenue, appointments, popular services and staff performance.

                </p>

                <a href="reports.php"
                   class="btn btn-primary">

                    View Reports

                </a>

            </div>

        </div>

    </div>

    <!-- ==========================
         TODAY'S SUMMARY
    =========================== -->

    <div class="mt-5">

        <h2 class="summary-heading mb-4">

            Today's Summary

        </h2>

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card summary-card">

                    <h4>Appointments</h4>

                    <div class="summary-number">

                        <?= $todayAppointments ?>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card summary-card">

                    <h4>Pending</h4>

                    <div class="summary-number">

                        <?= $pendingAppointments ?>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card summary-card">

                    <h4>Revenue</h4>

                    <div class="summary-number">

                        KSh <?= number_format($todayRevenue) ?>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card summary-card">

                    <h4>Customers</h4>

                    <div class="summary-number">

                        <?= $totalCustomers ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>