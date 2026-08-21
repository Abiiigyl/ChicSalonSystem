<?php

require_once("../includes/auth_check.php");
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: profile.php");
    exit();

}

$user_id = $_SESSION["user_id"];

$first_name = trim($_POST["first_name"]);
$last_name  = trim($_POST["last_name"]);
$email      = trim($_POST["email"]);
$phone      = trim($_POST["phone"]);

/* ==========================
   Check Email Uniqueness
========================== */

$sql = "SELECT user_id
        FROM users
        WHERE email = ?
        AND user_id != ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    echo "<script>
            alert('That email address is already in use.');
            window.history.back();
          </script>";
    exit();

}

/* ==========================
   Update Profile
========================== */

$sql = "UPDATE users

        SET

            first_name = ?,
            last_name = ?,
            email = ?,
            phone = ?

        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "ssssi",

    $first_name,
    $last_name,
    $email,
    $phone,
    $user_id

);

if (mysqli_stmt_execute($stmt)) {

    // Update session so navbar reflects changes immediately

    $_SESSION["first_name"] = $first_name;

    header("Location: profile.php");
    exit();

} else {

    echo "<script>
            alert('Failed to update profile.');
            window.history.back();
          </script>";

}

?>