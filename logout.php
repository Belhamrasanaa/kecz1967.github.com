<?php
session_start();

// Destroy session variables
$_SESSION = array();

// If you want to destroy the session cookies as well, uncomment the following lines
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit;
