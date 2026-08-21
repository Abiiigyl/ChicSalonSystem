<?php

$pageTitle = "My Schedule";
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
   Get Schedule
========================== */

$sql = "SELECT
            work_date,
            start_time,
            end_time
        FROM staff_schedules
        WHERE staff_id = ?
        ORDER BY work_date ASC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $staff_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-calendar3"></i>

            My Schedule

        </h1>

        <p>

            View your assigned working days and shifts.

        </p>

    </div>

    <div class="dashboard-card">

        <?php if(mysqli_num_rows($result) > 0){ ?>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Date</th>

                        <th>Day</th>

                        <th>Start Time</th>

                        <th>End Time</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($schedule = mysqli_fetch_assoc($result)){ ?>

                    <?php

                    $today = date("Y-m-d");

                    $status = ($schedule["work_date"] == $today)
                        ? '<span class="badge bg-success">Today</span>'
                        : '<span class="badge bg-secondary">Upcoming</span>';

                    ?>

                    <tr>

                        <td>

                            <?= date("d M Y", strtotime($schedule["work_date"])); ?>

                        </td>

                        <td>

                            <?= date("l", strtotime($schedule["work_date"])); ?>

                        </td>

                        <td>

                            <?= date("g:i A", strtotime($schedule["start_time"])); ?>

                        </td>

                        <td>

                            <?= date("g:i A", strtotime($schedule["end_time"])); ?>

                        </td>

                        <td>

                            <?= $status; ?>

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

                    No Schedule Available

                </h3>

                <p>

                    Your manager has not assigned any work shifts yet.

                </p>

            </div>

        <?php } ?>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>