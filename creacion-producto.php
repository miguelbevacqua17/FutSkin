<?php
  include "sesion.php";
  include "bd.php";

// Verifica si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos del formulario
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $categoriaID = $_POST["categoria"];
    $descuento = $_POST["descuento"];
    $descripcion = $_POST["descripcion"];

  // Llamar a la función para insertar el nuevo producto
  $resultado = insertarNuevoProducto($categoriaID, $nombre, $imagen, $descripcion, $precioLista, $descuento, $stock);
  
  // Verificar el resultado
  if ($resultado) {
      echo "Nuevo producto agregado correctamente.";
  } else {
      echo "Error al agregar el nuevo producto.";
  }

?>
