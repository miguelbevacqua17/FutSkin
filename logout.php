<?php
  // Incluimos el código de sesion.php
include "sesion.php";

session_start(); // Arrancamos la sesion

// Eliminamos las variables de sesion
$_SESSION = array();
echo $_SESSION;

// Cerramos la sesion
session_destroy();

// Redirigimos a principal.php
header("Location: principal.php");
exit();
  
?>
