<?php

  include "sesion.php";

session_start(); // Start the session

// Unset all session variables
$_SESSION = array();
echo $_SESSION;

// Destroy the session
session_destroy();

// Redirect to the login page or any other appropriate location
header("Location: principal.php");
exit();
?>
