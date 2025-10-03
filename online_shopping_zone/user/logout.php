<?php
session_start(); // Start the session

// Remove all session variables and end the session
session_unset();
session_destroy();

// Redirect to login page after logout
header('Location: login.php');
exit();
?>
