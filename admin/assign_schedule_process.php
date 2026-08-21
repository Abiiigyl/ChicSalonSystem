<?php

require_once("../includes/auth_check.php");
require_once("../config/database.php");

if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: assign_schedule.php");
    exit();

}

$staff_id = (int)$_POST["staff_id"];
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
   Verify Staff Member Exists
========================== */

$sql = "SELECT user_id

        FROM users

        WHERE user_id = ?

        AND role = 'staff'

        AND is_active = 1";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $staff_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    echo "<script>

            alert('Invalid staff member selected.');

            window.location='assign_schedule.php';

          </script>";

    exit();

}

/* ==========================
   Prevent Duplicate Schedule
========================== */

$sql = "SELECT schedule_id

        FROM staff_schedules

        WHERE staff_id = ?

        AND work_date = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "is", $staff_id, $work_date);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    echo "<script>

            alert('This staff member already has a schedule for the selected date.');

            window.history.back();

          </script>";

    exit();

}

/* ==========================
   Insert Schedule
========================== */

$sql = "INSERT INTO staff_schedules

        (staff_id, work_date, start_time, end_time)

        VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "isss",

    $staff_id,
    $work_date,
    $start_time,
    $end_time

);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

            alert('Schedule assigned successfully.');

            window.location='manage_schedules.php';

          </script>";

} else {

    echo "<script>

            alert('Failed to assign schedule.');

            window.history.back();

          </script>";

}

?>