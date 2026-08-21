<?php

$pageTitle = "Edit Staff Schedule";
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

    header("Location: manage_schedules.php");
    exit();

}

$schedule_id = (int)$_GET["id"];

/* ==========================
   Get Schedule Details
========================== */

$sql = "SELECT

            ss.*,

            u.first_name,
            u.last_name,
            u.specialization

        FROM staff_schedules ss

        JOIN users u
            ON ss.staff_id = u.user_id

        WHERE ss.schedule_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $schedule_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){

    header("Location: manage_schedules.php");
    exit();

}

$schedule = mysqli_fetch_assoc($result);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-pencil-square"></i>

            Edit Staff Schedule

        </h1>

        <p>

            Update the selected staff member's working shift.

        </p>

    </div>

    <div class="dashboard-card">

        <form action="edit_schedule_process.php"
              method="POST">

            <input
                type="hidden"
                name="schedule_id"
                value="<?= $schedule["schedule_id"]; ?>">

            <div class="row g-4">

                <!-- Staff Member -->

                <div class="col-12">

                    <label class="form-label">

                        Staff Member

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($schedule["first_name"] . " " . $schedule["last_name"] . " (" . $schedule["specialization"] . ")"); ?>"
                        readonly>

                </div>

                <!-- Work Date -->

                <div class="col-md-4">

                    <label class="form-label">

                        Work Date

                    </label>

                    <input
                        type="date"
                        name="work_date"
                        class="form-control"
                        value="<?= $schedule["work_date"]; ?>"
                        min="<?= date('Y-m-d'); ?>"
                        required>

                </div>

                <!-- Start Time -->

                <div class="col-md-4">

                    <label class="form-label">

                        Start Time

                    </label>

                    <input
                        type="time"
                        name="start_time"
                        class="form-control"
                        value="<?= $schedule["start_time"]; ?>"
                        min="08:00"
                        max="18:00"
                        required>

                </div>

                <!-- End Time -->

                <div class="col-md-4">

                    <label class="form-label">

                        End Time

                    </label>

                    <input
                        type="time"
                        name="end_time"
                        class="form-control"
                        value="<?= $schedule["end_time"]; ?>"
                        min="08:00"
                        max="18:00"
                        required>

                </div>

            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-between">

                <a href="manage_schedules.php"
                   class="btn btn-outline-light">

                    <i class="bi bi-arrow-left"></i>

                    Back

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    Save Changes

                </button>

            </div>

        </form>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>