<?php

$pageTitle = "Manage Customers";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");

if($_SESSION["role"] != "admin"){
    header("Location: ../login.php");
    exit();
}

require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

$search = "";

if(isset($_GET["search"])){
    $search = trim($_GET["search"]);
}

$sql = "SELECT
            user_id,
            first_name,
            last_name,
            email,
            phone,
            created_at,
            is_active
        FROM users
        WHERE role='customer'";

if($search != ""){

    $search = mysqli_real_escape_string($conn,$search);

    $sql .= " AND (
                first_name LIKE '%$search%'
                OR last_name LIKE '%$search%'
                OR email LIKE '%$search%'
                OR phone LIKE '%$search%'
            )";
}

$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn,$sql);

?>

<div class="container py-5">

    <div class="hero-section mb-5">

        <h1>

            <i class="bi bi-people-fill"></i>

            Manage Customers

        </h1>

        <p>

            View and manage registered salon customers.

        </p>

    </div>

    <div class="dashboard-card mb-4">

        <form method="GET">

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search customers..."
                    value="<?= htmlspecialchars($search); ?>">

                <button class="btn btn-primary">

                    <i class="bi bi-search"></i>

                </button>

            </div>

        </form>

    </div>

    <div class="dashboard-card">

        <div class="table-responsive">

            <table class="table table-dark table-hover align-middle">

                <thead>

                    <tr>

                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result)>0): ?>

                    <?php while($customer=mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>

                            <?= htmlspecialchars($customer["first_name"]." ".$customer["last_name"]); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($customer["email"]); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($customer["phone"]); ?>

                        </td>

                        <td>

                            <?= date("d M Y",strtotime($customer["created_at"])); ?>

                        </td>

                        <td>

                            <?php if($customer["is_active"]): ?>

                                <span class="badge bg-success">

                                    Active

                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a
                                href="view_customer.php?id=<?= $customer["user_id"]; ?>"
                                class="btn btn-sm btn-info">

                                <i class="bi bi-eye"></i>

                            </a>

                            <?php if($customer["is_active"]): ?>

                                <a
                                    href="toggle_customer.php?id=<?= $customer["user_id"]; ?>"
                                    class="btn btn-sm btn-warning"
                                    onclick="return confirm('Deactivate this customer account?');">

                                    <i class="bi bi-person-x-fill"></i>

                                </a>

                            <?php else: ?>

                                <a
                                    href="toggle_customer.php?id=<?= $customer["user_id"]; ?>"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Activate this customer account?');">

                                    <i class="bi bi-person-check-fill"></i>

                                </a>

                            <?php endif; ?>


                        </td>

                    </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6" class="text-center">

                            No customers found.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

require_once("../includes/footer.php");

?>