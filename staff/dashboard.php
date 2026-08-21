<?php

$pageTitle = "Staff Dashboard";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "staff") {

    header("Location: ../login.php");
    exit();

}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

$staff_id = $_SESSION["user_id"];

/* ==========================
   Greeting
========================== */

$hour = date("H");

if ($hour < 12) {

    $greeting = "Good Morning";

} elseif ($hour < 18) {

    $greeting = "Good Afternoon";

} else {

    $greeting = "Good Evening";

}

/* ==========================
   Today's Appointments
========================== */

$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE staff_id = ?
        AND appointment_date = CURDATE()";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $staff_id);
mysqli_stmt_execute($stmt);

$todayAppointments = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
)["total"];

/* ==========================
   Completed Today
========================== */

$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE staff_id = ?
        AND appointment_date = CURDATE()
        AND status='Completed'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $staff_id);
mysqli_stmt_execute($stmt);

$completedToday = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
)["total"];

/* ==========================
   Remaining Today
========================== */

$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE staff_id = ?
        AND appointment_date = CURDATE()
        AND status IN ('Pending','Confirmed')";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $staff_id);
mysqli_stmt_execute($stmt);

$remainingToday = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
)["total"];

/* ==========================
   Today's Schedule
========================== */

$sql = "SELECT
            start_time,
            end_time
        FROM staff_schedules
        WHERE staff_id = ?
        AND work_date = CURDATE()";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $staff_id);
mysqli_stmt_execute($stmt);

$schedule = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <?= $greeting; ?>,

            <?= htmlspecialchars($_SESSION["first_name"]); ?>! 👋

        </h1>

        <p>

            Welcome back. Here's your work summary for today.

        </p>

    </div>

    <!-- Quick Actions -->

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="dashboard-card management-card">

                <i class="bi bi-calendar-check management-icon"></i>

                <h3>My Appointments</h3>

                <p>

                    View today's appointments and customer information.

                </p>

                <a href="appointments.php"
                   class="btn btn-primary">

                    View Appointments

                </a>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-card management-card">

                <i class="bi bi-calendar3 management-icon"></i>

                <h3>My Schedule</h3>

                <p>

                    View your work schedule and assigned shifts.

                </p>

                <a href="schedule.php"
                   class="btn btn-primary">

                    View Schedule

                </a>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-card management-card">

                <i class="bi bi-person-circle management-icon"></i>

                <h3>My Profile</h3>

                <p>

                    Update your personal information and password.

                </p>

                <a href="profile.php"
                   class="btn btn-primary">

                    My Profile

                </a>

            </div>

        </div>

    </div>

    <!-- Today's Summary -->

    <div class="mt-5">

        <h2 class="summary-heading mb-4">

            Today's Summary

        </h2>

        <div class="row g-4">

            <div class="col-md-3">

                <div class="dashboard-card summary-card">

                    <h4>Appointments</h4>

                    <div class="summary-number">

                        <?= $todayAppointments ?>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="dashboard-card summary-card">

                    <h4>Completed</h4>

                    <div class="summary-number">

                        <?= $completedToday ?>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="dashboard-card summary-card">

                    <h4>Remaining</h4>

                    <div class="summary-number">

                        <?= $remainingToday ?>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="dashboard-card summary-card">

                    <h4>Working Hours</h4>

                    <div class="summary-number">

                        <?php

                        if ($schedule) {

                            echo date("g:i A", strtotime($schedule["start_time"]));

                            echo " - ";

                            echo date("g:i A", strtotime($schedule["end_time"]));

                        } else {

                            echo "Off Duty";

                        }

                        ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>