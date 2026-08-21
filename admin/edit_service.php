<?php

$pageTitle = "Edit Service";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");

/* Validate Service ID */
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_services.php");
    exit();
}

$service_id = (int)$_GET["id"];

/* Get Service */
$sql = "SELECT * FROM services WHERE service_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $service_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: manage_services.php");
    exit();
}

$service = mysqli_fetch_assoc($result);

require_once("../includes/header.php");
require_once("../includes/navbar.php");

/* Current Image */

$image = !empty($service["image"])
    ? "../assets/images/services/" . $service["image"]
    : "../assets/images/image-placeholder.png";

?>

<div class="container py-5">

    <!-- Hero Section -->
    <div class="hero-section mb-5">

        <h1>
            <i class="bi bi-pencil-square"></i>
            Edit Service
        </h1>

        <p>
            Update the details of this salon service.
        </p>

    </div>

    <div class="dashboard-card">

        <form action="edit_service_process.php"
              method="POST"
              enctype="multipart/form-data">

            <input
                type="hidden"
                name="service_id"
                value="<?= $service["service_id"]; ?>">

            <div class="row g-4">

                <!-- Service Name -->
                <div class="col-md-6">

                    <label class="form-label">
                        Service Name
                    </label>

                    <input
                        type="text"
                        name="service_name"
                        class="form-control"
                        value="<?= htmlspecialchars($service["service_name"]); ?>"
                        required>

                </div>

                <!-- Category -->
                <div class="col-md-6">

                    <label class="form-label">
                        Category
                    </label>

                    <select
                        name="category"
                        class="form-select"
                        required>

                        <option value="Hair" <?= $service["category"]=="Hair" ? "selected" : ""; ?>>Hair</option>

                        <option value="Nails" <?= $service["category"]=="Nails" ? "selected" : ""; ?>>Nails</option>

                        <option value="Makeup" <?= $service["category"]=="Makeup" ? "selected" : ""; ?>>Makeup</option>

                        <option value="Facial" <?= $service["category"]=="Facial" ? "selected" : ""; ?>>Facial</option>

                        <option value="Spa" <?= $service["category"]=="Spa" ? "selected" : ""; ?>>Spa</option>

                        <option value="Barber" <?= $service["category"]=="Barber" ? "selected" : ""; ?>>Barber</option>

                    </select>

                </div>

                <!-- Description -->
                <div class="col-12">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"
                        required><?= htmlspecialchars($service["description"]); ?></textarea>

                </div>

                <!-- Duration -->
                <div class="col-md-6">

                    <label class="form-label">
                        Duration (Minutes)
                    </label>

                    <input
                        type="number"
                        name="duration"
                        class="form-control"
                        min="15"
                        step="15"
                        value="<?= $service["duration"]; ?>"
                        required>

                    <small class="text-muted">

                        Example: 60 = 1 hour

                    </small>

                </div>

                <!-- Price -->
                <div class="col-md-6">

                    <label class="form-label">
                        Price (KSh)
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        min="0"
                        step="50"
                        value="<?= $service["price"]; ?>"
                        required>

                </div>

                <!-- Service Image -->
                <div class="col-12">

                    <label class="form-label">

                        Service Image

                    </label>

                    <div class="image-upload-container">

                        <label for="imageInput" class="upload-box">

                            <img
                                src="<?= $image; ?>"
                                id="imagePreview"
                                class="service-preview"
                                alt="Preview">

                            <div class="upload-overlay">

                                <i class="bi bi-cloud-arrow-up-fill"></i>

                                <h5>Click to Change Image</h5>

                                <p>JPG, PNG or WEBP</p>

                            </div>

                        </label>

                        <input
                            type="file"
                            id="imageInput"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            hidden>

                    </div>

                </div>

                <!-- Status -->
                <div class="col-12">

                    <label class="form-label d-block mb-3">

                        Status

                    </label>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="Active"
                            <?= $service["status"]=="Active" ? "checked" : ""; ?>>

                        <label class="form-check-label">

                            Active

                        </label>

                    </div>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="Inactive"
                            <?= $service["status"]=="Inactive" ? "checked" : ""; ?>>

                        <label class="form-check-label">

                            Inactive

                        </label>

                    </div>

                </div>

            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-between">

                <a href="manage_services.php"
                   class="btn btn-outline-light">

                    <i class="bi bi-arrow-left"></i>

                    Back

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-save"></i>

                    Update Service

                </button>

            </div>

        </form>

    </div>

</div>

<script>

const imageInput = document.getElementById("imageInput");

const imagePreview = document.getElementById("imagePreview");

const uploadOverlay = document.querySelector(".upload-overlay");

imageInput.addEventListener("change", function(){

    const file = this.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(e){

            imagePreview.src = e.target.result;

            uploadOverlay.style.opacity = "0";

        };

        reader.readAsDataURL(file);

    }

});

</script>

<?php

require_once("../includes/footer.php");

?>