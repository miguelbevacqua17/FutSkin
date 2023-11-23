<?php

include "sesion.php";
include "bd.php";

$conn = conectarBDUsuario();

// Verificar si la sesión está iniciada y la clave 'email' está presente
if (isset($_SESSION['email'])) {
    $sesion = $_SESSION['email'];

    // Verificar si la consulta de datos del usuario es exitosa
    $usuario = consultaDatosUsuario($conn, $sesion);
    
    if ($usuario !== null) {
        $nombre = $usuario['nombre'];
        $rol = $usuario['rol'];

        echo "User Email: $sesion // ";
        echo "User Name: $nombre // ";
        echo "User Role: $rol";

        // Resto del código que usa $nombre y $rol
    } else {
        echo "Error al obtener datos del usuario.";
    }
} else {
    echo "No hay sesión iniciada.";
}


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