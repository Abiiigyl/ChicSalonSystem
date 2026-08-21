<?php

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

/* Check Request */

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: manage_staff.php");
    exit();
}

/* Get Form Data */

$user_id        = (int) $_POST["user_id"];
$first_name     = trim($_POST["first_name"]);
$last_name      = trim($_POST["last_name"]);
$email          = trim($_POST["email"]);
$phone          = trim($_POST["phone"]);
$specialization = trim($_POST["specialization"]);
$is_active      = (int) $_POST["is_active"];

/* Validation */

if (
    empty($first_name) ||
    empty($last_name) ||
    empty($email) ||
    empty($phone) ||
    empty($specialization)
) {

    die("Please fill in all required fields.");

}

/* Check if email already exists for another user */

$sql = "SELECT user_id
        FROM users
        WHERE email = ?
        AND user_id != ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "si", $email, $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    die("Another user already has this email address.");

}

/* Update Staff */

$sql = "UPDATE users
        SET
            first_name = ?,
            last_name = ?,
            email = ?,
            phone = ?,
            specialization = ?,
            is_active = ?
        WHERE user_id = ?
        AND role = 'staff'";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "sssssii",

    $first_name,
    $last_name,
    $email,
    $phone,
    $specialization,
    $is_active,
    $user_id

);

if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_staff.php?success=updated");
    exit();

} else {

    die("Error updating staff member: " . mysqli_error($conn));

}

?>