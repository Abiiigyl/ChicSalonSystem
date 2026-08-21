<?php

$pageTitle = "My Profile";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");
require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

$user_id = $_SESSION["user_id"];

/* ==========================
   Customer Information
========================== */

$sql = "SELECT *
        FROM users
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

/* ==========================
   Statistics
========================== */

$sql = "SELECT

            COUNT(*) AS total,

            SUM(status='Completed') AS completed,

            SUM(status='Confirmed') AS upcoming

        FROM appointments

        WHERE customer_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

/* ==========================
   Favourite Service
========================== */

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
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$favourite = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

/* ==========================
   Recent Appointments
========================== */

$sql = "SELECT

            a.appointment_date,
            a.appointment_time,
            a.status,

            s.service_name,

            CONCAT(u.first_name,' ',u.last_name) AS staff_name

        FROM appointments a

        JOIN services s
            ON a.service_id = s.service_id

        LEFT JOIN users u
            ON a.staff_id = u.user_id

        WHERE a.customer_id = ?

        ORDER BY a.appointment_date DESC,
                 a.appointment_time DESC

        LIMIT 5";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$appointments = mysqli_stmt_get_result($stmt);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section text-center mb-5">

        <i class="bi bi-person-circle"
           style="font-size:5rem;color:var(--gold);"></i>

        <h1 class="mt-3">

            <?= htmlspecialchars($user["first_name"] . " " . $user["last_name"]); ?>

        </h1>

        <p>

            Customer Profile

        </p>

    </div>

    <!-- Personal Information -->

    <div class="dashboard-card mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3>

                Personal Information

            </h3>

            <div>

                <a href="edit_profile.php"
                   class="btn btn-primary">

                    <i class="bi bi-pencil-square"></i>

                    Edit Profile

                </a>

                <a href="change_password.php"
                   class="btn btn-outline-light">

                    <i class="bi bi-key-fill"></i>

                    Change Password

                </a>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <p><strong>First Name</strong><br>

                    <?= htmlspecialchars($user["first_name"]); ?>

                </p>

                <p><strong>Last Name</strong><br>

                    <?= htmlspecialchars($user["last_name"]); ?>

                </p>

                <p><strong>Email</strong><br>

                    <?= htmlspecialchars($user["email"]); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p><strong>Phone</strong><br>

                    <?= htmlspecialchars($user["phone"]); ?>

                </p>

                <p><strong>Member Since</strong><br>

                    <?= date("d F Y", strtotime($user["created_at"])); ?>

                </p>

                <p><strong>Status</strong><br>

                    <span class="badge bg-success">

                        Active

                    </span>

                </p>

            </div>

        </div>

    </div>

    <!-- Statistics -->

    <div class="row g-4 mb-5">

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h2><?= $stats["total"] ?? 0 ?></h2>

                <p>Total Appointments</p>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h2><?= $stats["completed"] ?? 0 ?></h2>

                <p>Completed</p>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h2><?= $stats["upcoming"] ?? 0 ?></h2>

                <p>Upcoming</p>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h5>

                    <?= $favourite["service_name"] ?? "None"; ?>

                </h5>

                <p>Favourite Service</p>

            </div>

        </div>

    </div>

    <!-- Recent Appointments -->

    <div class="dashboard-card">

        <h3 class="mb-4">

            Recent Appointments

        </h3>

        <?php if(mysqli_num_rows($appointments) > 0): ?>

        <div class="table-responsive">

            <table class="table table-dark table-hover">

                <thead>

                    <tr>

                        <th>Date</th>
                        <th>Time</th>
                        <th>Service</th>
                        <th>Staff</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($row = mysqli_fetch_assoc($appointments)): ?>

                    <tr>

                        <td><?= date("d M Y", strtotime($row["appointment_date"])); ?></td>

                        <td><?= date("g:i A", strtotime($row["appointment_time"])); ?></td>

                        <td><?= htmlspecialchars($row["service_name"]); ?></td>

                        <td><?= htmlspecialchars($row["staff_name"] ?? "Not Assigned"); ?></td>

                        <td><?= htmlspecialchars($row["status"]); ?></td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

        <?php else: ?>

            <div class="text-center py-5">

                <i class="bi bi-calendar-x"
                   style="font-size:3rem;color:var(--gold);"></i>

                <p class="mt-3">

                    You haven't booked any appointments yet.

                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>