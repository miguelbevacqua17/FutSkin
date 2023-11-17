<?php

include "bd.php";     
include "sesion.php";
  //session_start();

function main() {
      // Obtengo los datos cargados en el formulario.
      $apellido = $_POST['apellido'];       
      $nombre = $_POST['nombre']; 
      $email = $_SESSION['email']; 
      $password = $_POST['contrasena']; 
  
    // abrir conexión a base de datos, en este caso 'bd_usuario'
    $conn = conectarBDUsuario();
    // Ejecutar consulta
    $resultado = consultarUsuario($conn,$email,$password);
    
    if($resultado!=NULL && $resultado->num_rows>0){  
      $filasAfectadas = actualizarUsuario($conn,$email,$password,$apellido,$nombre );
   
          //SI TODO OK
          // Redirecciono al usuario a la página principal del sitio.
          header("HTTP/1.1 302 Moved Temporarily"); 
          header("Location: principal.php"); 
  
    } else{
      // SI ERROR
      header("Location: sign-edit.php"); 
    }
  
      // cerrar conexión '$conn' de base de datos
      cerrarBDConexion($conn);

}
main();

?>