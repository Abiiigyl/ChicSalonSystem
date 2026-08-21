<?php

$pageTitle = "My Appointments";
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

$sql = "SELECT

            a.appointment_id,
            a.appointment_date,
            a.appointment_time,
            a.status,

            CONCAT(c.first_name,' ',c.last_name) AS customer_name,

            s.service_name

        FROM appointments a

        JOIN users c
            ON a.customer_id = c.user_id

        JOIN services s
            ON a.service_id = s.service_id

        WHERE a.staff_id = ?

        ORDER BY

            a.appointment_date ASC,
            a.appointment_time ASC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $staff_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<div class="container py-5">

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-calendar-check-fill"></i>

            My Appointments

        </h1>

        <p>

            View and manage your assigned appointments.

        </p>

    </div>

    <div class="dashboard-card">

        <?php if(mysqli_num_rows($result) > 0){ ?>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Customer</th>

                        <th>Service</th>

                        <th>Date</th>

                        <th>Time</th>

                        <th>Status</th>

                        <th class="text-center">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($appointment = mysqli_fetch_assoc($result)){ ?>

                    <tr>

                        <td>

                            <?= htmlspecialchars($appointment["customer_name"]); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($appointment["service_name"]); ?>

                        </td>

                        <td>

                            <?= date("d M Y", strtotime($appointment["appointment_date"])); ?>

                        </td>

                        <td>

                            <?= date("g:i A", strtotime($appointment["appointment_time"])); ?>

                        </td>

                        <td>

                        <?php

                        switch($appointment["status"]){

                            case "Pending":

                                echo '<span class="badge bg-warning text-dark">Pending</span>';

                                break;

                            case "Confirmed":

                                echo '<span class="badge bg-primary">Confirmed</span>';

                                break;

                            case "Completed":

                                echo '<span class="badge bg-success">Completed</span>';

                                break;

                            case "No Show":

                                echo '<span class="badge bg-danger">No Show</span>';

                                break;

                            case "Cancelled":

                                echo '<span class="badge bg-secondary">Cancelled</span>';

                                break;

                        }

                        ?>

                        </td>

                        <td class="text-center">

                            <a href="view_appointment.php?id=<?= $appointment["appointment_id"]; ?>"

                               class="btn btn-sm btn-primary">

                                <i class="bi bi-eye-fill"></i>

                                View

                            </a>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

        <?php } else { ?>

            <div class="text-center py-5">

                <i class="bi bi-calendar-x"
                   style="font-size:4rem;color:var(--gold);"></i>

                <h3 class="mt-4">

                    No Appointments Assigned

                </h3>

                <p>

                    You currently have no appointments scheduled.

                </p>

            </div>

        <?php } ?>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>