<?php
  // Incluimos el código de bd.php
include "bd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ventaPedidoBtn'])) {
    require_once 'bd.php';

    // Funcion para cambiar el estado del pedido a 'venta'
    function ventaPedido($usuarioId) {
        $conn = conectarBDUsuario();

        if (!$conn) {
            return false;
        }

        $estadoVenta = 'venta';

        // Update SQL
        $sql = "UPDATE pedidos SET estado = ? WHERE cliente_fk = ? AND estado = 'pendiente'";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $estadoVenta, $usuarioId);
            $result = $stmt->execute();

            if ($result) {
                $stmt->close();
                cerrarBDConexion($conn);
                return true; 
            } else {
                cerrarBDConexion($conn);
                return false;
            }
        } else {
            cerrarBDConexion($conn);
            return false;
        }
    }

    // Verificamos errores
    $usuarioId = $_POST['usuarioId'];
    if (ventaPedido($usuarioId)) {
        header('Location: gracias.html'); // Redireccionamos a gracias.html
        exit();
    } else {
        echo 'Error finalizando venta.';
    }
}
?>
