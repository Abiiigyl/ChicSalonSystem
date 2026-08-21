<?php

session_start();

require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: register.php");
    exit();

}

$first_name = trim($_POST["first_name"]);
$last_name = trim($_POST["last_name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

if (
    empty($first_name) ||
    empty($last_name) ||
    empty($email) ||
    empty($phone) ||
    empty($password) ||
    empty($confirm_password)
) {

    $_SESSION["error"] = "Please fill in all the required fields.";

    header("Location: register.php");
    exit();

}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["error"] = "Please enter a valid email address.";

    header("Location: register.php");
    exit();

}

if ($password !== $confirm_password) {

    $_SESSION["error"] = "Passwords do not match.";

    header("Location: register.php");
    exit();

}

if (strlen($password) < 8) {

    $_SESSION["error"] = "Password must be at least 8 characters long.";

    header("Location: register.php");
    exit();

}

$check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ?");

$check_email->bind_param("s", $email);

$check_email->execute();

$result = $check_email->get_result();

if ($result->num_rows > 0) {

    $_SESSION["error"] = "An account with this email already exists.";

    $check_email->close();

    header("Location: register.php");
    exit();

}

$check_email->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$role = "customer";

$is_active = 1;

$profile_picture = NULL;

$stmt = $conn->prepare("
INSERT INTO users
(first_name, last_name, email, phone, password, role, profile_picture, is_active)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssssi",
    $first_name,
    $last_name,
    $email,
    $phone,
    $hashed_password,
    $role,
    $profile_picture,
    $is_active
);

if ($stmt->execute()) {

    $_SESSION["success"] = "Account created successfully! Please log in.";

    $stmt->close();
    $conn->close();

    header("Location: login.php");
    exit();

} else {

    $_SESSION["error"] = "Registration failed. Please try again.";

    $stmt->close();
    $conn->close();

    header("Location: register.php");
    exit();

}

?>