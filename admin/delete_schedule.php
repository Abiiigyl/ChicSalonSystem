<?php

require_once("../includes/auth_check.php");
require_once("../config/database.php");

if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}

if (!isset($_GET["id"])) {

    header("Location: manage_schedules.php");
    exit();

}

$schedule_id = (int)$_GET["id"];

/* ==========================
   Verify Schedule Exists
========================== */

$sql = "SELECT schedule_id

        FROM staff_schedules

        WHERE schedule_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $schedule_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    echo "<script>

            alert('Schedule not found.');

            window.location='manage_schedules.php';

          </script>";

    exit();

}

/* ==========================
   Delete Schedule
========================== */

$sql = "DELETE FROM staff_schedules

        WHERE schedule_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $schedule_id);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

            alert('Schedule deleted successfully.');

            window.location='manage_schedules.php';

          </script>";

} else {

    echo "<script>

            alert('Unable to delete schedule.');

            window.location='manage_schedules.php';

          </script>";

}

?>