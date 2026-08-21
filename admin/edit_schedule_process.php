<?php

require_once("../includes/auth_check.php");
require_once("../config/database.php");

if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: manage_schedules.php");
    exit();

}

$schedule_id = (int)$_POST["schedule_id"];
$work_date = $_POST["work_date"];
$start_time = $_POST["start_time"];
$end_time = $_POST["end_time"];

/* ==========================
   Validate Times
========================== */

if ($end_time <= $start_time) {

    echo "<script>

            alert('End time must be later than the start time.');

            window.history.back();

          </script>";

    exit();

}

/* ==========================
   Get Staff ID
========================== */

$sql = "SELECT staff_id

        FROM staff_schedules

        WHERE schedule_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $schedule_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    header("Location: manage_schedules.php");
    exit();

}

$schedule = mysqli_fetch_assoc($result);

$staff_id = $schedule["staff_id"];

/* ==========================
   Prevent Duplicate Schedule
========================== */

$sql = "SELECT schedule_id

        FROM staff_schedules

        WHERE staff_id = ?

        AND work_date = ?

        AND schedule_id != ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "isi", $staff_id, $work_date, $schedule_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    echo "<script>

            alert('This staff member already has a schedule for that date.');

            window.history.back();

          </script>";

    exit();

}

/* ==========================
   Update Schedule
========================== */

$sql = "UPDATE staff_schedules

        SET

            work_date = ?,
            start_time = ?,
            end_time = ?

        WHERE schedule_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "sssi",

    $work_date,
    $start_time,
    $end_time,
    $schedule_id

);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

            alert('Schedule updated successfully.');

            window.location='manage_schedules.php';

          </script>";

} else {

    echo "<script>

            alert('Failed to update schedule.');

            window.history.back();

          </script>";

}

?>