<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ventaPedidoBtn'])) {
    // Include your database connection logic
    require_once 'bd.php';

    // Function to change the estado of the pedido to 'venta'
    function ventaPedido($usuarioId) {
        $conn = conectarBDUsuario();

        if (!$conn) {
            return false; // Unable to connect to the database
        }

        $estadoVenta = 'venta';

        // Corrected SQL query with prepared statement
        $sql = "UPDATE pedidos SET estado = ? WHERE cliente_fk = ? AND estado = 'pendiente'";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $estadoVenta, $usuarioId);
            $result = $stmt->execute();

            if ($result) {
                $stmt->close();
                cerrarBDConexion($conn);
                return true; // Return true on success
            } else {
                cerrarBDConexion($conn);
                return false; // Error executing the statement
            }
        } else {
            cerrarBDConexion($conn);
            return false; // Error preparing statement
        }
    }

    // Handle changing the estado to 'venta'
    $usuarioId = $_POST['usuarioId'];
    if (ventaPedido($usuarioId)) {
        // Estado changed successfully, redirect to thank you page
        header('Location: gracias.html');
        exit();
    } else {
        // Handle change failure (if needed)
        echo 'Error finalizando venta.';
    }
}
?>
