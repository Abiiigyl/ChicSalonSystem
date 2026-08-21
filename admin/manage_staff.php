<?php

$pageTitle = "Manage Staff";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

/* Get Staff Members */

$sql = "SELECT *
        FROM users
        WHERE role = 'staff'
        ORDER BY first_name ASC";

$result = mysqli_query($conn, $sql);

?>

<div class="container py-5">

    <!-- Hero Section -->

    <div class="hero-section mb-5">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <h1>

                    <i class="bi bi-people-fill"></i>

                    Staff Management

                </h1>

                <p>

                    Manage salon staff accounts and their specializations.

                </p>

            </div>

            <a href="manage_schedules.php"
                class="btn btn-outline-light me-2">

                    <i class="bi bi-calendar-week"></i>

                    Manage Schedules

                </a>

            <a href="add_staff.php"
               class="btn btn-primary">

                <i class="bi bi-person-plus-fill"></i>

                Add Staff

            </a>

        </div>

    </div>

    <!-- Search -->

    <div class="dashboard-card mb-4">

        <div class="input-group">

            <span class="input-group-text">

                <i class="bi bi-search"></i>

            </span>

            <input
                type="text"
                id="staffSearch"
                class="form-control"
                placeholder="Search staff members...">

        </div>

    </div>

    <!-- Staff Table -->

    <div class="dashboard-card">

        <div class="table-responsive">

            <table
                class="table table-dark table-hover align-middle"
                id="staffTable">

                <thead>

                    <tr>

                        <th>Name</th>

                        <th>Specialization</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Status</th>

                        <th width="150">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(mysqli_num_rows($result) > 0): ?>

                        <?php while($staff = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>

                                    <?= htmlspecialchars($staff["first_name"] . " " . $staff["last_name"]); ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($staff["specialization"] ?: "Not Assigned"); ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($staff["email"]); ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($staff["phone"]); ?>

                                </td>

                                <td>

                                    <?php if($staff["is_active"]): ?>

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">

                                            Inactive

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a
                                        href="edit_staff.php?id=<?= $staff["user_id"]; ?>"
                                        class="btn btn-sm btn-outline-warning">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>

                                    <a
                                        href="delete_staff.php?id=<?= $staff["user_id"]; ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this staff member?');">

                                        <i class="bi bi-trash"></i>

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6" class="text-center py-5">

                                <i class="bi bi-people"
                                   style="font-size:3rem;color:var(--gold);"></i>

                                <h4 class="mt-3">

                                    No Staff Members Found

                                </h4>

                                <p>

                                    Click "Add Staff" to create your first staff account.

                                </p>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

const searchInput = document.getElementById("staffSearch");

searchInput.addEventListener("keyup", function(){

    const filter = this.value.toLowerCase();

    const rows = document.querySelectorAll("#staffTable tbody tr");

    rows.forEach(function(row){

        row.style.display = row.innerText.toLowerCase().includes(filter)
            ? ""
            : "none";

    });

});

</script>

<?php

require_once("../includes/footer.php");

?>