<?php
// Incluir la conexión a la base de datos y cualquier otra función necesaria
include 'bd.php'; // Ajusta el nombre según tu estructura

// Verificar si se ha proporcionado un ID de producto válido
if (isset($_GET['producto_id']) && is_numeric($_GET['producto_id'])) {
    $productoID = $_GET['producto_id'];

    // Obtener la ID del usuario actual (puedes usar tu función obtenerIdUsuarioActual)
    $usuarioID = obtenerIdUsuarioActual(); // Ajusta según tu lógica

    // Llamar a la función para agregar el producto al carrito
    if (agregarProductoAlCarrito($usuarioID, $productoID)) {
        echo "Producto agregado al carrito exitosamente.";
    } else {
        echo "Error al agregar el producto al carrito.";
    }
} else {
    echo "ID de producto no válida.";
}
?>

?>
