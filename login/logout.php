<?php
session_start();
// Unset all session variables
$_SESSION = array();
header("Location: login.php");
exit();
// Destroy the session
//session_destroy();

// Redirect to login page

?>