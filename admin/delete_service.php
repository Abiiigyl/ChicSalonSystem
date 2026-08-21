<?php

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

/* Validate ID */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_services.php");
    exit();
}

$service_id = (int)$_GET["id"];

/* Get image filename */

$sql = "SELECT image
        FROM services
        WHERE service_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $service_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    header("Location: manage_services.php");
    exit();

}

$service = mysqli_fetch_assoc($result);

/* Delete image from folder */

if (!empty($service["image"])) {

    $imagePath = "../assets/images/services/" . $service["image"];

    if (file_exists($imagePath)) {

        unlink($imagePath);

    }

}

/* Delete service */

$sql = "DELETE FROM services
        WHERE service_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $service_id);

if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_services.php?success=deleted");
    exit();

} else {

    die("Error deleting service.");

}

?>