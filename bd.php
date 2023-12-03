<?php

function conectarBDUsuario(){   
    // Datos para conectar a la base de datos.
    $nombreServidor = "mysql-futskin.alwaysdata.net";
    $nombreUsuario = "futskin";
    $passwordBaseDeDatos = "futskin-bd2023";
    $nombreBaseDeDatos = "futskin_bd";

    // Crear conexión con la base de datos.
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $conn = new mysqli($nombreServidor, $nombreUsuario, $passwordBaseDeDatos, $nombreBaseDeDatos);      
    } catch (Exception $e) {
        echo 'ERROR:'.$e->getMessage();
        $conn=NULL;
    }   
    return $conn;
}


function cerrarBDConexion($conn){
    if ($conn!=NULL){
        $conn->close();
    }    
}


function consultarUsuario($conn, $email, $password) {
    $resultado = NULL;
    if ($conn != NULL) {
        $formato = "SELECT * FROM clientes WHERE email = ? AND contrasena = ?";
        $stmt = $conn->prepare($formato);
        if ($stmt) {
            $stmt->bind_param("ss", $email, $password);
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $stmt->close();
            } else {
                echo "Error in executing statement: " . $stmt->error;
            }
        } else {
            echo "Error in preparing statement: " . $conn->error;
        }
    }
    return $resultado;
}


function verficarEmail($conn, $email) {
    $resultado = NULL;
    if ($conn != NULL) {
        // Confección de la consulta preparada para evitar inyecciones SQL
        $formato = "SELECT * FROM clientes WHERE email= ?";
        // Preparar la consulta
        $stmt = $conn->prepare($formato);
        // Verificar si la consulta se preparó correctamente
        if ($stmt) {
            // Vincular el parámetro
            $stmt->bind_param("s", $email);
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Obtener el resultado
                $resultado = $stmt->get_result();
                // Cerrar la consulta preparada
                $stmt->close();
            } else {
                echo "Error al ejecutar la consulta: " . $stmt->error;
            }
        } else {
            echo "SQL Query: $formato //";
            echo "email: $email //";
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    }
    return $resultado;
}


function agregarUsuario($conn,$apellido,$nombre,$email,$password) {
    $filasAfectadas = 0;
    if ($conn != NULL) {
        /* Crear una sentencia preparada */
        if ($stmt = $conn->prepare("INSERT INTO clientes (apellido, nombre, email, contrasena) VALUES (?, ?, ?, ?)")) {
            /* Ligar parámetros para marcadores */
            $stmt->bind_param('ssss',$apellido,$nombre,$email,$password);
            /* Ejecutar la consulta */
            $stmt->execute();
            /* Obtener la cantidad de filas afectadas en la inserción */
            $filasAfectadas = $stmt->affected_rows;
            /* Cerrar sentencia */
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    }
    return $filasAfectadas;
}


function consultaDatosUsuario($conn, $email) {
    $resultado = NULL;
    if ($conn != NULL) {
        // Confección del string de la Consulta segura para evitar inyecciones SQL.
        $sql = "SELECT clientes.*, envios.* FROM clientes 
                LEFT JOIN envios ON clientes.envio_fk = envios.id
                WHERE clientes.email = ? ";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("s", $email);
            // Ejecutar la consulta SQL
            $stmt->execute();
            // Obtener el resultado
            $result = $stmt->get_result();
            // Check if any rows were returned
            if ($result->num_rows > 0) {
                $resultado = $result->fetch_assoc();
            } else {
                // Handle the case where no rows were found
                error_log("No rows found for email: $email");
            }
            // Cerrar el conjunto de resultados y el statement
            $result->close();
            $stmt->close();
        } else {
            // Handle the error, e.g., log the error
            error_log("Error preparing statement: " . $conn->error);
        }
    }
    return $resultado;
}


function actualizarUsuario($conn, $email, $apellido, $nombre, $direccion, $altura, $barrio, $piso, $actualizarClientes = true, $actualizarEnvios = true) {
    $filasAfectadas = 0;

    if ($conn != NULL) {
        // Iniciar una transacción para garantizar la consistencia de ambas actualizaciones
        $conn->begin_transaction();

        try {
            // Actualizar datos en la tabla clientes si se solicita
            if ($actualizarClientes) {
                $stmtClientes = $conn->prepare("UPDATE clientes SET apellido = ?, nombre = ? WHERE email = ?");
                $stmtClientes->bind_param('sss', $apellido, $nombre, $email);
                $stmtClientes->execute();
                $filasAfectadasClientes = $stmtClientes->affected_rows;
                $stmtClientes->close();
            } else {
                $filasAfectadasClientes = 0; // No se intentó actualizar clientes
            }

            // Verificar si hay un registro existente en la tabla envios
            $stmtCheckEnvio = $conn->prepare("SELECT id FROM envios WHERE id = (SELECT envio_fk FROM clientes WHERE email = ?)");
            $stmtCheckEnvio->bind_param('s', $email);
            $stmtCheckEnvio->execute();
            $stmtCheckEnvio->store_result();
            $numRows = $stmtCheckEnvio->num_rows;

            if ($numRows > 0) {
                // Existe un registro en envios, realizar la actualización
                $stmtEnvios = $conn->prepare("UPDATE envios SET direccion = ?, altura = ?, barrio = ?, piso = ? WHERE id = (SELECT envio_fk FROM clientes WHERE email = ?)");
                $stmtEnvios->bind_param('sssss', $direccion, $altura, $barrio, $piso, $email);
                $stmtEnvios->execute();
                $filasAfectadasEnvios = $stmtEnvios->affected_rows;
                $stmtEnvios->close();
            } else {
                // No existe un registro en envios, crear uno nuevo
                $stmtNuevoEnvio = $conn->prepare("INSERT INTO envios (direccion, altura, barrio, piso) VALUES (?, ?, ?, ?)");
                $stmtNuevoEnvio->bind_param('ssss', $direccion, $altura, $barrio, $piso);
                $stmtNuevoEnvio->execute();
                $idNuevoEnvio = $stmtNuevoEnvio->insert_id;
                $stmtNuevoEnvio->close();

                // Actualizar el campo envio_fk en la tabla clientes
                $stmtUpdateClientes = $conn->prepare("UPDATE clientes SET envio_fk = ? WHERE email = ?");
                $stmtUpdateClientes->bind_param('ss', $idNuevoEnvio, $email);
                $stmtUpdateClientes->execute();
                $filasAfectadasEnvios = $stmtUpdateClientes->affected_rows;
                $stmtUpdateClientes->close();
            }

            $stmtCheckEnvio->close();

            // Confirmar la transacción si al menos una de las actualizaciones fue exitosa
            if ($filasAfectadasClientes > 0 || $filasAfectadasEnvios > 0) {
                $conn->commit();
                $filasAfectadas = $filasAfectadasClientes + $filasAfectadasEnvios;
            } else {
                // Revertir la transacción si nada se actualizó
                $conn->rollback();
            }

        } catch (Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante la transacción
            $conn->rollback();
            // Puedes agregar aquí el código para manejar la excepción, como loggearla o mostrar un mensaje al usuario
            echo "Error: " . $e->getMessage();
        }
    }

    return $filasAfectadas;
}



function traerCategoriasHTML() {
    // Conectar a la base de datos
    $conn = conectarBDUsuario();
    // Verificar la conexión
    if ($conn === NULL) {
        return "";
    }
    // Consultar categorías
    $sql = "SELECT * FROM categorias";
    $categorias = $conn->prepare($sql);
    if (!$categorias) {
        return "";
    }
    $categorias->execute();
    $result = $categorias->get_result();
    if (!$result) {
        return "";
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);    
    // Cerrar conexión a la base de datos
    cerrarBDConexion($conn);
    // Generar el HTML
    $html = "";
    foreach ($data as $row) { 
        $categoria = $row['nombre'];
        $descripcion_categoria = $row['descripcion'];
        $imagen = $row['imagen'];
        $id = $row['id'];
        
        $html .= '<div class="col-12 col-md-4 p-5 mt-3 text-center">'; // Agregado el estilo "text-center"
        $html .= '<a href="categoria.php?id=' . $id . '">';
        $html .= '<img src="/uploads/' . $imagen . '" class="mx-auto d-block" style="width:200px; height:200px;" alt="Categoria Image">'; // Agregado el estilo "mx-auto d-block"
        $html .= '</a>';
        $html .= '<h5 class="mt-3 mb-3">' . $categoria . '</h5>'; // Quitado el "text-center" para centrar solo verticalmente
        $html .= '</div>';
        
    }
    return $html;
}


function categorias() {
    // Conectar a la base de datos
    $conn = conectarBDUsuario();
    // Verificar la conexión
    if ($conn === NULL) {
        return "";        
    }
    // Consultar categorías
    $sql = "SELECT * FROM categorias";
    $categorias = $conn->prepare($sql);
    if (!$categorias) {
        return "";
    }
    $categorias->execute();
    $result = $categorias->get_result();
    if (!$result) {
       return "";
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    // Cerrar conexión a la base de datos
    cerrarBDConexion($conn);
    return $data;
}


// Función para generar HTML de productos
function traerProductosHTML($tipo = "default") {
    // Conectar a la base de datos
    $conn = conectarBDUsuario();
    // Verificar la conexión
    if ($conn === NULL) {
        return "";
    }
    // Consultar productos
    $sql = "SELECT * FROM productos";
    $prods = $conn->prepare($sql);
    if (!$prods) {
        return "";
    }
    $prods->execute();
    $result = $prods->get_result();
    if (!$result) {
        return "";
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    // Cerrar conexión a la base de datos
    cerrarBDConexion($conn);
    // Generar el HTML según el tipo
    $html = "";
    foreach ($data as $row) { 
        $precio = $row['precio_lista']; 
        $nombre = $row['producto'];
        $descripcion = $row['descripcion'];
        $descripcion_corta = substr($descripcion, 0, 40);

        $imagen = $row['imagen'];
        $id = $row['id'];
        if ($tipo === "detalle") {
            // HTML para vista detallada
            $html .= '<div class="col-md-4">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">'; // Agregado
            $html .= '<div class="card mb-4 product-wap rounded-0">';
            $html .= '<div class="card rounded-0">';
            $html .= '<img class="card-img rounded-0 img-fluid" src="/uploads/' . $imagen . '">';
            $html .= '<div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="h3 text-center">' . $nombre . '</a>'; // Modificado
            $html .= '<ul class="w-100 list-unstyled d-flex justify-content-between mb-0">';
            $html .= '</ul>';
            $html .= '<p class="text-right mb-0">$' . $precio . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</a>'; // Agregado
            $html .= '</div>';
        } else {
            // HTML por defecto
            $html .= '<div class="col-12 col-md-4 mb-4">';
            $html .= '<div class="card h-100 d-flex flex-column">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">'; // Agregado
            $html .= '<img src="/uploads/' . $imagen . '" class="card-img-top" alt="...">';
            $html .= '</a>';
            $html .= '<div class="card-body d-flex flex-column">';
            $html .= '<ul class="list-unstyled d-flex justify-content-between mb-2">';
            $html .= '<li class="text-muted text-right">$' . $precio . '</li>';
            $html .= '</ul>';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="h2 text-decoration-none text-dark">' . $nombre . '</a>'; // Modificado
            $html .= '<p class="card-text description-limit flex-grow-1">' . $descripcion_corta ."..." . '</p>';
            $html .= '<p class="text-muted"></p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
    }
    return $html;
}


function traerColumnaTabla($columna, $tabla) {
    // Conectar a la base de datos
    $conn = conectarBDUsuario();
    // Verificar la conexión
    if ($conn === NULL) {
        return array();
    }
    // Consultar la columna específica de la tabla
    $sql = "SELECT $columna FROM $tabla";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return array();
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        return array();
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    // Cerrar conexión a la base de datos
    cerrarBDConexion($conn);
    // Obtener solo los valores de la columna
    $valoresColumna = array_column($data, $columna);
    return $valoresColumna;
}


function obtenerDetalleProducto($productoID) {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return NULL;
    }
    $sql = "SELECT productos.*, categorias.nombre
                FROM productos
                INNER JOIN categorias ON categorias.id = productos.categoria_fk
                WHERE productos.id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return NULL;
    }
    $stmt->bind_param("i", $productoID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        cerrarBDConexion($conn);
        return NULL;
    }
    $producto = $result->fetch_assoc();
    cerrarBDConexion($conn);
    return $producto;
}



function obtenerDetalleCategoria($categoriaID) {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return NULL;
    }
    $sql = "SELECT * FROM categorias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return NULL;
    }
    $stmt->bind_param("i", $categoriaID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        cerrarBDConexion($conn);
        return NULL;
    }
    $categoria = $result->fetch_assoc();
    cerrarBDConexion($conn);
    return $categoria;
}


function obtenerProductosCarrito($usuarioID) {
    $conn = conectarBDUsuario();

    if ($conn === NULL) {
        return NULL;
    }

    $sql = "SELECT pedidos.id as pedido_id, productos.id, productos.producto, productos.imagen, productos.precio_lista, productos.descuento, pedidos.cantidad_prod
            FROM productos
            INNER JOIN pedidos ON productos.id = pedidos.producto_fk
            WHERE pedidos.cliente_fk = ? AND pedidos.estado = 'pendiente'";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        cerrarBDConexion($conn);
        return NULL;
    }

    $stmt->bind_param("i", $usuarioID);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result) {
        cerrarBDConexion($conn);
        return NULL;
    }

    $productosCarrito = $result->fetch_all(MYSQLI_ASSOC);

    cerrarBDConexion($conn);

    return $productosCarrito;
}


function calcularPrecioTotal($productosCarrito) {
    $precioTotal = 0;
    foreach ($productosCarrito as $producto) {
        // Asegúrate de que la clave 'precio_lista' existe en el array del producto
        if (array_key_exists('precio_lista', $producto) && array_key_exists('cantidad_prod', $producto)) {
            // Calcula el precio total del producto (precio unitario * cantidad)
            $precioUnitario = $producto['precio_lista'];
            $cantidad = $producto['cantidad_prod'];
            $precioProducto = $precioUnitario * $cantidad;

            // Suma el precio total del producto al precio total general
            $precioTotal += $precioProducto;
        } else {
            // Manejar el caso en el que 'precio_lista' o 'cantidad_prod' no están definidos
            echo "Las claves 'precio_lista' o 'cantidad_prod' no están definidas para este producto.";
        }
    }
    return $precioTotal;
}


function eliminarProductoDelCarrito($usuarioID, $productoID) {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return false;
    }
    // Lógica para eliminar el producto del carrito
    $sql = "DELETE FROM pedidos WHERE cliente_fk = ? AND producto_fk = ? AND estado = 'pendiente'";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        cerrarBDConexion($conn);
        return false;
    }
    $stmt->bind_param("ii", $usuarioID, $productoID);
    $success = $stmt->execute();
    cerrarBDConexion($conn);
    return $success;
}


function insertarNuevoProducto($categoriaID, $nombre, $imagen, $descripcion, $precioLista, $descuento, $stock) {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return false;
    }
    $sql = "INSERT INTO productos (categoria_fk, producto, imagen, descripcion, precio_lista, descuento, stock, deleteable) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        cerrarBDConexion($conn);
        return false;
    }
    $stmt->bind_param("isssisi", $categoriaID, $nombre, $imagen, $descripcion, $precioLista, $descuento, $stock);
    $resultado = $stmt->execute();
    // Check for errors during execution
    if (!$resultado) {
        cerrarBDConexion($conn);
        return false;
    }

    // Close the connection and return the result
    cerrarBDConexion($conn);
    return $resultado;
}


function agregarProductoAlCarrito($usuarioID, $productoID, $precio) {
    $conn = conectarBDUsuario(); // Adjust according to your connection function
    if ($conn === NULL) {
        return false;
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO pedidos (cliente_fk, producto_fk, precio_venta, estado, cantidad_prod) VALUES (?, ?, ?, 'pendiente', 1)";
    $stmt = $conn->prepare($sql);

    // Check for errors during preparation
    if (!$stmt) {
        cerrarBDConexion($conn);
        return false;
    }

    // Bind parameters
    $stmt->bind_param("iis", $usuarioID, $productoID, $precio);

    // Execute the statement and check for errors
    $resultado = $stmt->execute();

    // Check for errors during execution
    if (!$resultado) {
        cerrarBDConexion($conn);
        return false;
    }

    // Close the connection and return the result
    cerrarBDConexion($conn);
    return $resultado;
}



// Traer solo traerSoloCategorias
function obtenerCategorias() {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return array(); // Retorna un array vacío si hay un problema con la conexión
    }
    $sql = "SELECT id, nombre FROM categorias";
    $result = $conn->query($sql);
    $categorias = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[$row['id']] = $row['nombre'];
        }
    }
    cerrarBDConexion($conn);
    return $categorias;
}


// Función para generar HTML de ventas
function traerVentas() {
    // Conectar a la base de datos
    $conn = conectarBDUsuario();
    // Verificar la conexión
    if ($conn === NULL) {
        return "";
    }
    // Consultar productos
    $sql = "SELECT * FROM pedidos
            JOIN clientes ON pedidos.cliente_fk = clientes.id_cliente
            JOIN productos ON pedidos.producto_fk = productos.id";
    $prods = $conn->prepare($sql);
    if (!$prods) {
        return "";
    }
    $prods->execute();
    $result = $prods->get_result();
    if (!$result) {
        return "";
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    // Cerrar conexión a la base de datos
    cerrarBDConexion($conn);
    // Devolver los datos obtenidos
    return $data;
}

function buscarPedidosUsuario($usuarioEmail) {
    // Conectar a la base de datos
    $conn = conectarBDUsuario();

    // Verificar la conexión
    if ($conn === NULL) {
        return []; // Devolver un array vacío en caso de fallo en la conexión
    }

    try {
        // Obtener el ID del usuario utilizando su email
        $stmtUsuario = $conn->prepare("SELECT id_cliente FROM clientes WHERE email = ?");
        $stmtUsuario->bind_param('s', $usuarioEmail);
        $stmtUsuario->execute();
        $resultUsuario = $stmtUsuario->get_result();

        if (!$resultUsuario) {
            throw new Exception("Error al obtener el ID del usuario: " . $stmtUsuario->error);
        }

        $usuario = $resultUsuario->fetch_assoc();

        // Verificar si se encontró el usuario
        if (!$usuario) {
            throw new Exception("Usuario no encontrado con el email: $usuarioEmail");
        }

        // Obtener el ID del usuario
        $usuarioId = $usuario['id_cliente'];

        // Consultar los pedidos del usuario
        $sql = "SELECT pedidos.*, productos.* FROM pedidos
                JOIN productos ON pedidos.producto_fk = productos.id
                WHERE pedidos.cliente_fk = ?";

        $stmtPedidos = $conn->prepare($sql);

        // Verificar si la preparación de la consulta fue exitosa
        if (!$stmtPedidos) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmtPedidos->bind_param('i', $usuarioId);
        $stmtPedidos->execute();
        $resultPedidos = $stmtPedidos->get_result();

        // Verificar si la ejecución de la consulta fue exitosa
        if (!$resultPedidos) {
            throw new Exception("Error en la ejecución de la consulta: " . $stmtPedidos->error);
        }

        // Obtener los datos de los pedidos
        $dataPedidos = $resultPedidos->fetch_all(MYSQLI_ASSOC);

        // Cerrar la consulta y liberar los recursos
        $stmtPedidos->close();

        // Devolver los datos obtenidos
        return $dataPedidos;

    } catch (Exception $e) {
        // Manejar cualquier excepción que pueda ocurrir durante la ejecución de la función
        cerrarBDConexion($conn); // Asegurarse de cerrar la conexión en caso de error
        error_log("Error en la función buscarPedidosUsuario: " . $e->getMessage());
        return [];
    }
}





function obtenerProductosPorCategoria($conn, $categoriaId) {
    $productos = [];

    // Verificar la conexión a la base de datos
    if ($conn !== NULL) {
        try {
            // Consultar productos por categoría
            $sql = "SELECT * FROM productos WHERE categoria_fk = ?";
            $stmt = $conn->prepare($sql);

            // Verificar si la preparación de la consulta fue exitosa
            if ($stmt) {
                // Bind parameters
                $stmt->bind_param('i', $categoriaId);

                // Ejecutar la consulta
                $stmt->execute();

                // Obtener el resultado
                $result = $stmt->get_result();

                // Verificar si se obtuvieron resultados
                if ($result) {
                    // Obtener los productos
                    $productos = $result->fetch_all(MYSQLI_ASSOC);
                }

                // Cerrar el statement
                $stmt->close();
            } else {
                // Handle the error, e.g., log the error
                error_log("Error preparing statement: " . $conn->error);
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur
            error_log("Error: " . $e->getMessage());
        }
    }

    return $productos;
}






function mostrarContenidoSegunCategoria($productosPorCategoria) {
    $productosHTML = traerProductosHTML("detalle");
    
    // Verificar si la variable $productosPorCategoria está definida y no está vacía
    if (isset($productosPorCategoria) && !empty($productosPorCategoria)) {
        // Mostrar productos por categoría
        $html = "";
        foreach ($productosPorCategoria as $producto) {
            // Aquí debes adaptar el código según la estructura real de tus productos
            $precio = $producto['precio_lista']; 
            $nombre = $producto['producto'];
            $descripcion = $producto['descripcion'];
            $imagen = $producto['imagen'];
            $id = $producto['id'];
            
            // HTML para vista detallada
            $html .= '<div class="col-md-4">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">'; // Agregado
            $html .= '<div class="card mb-4 product-wap rounded-0">';
            $html .= '<div class="card rounded-0">';
            $html .= '<img class="card-img rounded-0 img-fluid" src="/uploads/' . $imagen . '">';
            $html .= '<div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="h3 text-center">' . $nombre . '</a>'; // Modificado
            $html .= '<ul class="w-100 list-unstyled d-flex justify-content-between mb-0">';
            $html .= '</ul>';
            $html .= '<p class="text-right mb-0">$' . $precio . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</a>'; // Agregado
            $html .= '</div>';
        }
        echo $html;
    } else {
        // Mostrar nombres de categorías o HTML de productos si no hay productos por categoría
        echo $productosHTML;
    }
}






 
?>