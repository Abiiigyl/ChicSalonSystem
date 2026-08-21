<?php

require_once("../includes/auth_check.php");
require_once("../config/database.php");

if ($_SESSION["role"] != "staff") {

    header("Location: ../login.php");
    exit();

}

if (!isset($_GET["id"]) || !isset($_GET["status"])) {

    header("Location: appointments.php");
    exit();

}

$appointment_id = (int)$_GET["id"];
$staff_id = $_SESSION["user_id"];

$status = $_GET["status"];

/* ==========================
   Allowed Statuses
========================== */

$allowed_statuses = [

    "Completed",
    "No Show"

];

if (!in_array($status, $allowed_statuses)) {

    header("Location: appointments.php");
    exit();

}

/* ==========================
   Verify Appointment
========================== */

$sql = "SELECT appointment_id

        FROM appointments

        WHERE appointment_id = ?

        AND staff_id = ?

        AND status = 'Confirmed'";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "ii", $appointment_id, $staff_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    header("Location: appointments.php");
    exit();

}

/* ==========================
   Update Status
========================== */

$sql = "UPDATE appointments

        SET status = ?

        WHERE appointment_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "si", $status, $appointment_id);

if (mysqli_stmt_execute($stmt)) {

    header("Location: view_appointment.php?id=" . $appointment_id);
    exit();

} else {

    echo "<script>

            alert('Unable to update appointment status.');

            window.history.back();

          </script>";

}

?>