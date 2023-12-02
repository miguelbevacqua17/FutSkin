<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarPedidoBtn'])) {
    // Include your database connection logic
    require_once 'bd.php';

    // Function to eliminate the pedido
    function eliminarPedido($pedidoID) {
        $conn = conectarBDUsuario();

        if (!$conn) {
            return false; // Unable to connect to the database
        }

        $estadoEliminado = 'eliminado';

        $sql = "UPDATE pedidos SET estado = ? WHERE cliente_fk = ? AND estado = 'pendiente'";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $estadoEliminado, $pedidoID);
            $result = $stmt->execute();

            $stmt->close();
            cerrarBDConexion($conn);

            return $result; // Return true on success, false on failure
        } else {
            cerrarBDConexion($conn);
            return false; // Error preparing statement
        }
    }

    // Handle pedido deletion
    $pedidoID = $_POST['pedidoID'];
    if (eliminarPedido($pedidoID)) {
        // Pedido deleted successfully, you can redirect or display a success message
        header('Location: carrito.php');
        exit();
    } else {
        // Handle deletion failure (if needed)
        echo 'Error deleting pedido.';
    }
}
?>
