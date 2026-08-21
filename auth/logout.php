<?php

session_start();

/* Remove only authentication-related session variables */

unset($_SESSION["user_id"]);
unset($_SESSION["first_name"]);
unset($_SESSION["last_name"]);
unset($_SESSION["email"]);
unset($_SESSION["role"]);
unset($_SESSION["profile_picture"]);
unset($_SESSION["login_time"]);

/* Show a one-time success message */

$_SESSION["success"] = "You have been logged out successfully.";

/* Regenerate the session ID for security */

session_regenerate_id(true);

/* Redirect */

header("Location: login.php");

exit();

?>