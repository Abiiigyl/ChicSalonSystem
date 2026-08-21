<?php

$pageTitle = "Manage Staff Schedules";
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
   Get All Schedules
========================== */

$sql = "SELECT

            ss.schedule_id,
            ss.work_date,
            ss.start_time,
            ss.end_time,

            u.first_name,
            u.last_name,
            u.specialization

        FROM staff_schedules ss

        JOIN users u
            ON ss.staff_id = u.user_id

        ORDER BY
            ss.work_date ASC,
            ss.start_time ASC";

$result = mysqli_query($conn, $sql);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-calendar-week-fill"></i>

            Staff Schedule Management

        </h1>

        <p>

            Assign, update and manage employee work schedules.

        </p>

    </div>

    <div class="dashboard-card">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3>

                Staff Schedules

            </h3>

            <a href="assign_schedule.php"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                Assign Schedule

            </a>

        </div>

        <?php if(mysqli_num_rows($result) > 0){ ?>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Staff Member</th>

                        <th>Specialization</th>

                        <th>Date</th>

                        <th>Start</th>

                        <th>End</th>

                        <th class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php while($schedule = mysqli_fetch_assoc($result)){ ?>

                    <tr>

                        <td>

                            <?= htmlspecialchars($schedule["first_name"] . " " . $schedule["last_name"]); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($schedule["specialization"]); ?>

                        </td>

                        <td>

                            <?= date("d M Y", strtotime($schedule["work_date"])); ?>

                        </td>

                        <td>

                            <?= date("g:i A", strtotime($schedule["start_time"])); ?>

                        </td>

                        <td>

                            <?= date("g:i A", strtotime($schedule["end_time"])); ?>

                        </td>

                        <td class="text-center">

                            <a href="edit_schedule.php?id=<?= $schedule["schedule_id"]; ?>"
                               class="btn btn-sm btn-warning">

                                <i class="bi bi-pencil-square"></i>

                                Edit

                            </a>

                            <a href="delete_schedule.php?id=<?= $schedule["schedule_id"]; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this schedule?');">

                                <i class="bi bi-trash"></i>

                                Delete

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

                No Staff Schedules Found

            </h3>

            <p>

                Start by assigning a work schedule to your staff.

            </p>

            <a href="assign_schedule.php"
               class="btn btn-primary mt-3">

                <i class="bi bi-plus-circle"></i>

                Assign First Schedule

            </a>

        </div>

        <?php } ?>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>