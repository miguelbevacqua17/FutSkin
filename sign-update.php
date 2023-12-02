<?php
include "sesion.php";
include "bd.php";

// Verificar si la sesión está iniciada y la clave 'email' está presente
if (isset($_SESSION['email'])) {
    $sesion = $_SESSION['email'];
    $conn = conectarBDUsuario();

    // Obtener los datos del usuario
    $usuario = consultaDatosUsuario($conn, $sesion);
    if ($usuario !== null) {
        $nombre = $usuario['nombre'];
        $rol = $usuario['rol'];
        $direccion = $usuario['direccion'];
        $altura = $usuario['altura'];
        $piso = $usuario['piso'];
        $barrio = $usuario['barrio'];

        echo "User Email: $sesion // ";
        echo "User Name: $nombre // ";
        echo "User Role: $rol // ";
        echo "Dirección de envío: $direccion // ";
        echo "Altura: $altura // ";
        echo "Piso: $piso // ";
        echo "Barrio: $barrio";
    } else {
        echo "Error al obtener datos del usuario.";
    }

    // Verificar si el formulario ha sido enviado
// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recoger los datos del formulario
  $nuevoEmail = $_POST["email"];
  $nuevoApellido = $_POST["apellido"];
  $nuevoNombre = $_POST["nombre"];
  $nuevoDireccion = $_POST["direccion"];
  $nuevoAltura = $_POST["altura"];
  $nuevoPiso = $_POST["piso"];
  $nuevoBarrio = $_POST["barrio"];

  // ... (código para obtener datos del usuario)

  // Llamar a la función para actualizar los datos del usuario
  $actualizado = actualizarUsuario($conn, $nuevoEmail, $nuevoApellido, $nuevoNombre, $nuevoDireccion, $nuevoAltura, $nuevoBarrio, $nuevoPiso);
  // Cerrar conexión '$conn' de base de datos
  
  cerrarBDConexion($conn);

  if ($actualizado) {
      echo "Datos actualizados correctamente.";
      // Puedes redirigir al usuario a otra página o mostrar un mensaje de éxito.
      header("Location: sign-edit.php");
  } else {
      echo "Error al actualizar los datos.";
      // Puedes mostrar un mensaje de error o realizar alguna otra acción.
  }
}


} else {
    echo "No hay sesión iniciada.";
}
?>
