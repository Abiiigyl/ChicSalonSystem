<?php

$pageTitle = "Assign Staff Schedule";
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
   Get Active Staff
========================== */

$sql = "SELECT

            user_id,
            first_name,
            last_name,
            specialization

        FROM users

        WHERE role = 'staff'
        AND is_active = 1

        ORDER BY first_name, last_name";

$result = mysqli_query($conn, $sql);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-calendar-plus-fill"></i>

            Assign Staff Schedule

        </h1>

        <p>

            Assign a working shift to a member of staff.

        </p>

    </div>

    <div class="dashboard-card">

        <form action="assign_schedule_process.php"
              method="POST">

            <div class="row g-4">

                <!-- Staff Member -->

                <div class="col-md-12">

                    <label class="form-label">

                        Staff Member

                    </label>

                    <select
                        name="staff_id"
                        class="form-select"
                        required>

                        <option value="">

                            -- Select Staff Member --

                        </option>

                        <?php while($staff = mysqli_fetch_assoc($result)){ ?>

                            <option value="<?= $staff["user_id"]; ?>">

                                <?= htmlspecialchars(
                                    $staff["first_name"] . " " .
                                    $staff["last_name"] .
                                    " (" .
                                    $staff["specialization"] .
                                    ")"
                                ); ?>

                            </option>

                        <?php } ?>

                    </select>

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

                    <i class="bi bi-calendar-check"></i>

                    Assign Schedule

                </button>

            </div>

        </form>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>