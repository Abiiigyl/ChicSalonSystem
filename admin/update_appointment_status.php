<?php

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

/* Validate Input */

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"]) ||
    !isset($_GET["status"])
) {

    header("Location: manage_appointments.php");
    exit();

}

$appointment_id = (int) $_GET["id"];

$status = trim($_GET["status"]);

/* Allowed Status Values */

$allowedStatus = [

    "Pending",
    "Confirmed",
    "Completed",
    "Cancelled"

];

if (!in_array($status, $allowedStatus)) {

    header("Location: manage_appointments.php");
    exit();

}

/* Check Appointment Exists */

$sql = "SELECT appointment_id
        FROM appointments
        WHERE appointment_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $appointment_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    header("Location: manage_appointments.php");
    exit();

}

/* Update Status */

$sql = "UPDATE appointments
        SET status = ?
        WHERE appointment_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "si",

    $status,
    $appointment_id

);

if (mysqli_stmt_execute($stmt)) {

    header("Location: view_appointment.php?id=" . $appointment_id . "&success=status_updated");
    exit();

} else {

    die("Error updating appointment status: " . mysqli_error($conn));

}

?>