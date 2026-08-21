<?php

require_once("../config/database.php");

header("Content-Type: application/json");

/* ==========================
   Validate Input
========================== */

if (
    !isset($_GET["service_id"]) ||
    !isset($_GET["date"]) ||
    !isset($_GET["time"]) ||
    !is_numeric($_GET["service_id"])
) {

    echo json_encode([]);
    exit();

}

$service_id = (int)$_GET["service_id"];
$date = $_GET["date"];
$time = $_GET["time"];

/* ==========================
   Get Service Category
========================== */

$sql = "SELECT category
        FROM services
        WHERE service_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $service_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    echo json_encode([]);
    exit();

}

$service = mysqli_fetch_assoc($result);

$category = $service["category"];

/* ==========================
   Find Available Staff
========================== */

$sql = "SELECT

            u.user_id,
            u.first_name,
            u.last_name

        FROM users u

        INNER JOIN staff_schedules ss
            ON u.user_id = ss.staff_id

        WHERE

            u.role = 'staff'

            AND u.is_active = 1

            AND u.specialization = ?

            AND ss.work_date = ?

            AND ? BETWEEN ss.start_time AND ss.end_time

            AND NOT EXISTS (

                SELECT 1

                FROM appointments a

                WHERE a.staff_id = u.user_id

                AND a.appointment_date = ?

                AND a.appointment_time = ?

                AND a.status IN ('Pending','Confirmed')

            )

        ORDER BY

            u.first_name,
            u.last_name";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "sssss",

    $category,
    $date,
    $time,
    $date,
    $time

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$staff = [];

while ($row = mysqli_fetch_assoc($result)) {

    $staff[] = $row;

}

echo json_encode($staff);

?>