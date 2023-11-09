<?php

session_start();
if (isset($_REQUEST["accion"])){  
    if ($_REQUEST["accion"]=="cerrarSesion" && isset($_SESSION['email'])){
      cerrarSesion('email');
    }    
}

function cerrarSesion($clave){
  // Elimina la variable clave en sesión.
  unset($_SESSION[$clave]); 
 
  // Elimina la sesion.
  session_destroy();
   
  // Redirecciona a la página de signin. 
  header("Location: signin.html");
}

function crearSesion($clave, $valor){
    // Guardar en la sesión el email del usuario.
    $_SESSION[$clave] = $valor;
     
    // Redirecciono al usuario a la página principal del sitio.
    // header("HTTP/1.1 302 Moved Temporarily");  //REDIRRECCIÓN: https://desarrolloweb.com/articulos/redireccion-php-301-302.html 
    header("Location: principal.php"); 
}

function controlarSesion(){
// Controlo si el usuario ya está logueado en el sistema.

  $sesionUsuario=NULL;
  if(isset($_SESSION['email'])){
    // Le asigno la sesion correspondiente al usuario
    $sesionUsuario=$_SESSION['email'];    
    
  }else{

    // Si no está logueado lo redireccion a la página de login.
    // para hacer el signin
    //header("HTTP/1.1 302 Moved Temporarily"); 
    header("Location: signin.html"); 
  }
  
  return $sesionUsuario;
}

?>