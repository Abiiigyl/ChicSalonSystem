<?php

require_once("../includes/auth_check.php");
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: book_appointments.php");
    exit();

}

/* Logged-in Customer */

$customer_id = $_SESSION["user_id"];

/* Form Data */

$service_id = (int)$_POST["service_id"];
$staff_id = (int)$_POST["staff_id"];

$appointment_date = $_POST["appointment_date"];
$appointment_time = $_POST["appointment_time"];

$notes = trim($_POST["notes"]);

/* Basic Validation */

if (

    empty($service_id) ||

    empty($staff_id) ||

    empty($appointment_date) ||

    empty($appointment_time)

){

    die("Please complete all required booking information.");

}

/* Prevent Booking in the Past */

$currentDate = date("Y-m-d");

if($appointment_date < $currentDate){

    die("Invalid appointment date.");

}

/* Check if Staff Already Booked */

$sql = "SELECT appointment_id

        FROM appointments

        WHERE staff_id = ?

        AND appointment_date = ?

        AND appointment_time = ?

        AND status IN ('Pending','Confirmed')";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

    $stmt,

    "iss",

    $staff_id,

    $appointment_date,

    $appointment_time

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)>0){

    die("Sorry, this staff member is already booked for that time.");

}

/* Save Appointment */

$status = "Pending";

$sql = "INSERT INTO appointments

(

customer_id,

staff_id,

service_id,

appointment_date,

appointment_time,

notes,

status

)

VALUES

(

?,?,?,?,?,?,?

)";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

    $stmt,

    "iiissss",

    $customer_id,

    $staff_id,

    $service_id,

    $appointment_date,

    $appointment_time,

    $notes,

    $status

);

if(mysqli_stmt_execute($stmt)){

    header("Location: my_appointments.php?success=booked");

    exit();

}else{

    die("Booking failed: ".mysqli_error($conn));

}

?>