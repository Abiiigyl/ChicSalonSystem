<?php

$pageTitle = "Service Management";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

/* Greeting */

$hour = date("H");

if ($hour < 12) {

    $greeting = "Good Morning";

} elseif ($hour < 18) {

    $greeting = "Good Afternoon";

} else {

    $greeting = "Good Evening";

}

/* Fetch Services */

$sql = "SELECT * FROM services ORDER BY service_name ASC";

$result = mysqli_query($conn, $sql);

?>

<div class="container py-5">

    <!-- Hero -->

    <div class="hero-section mb-5">

        <h1>

            ✂️ Service Management

        </h1>

        <p>

            Manage all salon services offered to your customers.

        </p>

    </div>

    <!-- Top Bar -->

    <div class="dashboard-card mb-4">

        <div class="row align-items-center g-3">

            <div class="col-lg-8">

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-search"></i>

                    </span>

                    <input
                        type="text"
                        id="serviceSearch"
                        class="form-control"
                        placeholder="Search services...">

                </div>

            </div>

            <div class="col-lg-4 text-lg-end">

                <a href="add_service.php"
                   class="btn btn-primary">

                    <i class="bi bi-plus-circle"></i>

                    Add New Service

                </a>

            </div>

        </div>

    </div>

    <!-- Services Table -->

    <div class="dashboard-card">

        <div class="table-responsive">

            <table class="table table-dark table-hover align-middle mb-0"
                   id="servicesTable">

                <thead>

                    <tr>

                        <th>Service</th>

                        <th>Category</th>

                        <th>Duration</th>

                        <th>Price</th>

                        <th>Status</th>

                        <th class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php

                if(mysqli_num_rows($result) > 0){

                    while($row = mysqli_fetch_assoc($result)){

                        $duration = $row["duration"];

                        if($duration >= 60){

                            $hours = floor($duration / 60);

                            $minutes = $duration % 60;

                            if($minutes > 0){

                                $displayDuration = $hours . " hr " . $minutes . " mins";

                            }else{

                                $displayDuration = $hours . " hr";

                            }

                        }else{

                            $displayDuration = $duration . " mins";

                        }

                ?>

                    <tr>

                        <td>

                            <?= htmlspecialchars($row["service_name"]); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($row["category"]); ?>

                        </td>

                        <td>

                            <?= $displayDuration; ?>

                        </td>

                        <td>

                            KSh <?= number_format($row["price"],2); ?>

                        </td>

                        <td>

                            <?php if($row["status"]=="Active"){ ?>

                                <span class="badge bg-success">

                                    Active

                                </span>

                            <?php } else { ?>

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            <?php } ?>

                        </td>

                        <td class="text-center">

                            <a href="edit_service.php?id=<?= $row["service_id"]; ?>"
                               class="btn btn-outline-warning btn-sm">

                                <i class="bi bi-pencil-square"></i>

                            </a>

                            <a href="delete_service.php?id=<?= $row["service_id"]; ?>"
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.');">

                                <i class="bi bi-trash"></i>

                            </a>

                        </td>

                    </tr>

                <?php

                    }

                }else{

                ?>

                    <tr>

                        <td colspan="6"
                            class="text-center py-5">

                            <i class="bi bi-scissors"
                               style="font-size:3rem;color:var(--gold);"></i>

                            <h5 class="mt-3">

                                No services found.

                            </h5>

                            <p>

                                Start by adding your first salon service.

                            </p>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

const search = document.getElementById("serviceSearch");

search.addEventListener("keyup", function(){

    const value = this.value.toLowerCase();

    const rows = document.querySelectorAll("#servicesTable tbody tr");

    rows.forEach(row=>{

        row.style.display =
            row.innerText.toLowerCase().includes(value)
            ? ""
            : "none";

    });

});

</script>

<?php

require_once("../includes/footer.php");

?>