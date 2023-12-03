<?php

// Iniciamos la sesion
session_start();
if (isset($_GET["accion"])){
    if ($_GET["accion"]=="cerrarSesion" && isset($_SESSION['email'])){
      cerrarSesion('email');
    }
}

 // Cerramos la sesion
function cerrarSesion($clave){
    // Elimina la variable clave en sesión.
    unset($_SESSION[$clave]); 
    // Elimina la sesion.
    session_destroy();
    // Redirecciona a la página de signin. 
    header("Location: signin.html");
}

 // Creamos la sesion
function crearSesion($clave, $valor){
    // Guardar en la sesión el email del usuario.
    $_SESSION[$clave] = $valor;
    
    // Redireccionamos al usuario a la página principal del sitio.
    header("Location: principal.php"); 
}

function controlarSesion(){
// Controlamos si el usuario ya está logueado en el sistema.
  $sesionUsuario=NULL;
    if(isset($_SESSION['email'])){
      // Le asignamos la sesion correspondiente al usuario
      $sesionUsuario=$_SESSION['email'];      
    } else {
      // Si no está logueado lo redireccion a la página de login.
      header("Location: signin.html"); 
    }
    return $sesionUsuario;
}
  
?>