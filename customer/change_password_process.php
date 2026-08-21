<?php

require_once("../includes/auth_check.php");
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: change_password.php");
    exit();

}

$user_id = $_SESSION["user_id"];

$current_password = $_POST["current_password"];
$new_password = $_POST["new_password"];
$confirm_password = $_POST["confirm_password"];

/* ==========================
   Get Current Password
========================== */

$sql = "SELECT password
        FROM users
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

/* ==========================
   Verify Current Password
========================== */

if (!password_verify($current_password, $user["password"])) {

    echo "<script>
            alert('Current password is incorrect.');
            window.history.back();
          </script>";

    exit();

}

/* ==========================
   Check Password Match
========================== */

if ($new_password != $confirm_password) {

    echo "<script>
            alert('New passwords do not match.');
            window.history.back();
          </script>";

    exit();

}

/* ==========================
   Password Length
========================== */

if (strlen($new_password) < 8) {

    echo "<script>
            alert('Password must be at least 8 characters long.');
            window.history.back();
          </script>";

    exit();

}

/* ==========================
   Prevent Same Password
========================== */

if (password_verify($new_password, $user["password"])) {

    echo "<script>
            alert('Your new password must be different from your current password.');
            window.history.back();
          </script>";

    exit();

}

/* ==========================
   Update Password
========================== */

$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE users
        SET password = ?
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $new_hash, $user_id);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>
            alert('Password changed successfully.');
            window.location='profile.php';
          </script>";

} else {

    echo "<script>
            alert('Unable to update password.');
            window.history.back();
          </script>";

}

?>