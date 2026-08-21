<?php

$pageTitle = "Manage Appointments";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

/* Dashboard Statistics */

$pending = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Pending'"))["total"];

$confirmed = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Confirmed'"))["total"];

$completed = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Completed'"))["total"];

$cancelled = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM appointments WHERE status='Cancelled'"))["total"];

/* Appointment List */

$sql = "SELECT

            a.appointment_id,

            CONCAT(c.first_name,' ',c.last_name) AS customer_name,

            s.service_name,

            CONCAT(st.first_name,' ',st.last_name) AS staff_name,

            a.appointment_date,

            a.appointment_time,

            a.status

        FROM appointments a

        JOIN users c
            ON a.customer_id = c.user_id

        JOIN users st
            ON a.staff_id = st.user_id

        JOIN services s
            ON a.service_id = s.service_id

        ORDER BY
            a.appointment_date DESC,
            a.appointment_time ASC";

$result = mysqli_query($conn,$sql);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-calendar-check-fill"></i>

            Appointment Management

        </h1>

        <p>

            Monitor and manage customer appointments.

        </p>

    </div>

    <!-- Statistics -->

    <div class="row g-4 mb-5">

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h5>Pending</h5>

                <h2><?= $pending; ?></h2>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h5>Confirmed</h5>

                <h2><?= $confirmed; ?></h2>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h5>Completed</h5>

                <h2><?= $completed; ?></h2>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card text-center">

                <h5>Cancelled</h5>

                <h2><?= $cancelled; ?></h2>

            </div>

        </div>

    </div>

    <!-- Search -->

    <div class="dashboard-card mb-4">

        <div class="input-group">

            <span class="input-group-text">

                <i class="bi bi-search"></i>

            </span>

            <input
                type="text"
                id="appointmentSearch"
                class="form-control"
                placeholder="Search customer, service or staff...">

        </div>

    </div>

    <!-- Appointment Table -->

    <div class="dashboard-card">

        <div class="table-responsive">

            <table
                class="table table-dark table-hover align-middle"
                id="appointmentTable">

                <thead>

                    <tr>

                        <th>Customer</th>

                        <th>Service</th>

                        <th>Staff</th>

                        <th>Date</th>

                        <th>Time</th>

                        <th>Status</th>

                        <th width="90">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(mysqli_num_rows($result)>0): ?>

                        <?php while($row=mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td><?= htmlspecialchars($row["customer_name"]); ?></td>

                            <td><?= htmlspecialchars($row["service_name"]); ?></td>

                            <td><?= htmlspecialchars($row["staff_name"]); ?></td>

                            <td><?= date("d M Y",strtotime($row["appointment_date"])); ?></td>

                            <td><?= date("g:i A",strtotime($row["appointment_time"])); ?></td>

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

                            <td>

                                <a
                                   href="view_appointment.php?id=<?= $row["appointment_id"]; ?>"
                                   class="btn btn-sm btn-outline-info">

                                   <i class="bi bi-eye-fill"></i>

                                </a>

                            </td>

                        </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="7" class="text-center py-5">

                                <i class="bi bi-calendar-x"
                                   style="font-size:3rem;color:var(--gold);"></i>

                                <h4 class="mt-3">

                                    No Appointments Found

                                </h4>

                                <p>

                                    Customer bookings will appear here.

                                </p>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

const appointmentSearch = document.getElementById("appointmentSearch");

appointmentSearch.addEventListener("keyup", function(){

    const value = this.value.toLowerCase();

    document.querySelectorAll("#appointmentTable tbody tr").forEach(row=>{

        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ""
            : "none";

    });

});

</script>

<?php

require_once("../includes/footer.php");

?>