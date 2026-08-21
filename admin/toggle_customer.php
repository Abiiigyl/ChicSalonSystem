<?php

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

if (!isset($_GET["id"])) {
    header("Location: manage_customers.php");
    exit();
}

$user_id = intval($_GET["id"]);

/* ==========================
   Get Current Status
========================== */

$sql = "SELECT is_active
        FROM users
        WHERE user_id = ?
        AND role = 'customer'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: manage_customers.php");
    exit();
}

$customer = mysqli_fetch_assoc($result);

$newStatus = ($customer["is_active"] == 1) ? 0 : 1;

/* ==========================
   Update Status
========================== */

$sql = "UPDATE users
        SET is_active = ?
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $newStatus, $user_id);
mysqli_stmt_execute($stmt);

header("Location: manage_customers.php");
exit();

?>