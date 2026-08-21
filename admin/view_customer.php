<?php

$pageTitle = "Customer Details";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

if (!isset($_GET["id"])) {
    header("Location: manage_customers.php");
    exit();
}

$user_id = intval($_GET["id"]);

/* ==========================
   Customer Details
========================== */

$sql = "SELECT *
        FROM users
        WHERE user_id = ?
        AND role = 'customer'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$customer) {
    header("Location: manage_customers.php");
    exit();
}

/* ==========================
   Statistics
========================== */

$sql = "SELECT

            COUNT(*) AS total,

            SUM(status='Completed') AS completed,

            SUM(status='Cancelled') AS cancelled,

            SUM(status='Pending') AS pending,

            SUM(status='Confirmed') AS confirmed

        FROM appointments

        WHERE customer_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

/* ==========================
   Booking History
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

        JOIN users u
            ON a.staff_id = u.user_id

        WHERE a.customer_id = ?

        ORDER BY
            a.appointment_date DESC,
            a.appointment_time DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$appointments = mysqli_stmt_get_result($stmt);

?>

<div class="container py-5">

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-person-circle"></i>

            Customer Details

        </h1>

        <p>

            View customer information and appointment history.

        </p>

    </div>

    <!-- Customer Information -->

    <div class="dashboard-card mb-5">

        <h3 class="mb-4">

            Personal Information

        </h3>

        <div class="row">

            <div class="col-md-6">

                <p>

                    <strong>Name:</strong><br>

                    <?= htmlspecialchars($customer["first_name"] . " " . $customer["last_name"]); ?>

                </p>

                <p>

                    <strong>Email:</strong><br>

                    <?= htmlspecialchars($customer["email"]); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p>

                    <strong>Phone:</strong><br>

                    <?= htmlspecialchars($customer["phone"]); ?>

                </p>

                <p>

                    <strong>Joined:</strong><br>

                    <?= date("d F Y", strtotime($customer["created_at"])); ?>

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

                <h2><?= $stats["confirmed"] ?? 0 ?></h2>

                <p>Confirmed</p>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h2><?= $stats["pending"] ?? 0 ?></h2>

                <p>Pending</p>

            </div>

        </div>

    </div>

    <!-- Booking History -->

    <div class="dashboard-card">

        <h3 class="mb-4">

            Appointment History

        </h3>

        <?php if(mysqli_num_rows($appointments) > 0): ?>

            <div class="table-responsive">

                <table class="table table-dark table-hover align-middle">

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

                            <td>

                                <?= date("d M Y", strtotime($row["appointment_date"])); ?>

                            </td>

                            <td>

                                <?= date("g:i A", strtotime($row["appointment_time"])); ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($row["service_name"]); ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($row["staff_name"]); ?>

                            </td>

                            <td>

                                <?php

                                switch($row["status"]){

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

                            </td>

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

                    This customer has not booked any appointments yet.

                </p>

            </div>

        <?php endif; ?>

    </div>

    <div class="mt-4">

        <a href="manage_customers.php"
           class="btn btn-outline-light">

            <i class="bi bi-arrow-left"></i>

            Back to Customers

        </a>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>