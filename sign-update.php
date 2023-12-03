<?php
  // Incluimos el código de sesion.php y bd.php
include "sesion.php";
include "bd.php";

// Verificamos si la sesión está iniciada y la clave 'email' está presente
if (isset($_SESSION['email'])) {
    $sesion = $_SESSION['email'];
    $conn = conectarBDUsuario();

    // Obtenemos los datos del usuario
    $usuario = consultaDatosUsuario($conn, $sesion);

    // Si la variable usuario trae datos, los declaramos en variables y las mostramos con echo
    if ($usuario !== null) {
        $nombre = $usuario['nombre'];
        $rol = $usuario['rol'];
        $direccion = $usuario['direccion'];
        $altura = $usuario['altura'];
        $piso = $usuario['piso'];
        $barrio = $usuario['barrio'];
        $id = $usuario['id_cliente'];

        echo "User Email: $sesion // ";
        echo "User Name: $nombre // ";
        echo "User Id: $id // ";
        echo "User Role: $rol // ";
        echo "Dirección de envío: $direccion // ";
        echo "Altura: $altura // ";
        echo "Piso: $piso // ";
        echo "Barrio: $barrio";
    } else {
        echo "Error al obtener datos del usuario.";
    }

    // Verificamos si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Guardamos los datos del formulario
    $nuevoEmail = $_POST["email"];
    $nuevoApellido = $_POST["apellido"];
    $nuevoNombre = $_POST["nombre"];
    $nuevoDireccion = $_POST["direccion"];
    $nuevoAltura = $_POST["altura"];
    $nuevoPiso = $_POST["piso"];
    $nuevoBarrio = $_POST["barrio"];

  // Llamamos a la función para actualizar los datos del usuario
  $actualizado = actualizarUsuario($conn, $nuevoEmail, $nuevoApellido, $nuevoNombre, $nuevoDireccion, $nuevoAltura, $nuevoBarrio, $nuevoPiso);
  
  // Cerramos conexión '$conn' de base de datos
  cerrarBDConexion($conn);

    if ($actualizado) {
      echo "Datos actualizados correctamente.";
      header("Location: sign-edit.php");
    } else {
      echo "Error al actualizar los datos.";
    }
}

} else {
    echo "No hay sesión iniciada.";
}
?>
