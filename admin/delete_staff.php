<?php

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

/* Validate User ID */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_staff.php");
    exit();
}

$user_id = (int)$_GET["id"];

/* Prevent deleting yourself */

if ($user_id == $_SESSION["user_id"]) {

    header("Location: manage_staff.php?error=self_delete");
    exit();

}

/* Check staff member exists */

$sql = "SELECT user_id
        FROM users
        WHERE user_id = ?
        AND role = 'staff'";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    header("Location: manage_staff.php");
    exit();

}

/* Optional: Delete staff schedules first */

$sql = "DELETE FROM staff_schedules
        WHERE staff_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

/* Delete staff account */

$sql = "DELETE FROM users
        WHERE user_id = ?
        AND role = 'staff'";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_staff.php?success=deleted");
    exit();

} else {

    die("Error deleting staff member: " . mysqli_error($conn));

}

?>