<?php

$pageTitle = "Add New Service";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../includes/header.php");
require_once("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- Hero Section -->
    <div class="hero-section mb-5">

        <h1>
            <i class="bi bi-plus-circle-fill"></i>
            Add New Service
        </h1>

        <p>
            Create a new salon service that customers can book.
        </p>

    </div>

    <div class="dashboard-card">

        <form action="add_service_process.php"
              method="POST"
              enctype="multipart/form-data">

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
                        placeholder="e.g. Hair Braiding"
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

                        <option value="">
                            Select Category
                        </option>

                        <option>Hair</option>
                        <option>Nails</option>
                        <option>Makeup</option>
                        <option>Facial</option>
                        <option>Spa</option>
                        <option>Barber</option>

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
                        placeholder="Describe this service..."
                        required></textarea>

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
                        placeholder="60"
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
                        placeholder="2500"
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
                                src="../assets/images/image-placeholder.png"
                                id="imagePreview"
                                class="service-preview"
                                alt="Preview">

                            <div class="upload-overlay">

                                <i class="bi bi-cloud-arrow-up-fill"></i>

                                <h5>Click to Upload Image</h5>

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
                            checked>

                        <label class="form-check-label">

                            Active

                        </label>

                    </div>

                    <div class="form-check form-check-inline">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="status"
                            value="Inactive">

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

                    <i class="bi bi-check-circle"></i>

                    Save Service

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