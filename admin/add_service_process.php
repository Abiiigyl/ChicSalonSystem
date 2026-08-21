<?php

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}

require_once("../config/database.php");

/* Check request */

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: add_service.php");
    exit();

}

/* Get form data */

$service_name = trim($_POST["service_name"]);
$category     = trim($_POST["category"]);
$description  = trim($_POST["description"]);
$duration     = (int) $_POST["duration"];
$price        = (float) $_POST["price"];
$status       = $_POST["status"];

/* Basic Validation */

if (
    empty($service_name) ||
    empty($category) ||
    empty($description) ||
    $duration <= 0 ||
    $price <= 0
) {

    die("Please fill in all required fields correctly.");

}

/* Image Upload */

$imageName = NULL;

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    $allowed = ["jpg", "jpeg", "png", "webp"];

    $fileName = $_FILES["image"]["name"];

    $fileTmp = $_FILES["image"]["tmp_name"];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed)) {

        die("Invalid image format.");
    }

    $imageName = uniqid("service_", true) . "." . $extension;

    $uploadPath = "../assets/images/services/" . $imageName;

    if (!move_uploaded_file($fileTmp, $uploadPath)) {

        die("Failed to upload image.");

    }

}

/* Insert Service */

$sql = "INSERT INTO services
(
    service_name,
    category,
    description,
    duration,
    image,
    price,
    status
)
VALUES
(
    ?, ?, ?, ?, ?, ?, ?
)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssisds",
    $service_name,
    $category,
    $description,
    $duration,
    $imageName,
    $price,
    $status
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_services.php?success=added");
    exit();

} else {

    die("Error adding service: " . mysqli_error($conn));

}

?>