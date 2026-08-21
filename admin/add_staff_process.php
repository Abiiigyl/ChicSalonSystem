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

$first_name       = trim($_POST["first_name"]);
$last_name        = trim($_POST["last_name"]);
$email            = trim($_POST["email"]);
$phone            = trim($_POST["phone"]);
$password         = $_POST["password"];
$confirm_password = $_POST["confirm_password"];
$specialization   = trim($_POST["specialization"]);
$is_active        = (int) $_POST["is_active"];

$role = "staff";

/* Validation */

if (
    empty($first_name) ||
    empty($last_name) ||
    empty($email) ||
    empty($phone) ||
    empty($password) ||
    empty($specialization)
) {

    die("Please fill in all required fields.");

}

/* Password Match */

if ($password !== $confirm_password) {

    die("Passwords do not match.");

}

/* Check Email */

$sql = "SELECT user_id
        FROM users
        WHERE email = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    die("An account with this email already exists.");

}

/* Hash Password */

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

/* Insert Staff */

$sql = "INSERT INTO users
        (
            first_name,
            last_name,
            email,
            phone,
            password,
            role,
            specialization,
            is_active
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "sssssssi",

    $first_name,
    $last_name,
    $email,
    $phone,
    $hashed_password,
    $role,
    $specialization,
    $is_active

);

if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_staff.php?success=added");
    exit();

} else {

    die("Error adding staff member: " . mysqli_error($conn));

}

?>