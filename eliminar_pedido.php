<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarPedidoBtn'])) {
    
    // Incluimos el código de bd.php
    require_once 'bd.php';

    // Funcion para eliminar el pedido (le pasamos el ID de ese pedido)
    function eliminarPedido($pedidoID) {
        $conn = conectarBDUsuario();

        if (!$conn) {
            return false;
        }
    
        // Declaramos el estado que queremos que actualice en la base ('eliminado')
        $estadoEliminado = 'eliminado';

        // Update SQL
        $sql = "UPDATE pedidos SET estado = ? WHERE id = ? AND estado = 'pendiente'";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $estadoEliminado, $pedidoID);
            $result = $stmt->execute();

            $stmt->close();
            cerrarBDConexion($conn);

            return $result;
        } else {
            cerrarBDConexion($conn);
            return false;
        }
    }

    // Realizamos el cambio de estado en la base de datos
    $pedidoID = $_POST['pedidoID'];
    if (eliminarPedido($pedidoID)) {
        // Se eliminó el producto
        header('Location: carrito.php');
        exit();
    } else {
        // Error al eliminar el producto
        echo 'Error deleting pedido.';
    }
}
?>
