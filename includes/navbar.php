<?php

$currentPage = basename($_SERVER["PHP_SELF"]);

$role = $_SESSION["role"];

switch ($role) {

    case "admin":

        $dashboard = "../admin/dashboard.php";

        $links = [

            "Dashboard" => "../admin/dashboard.php",

            "Customers" => "../admin/manage_customers.php",

            "Staff" => "../admin/manage_staff.php",

            "Services" => "../admin/manage_services.php",

            "Appointments" => "../admin/manage_appointments.php",

            "Reports" => "../admin/reports.php"

        ];

        break;

    case "staff":

        $dashboard = "../staff/dashboard.php";

        $links = [

            "Dashboard" => "../staff/dashboard.php",

            "Appointments" => "../staff/appointments.php",

            "Schedule" => "../staff/schedule.php",

            "Profile" => "../staff/profile.php"

        ];

        break;

    default:

        $dashboard = "../customer/dashboard.php";

        $links = [

            "Dashboard" => "../customer/dashboard.php",

            "Book Appointment" => "../customer/book_appointments.php",

            "My Appointments" => "../customer/my_appointments.php",

            "My Profile" => "../customer/profile.php"

        ];

}
?>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">

    <div class="container">

        <a class="navbar-brand" href="<?= $dashboard ?>">

            <i class="bi bi-scissors"></i>

            Chic Groomers Salon

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <?php foreach ($links as $name => $url):

                    $active = (basename($url) == $currentPage) ? "active" : "";

                ?>

                    <li class="nav-item">

                        <a class="nav-link <?= $active ?>"

                           href="<?= $url ?>">

                            <?= htmlspecialchars($name) ?>

                        </a>

                    </li>

                <?php endforeach; ?>

            </ul>

            <div class="dropdown">

                <a class="nav-link dropdown-toggle text-light"

                   href="#"

                   role="button"

                   data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle"></i>

                    Welcome,

                    <?= htmlspecialchars($_SESSION["first_name"]); ?>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>

                        <span class="dropdown-item-text">

                            <strong>

                                <?= ucfirst(htmlspecialchars($_SESSION["role"])); ?>

                            </strong>

                        </span>

                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <a class="dropdown-item"

                           href="<?= ($role == "customer") ? "../customer/profile.php" : (($role == "staff") ? "../staff/profile.php" : "#"); ?>">

                            <i class="bi bi-person"></i>

                            My Profile

                        </a>

                    </li>

                    <li>

                        <a class="dropdown-item text-danger"

                           href="../auth/logout.php">

                            <i class="bi bi-box-arrow-right"></i>

                            Logout

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>