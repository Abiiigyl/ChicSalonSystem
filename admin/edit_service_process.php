<?php

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

/* Check Request */

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: manage_services.php");
    exit();
}

/* Get Form Data */

$service_id   = (int) $_POST["service_id"];
$service_name = trim($_POST["service_name"]);
$category     = trim($_POST["category"]);
$description  = trim($_POST["description"]);
$duration     = (int) $_POST["duration"];
$price        = (float) $_POST["price"];
$status       = trim($_POST["status"]);

/* Validate */

if (
    empty($service_name) ||
    empty($category) ||
    empty($description) ||
    $duration <= 0 ||
    $price <= 0
) {

    die("Please fill in all required fields.");

}

/* Get Current Image */

$sql = "SELECT image
        FROM services
        WHERE service_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $service_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$service = mysqli_fetch_assoc($result);

$imageName = $service["image"];

/* Upload New Image (Optional) */

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    $allowed = ["jpg", "jpeg", "png", "webp"];

    $extension = strtolower(
        pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, $allowed)) {

        die("Invalid image format.");

    }

    /* Delete old image */

    if (!empty($imageName)) {

        $oldImage = "../assets/images/services/" . $imageName;

        if (file_exists($oldImage)) {

            unlink($oldImage);

        }

    }

    /* Save new image */

    $imageName = uniqid("service_", true) . "." . $extension;

    $uploadPath = "../assets/images/services/" . $imageName;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)) {

        die("Failed to upload image.");

    }

}

/* Update Service */

$sql = "UPDATE services
        SET
            service_name = ?,
            category = ?,
            description = ?,
            duration = ?,
            image = ?,
            price = ?,
            status = ?
        WHERE service_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "sssisdsi",

    $service_name,
    $category,
    $description,
    $duration,
    $imageName,
    $price,
    $status,
    $service_id

);

if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_services.php?success=updated");
    exit();

} else {

    die("Error updating service: " . mysqli_error($conn));

}

?>