<?php

include "bd.php";     //https://www.w3schools.com/php/php_includes.asp
include "sesion.php";
// session_start();
  
    // Obtengo los datos cargados en el formulario de signin.
    // se puede agregar la funcion isset()
    $email = $_POST['email'];       //"mariano@gmail.com";
    $password = $_POST['password']; //"1234";
    // abrir conexión a base de datos, en este caso 'bd_usuario'
    $conn = conectarBDUsuario();
    // Ejecutar consulta
    // Consulta SQL para verificar las credenciales del usuario
    $resultado = consultarUsuario($conn,$email,$password);

// cerrar conexión '$conn' de base de datos
cerrarBDConexion($conn);
  
if ($resultado != NULL && $resultado->num_rows > 0){  
    crearSesion('email', $email);
    echo 'Se creó la sesion';
} else {
    echo "$email $password";
    echo 'El email o password es incorrecto, <a href="signin.php">vuelva a intentarlo</a>.<br/>';
}

?>
