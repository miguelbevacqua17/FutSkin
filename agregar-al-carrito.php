<?php
  // Incluimos el código de bd.php
include 'bd.php';

// Verificamos si se proporciona un ID de un producto válido
if (isset($_GET['producto_id']) && is_numeric($_GET['producto_id'])) {
    $productoID = $_GET['producto_id'];

    // Obtenemos el ID del usuario actual con la funcion obtenerIdUsuarioActual()
    $usuarioID = obtenerIdUsuarioActual(); // Ajusta según tu lógica

    // Llamamos a la función para agregar el producto al carrito agregarProductoAlCarrito()
    if (agregarProductoAlCarrito($usuarioID, $productoID)) {
        echo "Producto agregado al carrito exitosamente.";
    } else {
        echo "Error al agregar el producto al carrito.";
    }
} else {
    echo "ID de producto no válida.";
}
?>
