<?php

session_start();

require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: login.php");
    exit();

}

$email = trim($_POST["email"]);
$password = $_POST["password"];

if (empty($email) || empty($password)) {

    $_SESSION["error"] = "Please enter your email and password.";

    header("Location: login.php");
    exit();

}

$stmt = $conn->prepare("
SELECT user_id,
       first_name,
       last_name,
       email,
       password,
       role,
       is_active,
       profile_picture
FROM users
WHERE email = ?
");

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    if ($user["is_active"] == 0) {

        $_SESSION["error"] = "Your account has been deactivated. Please contact the administrator.";

        header("Location: login.php");
        exit();

    }

    if (password_verify($password, $user["password"])) {

        // First check whether the account is active
    if ($user["is_active"] == 0) {

        $_SESSION["error"] = "Your account has been deactivated. Please contact the salon.";

        header("Location: login.php");
        exit();

    }

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["first_name"] = $user["first_name"];
        $_SESSION["last_name"] = $user["last_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["profile_picture"] = $user["profile_picture"];
        $_SESSION["login_time"] = time();

        if ($user["role"] == "admin") {

            header("Location: ../admin/dashboard.php");

        } elseif ($user["role"] == "staff") {

            header("Location: ../staff/dashboard.php");

        } else {

            header("Location: ../customer/dashboard.php");

        }

        exit();

    } else {

        $_SESSION["error"] = "Invalid email or password.";

        header("Location: login.php");
        exit();

    }

} else {

    $_SESSION["error"] = "Invalid email or password.";

    header("Location: login.php");
    exit();

}

$stmt->close();
$conn->close();

?>