<?php

// Función para hacer la conexión con la base de datos
function conectarBDUsuario(){   
    // Datos para conectar a la base de datos.
    $nombreServidor = "mysql-futskin.alwaysdata.net";
    $nombreUsuario = "futskin";
    $passwordBaseDeDatos = "futskin-bd2023";
    $nombreBaseDeDatos = "futskin_bd";

    // Creamos la conexión con la base de datos.
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $conn = new mysqli($nombreServidor, $nombreUsuario, $passwordBaseDeDatos, $nombreBaseDeDatos);      
    } catch (Exception $e) {
        echo 'ERROR:'.$e->getMessage();
        $conn=NULL;
    }   
    return $conn;
}


// Función para cerrar la conexión con la base de datos
function cerrarBDConexion($conn){
    if ($conn!=NULL){
        $conn->close();
    }    
}


// Función para obtener el usuario donde coincida el email y la contraseña
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


// Función para obtener el usuario donde coincida el email
function verficarEmail($conn, $email) {
    $resultado = NULL;
    if ($conn != NULL) {
        // Confección de la consulta preparada para evitar inyecciones SQL
        $formato = "SELECT * FROM clientes WHERE email= ?";
        // Preparar la consulta
        $stmt = $conn->prepare($formato);
        // Verificamos si la consulta se preparó correctamente
        if ($stmt) {
            // Vinculamos el parámetro
            $stmt->bind_param("s", $email);
            // Ejecutamos la consulta
            if ($stmt->execute()) {
                // Obtener el resultado
                $resultado = $stmt->get_result();
                // Cerramos la consulta preparada
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


// Función para agregar el registro de un usuario (signup)
function agregarUsuario($conn,$apellido,$nombre,$email,$password) {
    $filasAfectadas = 0;
    if ($conn != NULL) {
        if ($stmt = $conn->prepare("INSERT INTO clientes (apellido, nombre, email, contrasena) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param('ssss',$apellido,$nombre,$email,$password);
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    }
    return $filasAfectadas;
}


// Función para obtener los datos de un usuario y datos de envio
function consultaDatosUsuario($conn, $email) {
    $resultado = NULL;
    if ($conn != NULL) {
        // Confeccionamos el string de la consulta segura para evitar inyecciones SQL.
        $sql = "SELECT clientes.*, envios.* FROM clientes 
                LEFT JOIN envios ON clientes.envio_fk = envios.id
                WHERE clientes.email = ? ";

        // Preparamos la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("s", $email);
            // Ejecutamos la consulta SQL
            $stmt->execute();
            // Obtenemos el resultado
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $resultado = $result->fetch_assoc();
            } else {
                error_log("No rows found for email: $email");
            }
            // Cerramos el conjunto de resultados y el statement
            $result->close();
            $stmt->close();
        } else {
            error_log("Error preparing statement: " . $conn->error);
        }
    }
    return $resultado;
}


// Función para actualizar datos de usuario y de envio (sign-update)
function actualizarUsuario($conn, $email, $apellido, $nombre, $direccion, $altura, $barrio, $piso, $actualizarClientes = true, $actualizarEnvios = true) {
    $filasAfectadas = 0;

    if ($conn != NULL) {
        // Iniciamos una transacción para garantizar la consistencia de ambas actualizaciones
        $conn->begin_transaction();
        try {
            // Actualizamos datos en la tabla clientes si se solicita
            if ($actualizarClientes) {
                $stmtClientes = $conn->prepare("UPDATE clientes SET apellido = ?, nombre = ? WHERE email = ?");
                $stmtClientes->bind_param('sss', $apellido, $nombre, $email);
                $stmtClientes->execute();
                $filasAfectadasClientes = $stmtClientes->affected_rows;
                $stmtClientes->close();
            } else {
                $filasAfectadasClientes = 0; // No se intentó actualizar clientes
            }

            // Verificamos si hay un registro existente en la tabla envios
            $stmtCheckEnvio = $conn->prepare("SELECT id FROM envios WHERE id = (SELECT envio_fk FROM clientes WHERE email = ?)");
            $stmtCheckEnvio->bind_param('s', $email);
            $stmtCheckEnvio->execute();
            $stmtCheckEnvio->store_result();
            $numRows = $stmtCheckEnvio->num_rows;

            if ($numRows > 0) {
                // Existe un registro en envios, realizamos la actualización
                $stmtEnvios = $conn->prepare("UPDATE envios SET direccion = ?, altura = ?, barrio = ?, piso = ? WHERE id = (SELECT envio_fk FROM clientes WHERE email = ?)");
                $stmtEnvios->bind_param('sssss', $direccion, $altura, $barrio, $piso, $email);
                $stmtEnvios->execute();
                $filasAfectadasEnvios = $stmtEnvios->affected_rows;
                $stmtEnvios->close();
            } else {
                // No existe un registro en envios, creamos uno nuevo
                $stmtNuevoEnvio = $conn->prepare("INSERT INTO envios (direccion, altura, barrio, piso) VALUES (?, ?, ?, ?)");
                $stmtNuevoEnvio->bind_param('ssss', $direccion, $altura, $barrio, $piso);
                $stmtNuevoEnvio->execute();
                $idNuevoEnvio = $stmtNuevoEnvio->insert_id;
                $stmtNuevoEnvio->close();

                // Actualizamos el campo envio_fk en la tabla clientes
                $stmtUpdateClientes = $conn->prepare("UPDATE clientes SET envio_fk = ? WHERE email = ?");
                $stmtUpdateClientes->bind_param('ss', $idNuevoEnvio, $email);
                $stmtUpdateClientes->execute();
                $filasAfectadasEnvios = $stmtUpdateClientes->affected_rows;
                $stmtUpdateClientes->close();
            }

            $stmtCheckEnvio->close();

            // Confirmamos la transacción si al menos una de las actualizaciones fue exitosa
            if ($filasAfectadasClientes > 0 || $filasAfectadasEnvios > 0) {
                $conn->commit();
                $filasAfectadas = $filasAfectadasClientes + $filasAfectadasEnvios;
            } else {
                // Revertimos la transacción si nada se actualizó
                $conn->rollback();
            }

        } catch (Exception $e) {
            // Manejamos cualquier excepción que pueda ocurrir durante la transacción
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    }

    return $filasAfectadas;
}


// Función para generar HTML de categorias
function traerCategoriasHTML() {
    // Conectamos a la base de datos
    $conn = conectarBDUsuario();
    // Verificamos la conexión
    if ($conn === NULL) {
        return "";
    }
    // Consultamos categorías
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
    // Cerramos la conexión a la base de datos
    cerrarBDConexion($conn);
    // Generamos el HTML
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


// Función para traer las categorias
function categorias() {
    // Conectamos a la base de datos
    $conn = conectarBDUsuario();
    // Verificar la conexión
    if ($conn === NULL) {
        return "";        
    }
    // Consultamos categorías
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
    // Cerramos conexión a la base de datos
    cerrarBDConexion($conn);
    return $data;
}


// Función para generar HTML de productos
function traerProductosHTML($tipo = "default") {
    // Conectamos a la base de datos
    $conn = conectarBDUsuario();
    // Verificamos la conexión
    if ($conn === NULL) {
        return "";
    }
    // Consultamos productos
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
    // Cerramos la conexión a la base de datos
    cerrarBDConexion($conn);
    // Generamos el HTML según el tipo
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
            $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">';
            $html .= '<div class="card mb-4 product-wap rounded-0">';
            $html .= '<div class="card rounded-0">';
            $html .= '<img class="card-img rounded-0 img-fluid" src="/uploads/' . $imagen . '">';
            $html .= '<div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="h3 text-center">' . $nombre . '</a>'; 
            $html .= '<ul class="w-100 list-unstyled d-flex justify-content-between mb-0">';
            $html .= '</ul>';
            $html .= '<p class="text-right mb-0">$' . $precio . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</a>'; 
            $html .= '</div>';
        } else {
            // HTML por defecto
            $html .= '<div class="col-12 col-md-4 mb-4">';
            $html .= '<div class="card h-100 d-flex flex-column">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">'; 
            $html .= '<img src="/uploads/' . $imagen . '" class="card-img-top" alt="...">';
            $html .= '</a>';
            $html .= '<div class="card-body d-flex flex-column">';
            $html .= '<ul class="list-unstyled d-flex justify-content-between mb-2">';
            $html .= '<li class="text-muted text-right">$' . $precio . '</li>';
            $html .= '</ul>';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="h2 text-decoration-none text-dark">' . $nombre . '</a>';
            $html .= '<p class="card-text description-limit flex-grow-1">' . $descripcion_corta ."..." . '</p>';
            $html .= '<p class="text-muted"></p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
    }
    return $html;
}


// Función para traer los datos de una categoria
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

// Función para traer todos los productos de una categoria (1)
function obtenerProductosPorCategoria($conn, $categoriaId) {
    $productos = [];
    // Verificamos la conexión a la base de datos
    if ($conn !== NULL) {
        try {
            // Consultamos productos por categoría
            $sql = "SELECT * FROM productos WHERE categoria_fk = ?";
            $stmt = $conn->prepare($sql);
            // Verificamos si la preparación de la consulta fue exitosa
            if ($stmt) {
                // Bind parameters
                $stmt->bind_param('i', $categoriaId);
                // Ejecutamos la consulta
                $stmt->execute();
                // Obtenemos el resultado
                $result = $stmt->get_result();
                // Verificamos si se obtuvieron resultados
                if ($result) {
                    // Obtenemos los productos
                    $productos = $result->fetch_all(MYSQLI_ASSOC);
                }
                $stmt->close();
            } else {
                error_log("Error preparing statement: " . $conn->error);
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
        }
    }
    return $productos;
}


// Función para traer todos los productos de una categoria (2)
function mostrarContenidoSegunCategoria($productosPorCategoria) {
    $productosHTML = traerProductosHTML("detalle");
    // Verificamos si la variable $productosPorCategoria está definida y no está vacía
    if (isset($productosPorCategoria) && !empty($productosPorCategoria)) {
        // Mostramos los productos por categoría
        $html = "";
        foreach ($productosPorCategoria as $producto) {
            $precio = $producto['precio_lista']; 
            $nombre = $producto['producto'];
            $descripcion = $producto['descripcion'];
            $imagen = $producto['imagen'];
            $id = $producto['id'];
            
            // HTML para vista detallada
            $html .= '<div class="col-md-4">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">'; 
            $html .= '<div class="card mb-4 product-wap rounded-0">';
            $html .= '<div class="card rounded-0">';
            $html .= '<img class="card-img rounded-0 img-fluid" src="/uploads/' . $imagen . '">';
            $html .= '<div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<a href="shop-single.php?id=' . $id . '" class="h3 text-center">' . $nombre . '</a>';
            $html .= '<ul class="w-100 list-unstyled d-flex justify-content-between mb-0">';
            $html .= '</ul>';
            $html .= '<p class="text-right mb-0">$' . $precio . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</a>';
            $html .= '</div>';
        }
        echo $html;
    } else {
        echo $productosHTML;
    }
}


// Función para traer los datos de un producto
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


// Función para obtener las categorias 
function obtenerCategorias() {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return array();
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


// Función para agregar un producto al carrito del usuario
function agregarProductoAlCarrito($usuarioID, $productoID, $precio) {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return false;
    }
    $sql = "INSERT INTO pedidos (cliente_fk, producto_fk, precio_venta, estado, cantidad_prod) VALUES (?, ?, ?, 'pendiente', 1)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        cerrarBDConexion($conn);
        return false;
    }
    $stmt->bind_param("iis", $usuarioID, $productoID, $precio);
    $resultado = $stmt->execute();
    if (!$resultado) {
        cerrarBDConexion($conn);
        return false;
    }
    cerrarBDConexion($conn);
    return $resultado;
}


// Función para obtener los productos en el carrito de un usuario
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


// Función para calcular el precio total del carrito de un usuario
function calcularPrecioTotal($productosCarrito) {
    $precioTotal = 0;
    foreach ($productosCarrito as $producto) {
        if (array_key_exists('precio_lista', $producto) && array_key_exists('cantidad_prod', $producto)) {
            // Calculamos el precio total del producto (precio unitario * cantidad)
            $precioUnitario = $producto['precio_lista'];
            $cantidad = $producto['cantidad_prod'];
            $precioProducto = $precioUnitario * $cantidad;
            // Sumamos el precio total del producto al precio total general
            $precioTotal += $precioProducto;
        } else {
            echo "Las claves 'precio_lista' o 'cantidad_prod' no están definidas para este producto.";
        }
    }
    return $precioTotal;
}


// Función para eliminar un producto del carrito del usuario
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


// Función para traer las ventas concretadas
function traerVentas() {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return "";
    }
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
    cerrarBDConexion($conn);
    return $data;
}


// Función para traer las compras de un usuario
function buscarPedidosUsuario($usuarioEmail) {
    $conn = conectarBDUsuario();
    if ($conn === NULL) {
        return [];
    }
    try {
        // Obtenemos el ID del usuario utilizando su email
        $stmtUsuario = $conn->prepare("SELECT id_cliente FROM clientes WHERE email = ?");
        $stmtUsuario->bind_param('s', $usuarioEmail);
        $stmtUsuario->execute();
        $resultUsuario = $stmtUsuario->get_result();
        if (!$resultUsuario) {
            throw new Exception("Error al obtener el ID del usuario: " . $stmtUsuario->error);
        }
        $usuario = $resultUsuario->fetch_assoc();
        // Verificamos si se encontró el usuario
        if (!$usuario) {
            throw new Exception("Usuario no encontrado con el email: $usuarioEmail");
        }
        // Obtenemos el ID del usuario
        $usuarioId = $usuario['id_cliente'];
        // Consultamos los pedidos del usuario
        $sql = "SELECT pedidos.*, productos.* FROM pedidos
                JOIN productos ON pedidos.producto_fk = productos.id
                WHERE pedidos.cliente_fk = ?";
        $stmtPedidos = $conn->prepare($sql);
        // Verificamos si la preparación de la consulta fue exitosa
        if (!$stmtPedidos) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmtPedidos->bind_param('i', $usuarioId);
        $stmtPedidos->execute();
        $resultPedidos = $stmtPedidos->get_result();
        // Verificamos si la ejecución de la consulta fue exitosa
        if (!$resultPedidos) {
            throw new Exception("Error en la ejecución de la consulta: " . $stmtPedidos->error);
        }
        // Obtenemos los datos de los pedidos
        $dataPedidos = $resultPedidos->fetch_all(MYSQLI_ASSOC);
        $stmtPedidos->close();
        // Devolvemos los datos obtenidos
        return $dataPedidos;
    } catch (Exception $e) {
        // Manejamos cualquier excepción que pueda ocurrir durante la ejecución de la función
        cerrarBDConexion($conn);
        error_log("Error en la función buscarPedidosUsuario: " . $e->getMessage());
        return [];
    }
}





// Función para crear/agregar un nuevo producto
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
    if (!$resultado) {
        cerrarBDConexion($conn);
        return false;
    }
    cerrarBDConexion($conn);
    return $resultado;
}


// Función para traer datos de la columna de una tabla
function traerColumnaTabla($columna, $tabla) {
    // Conectamos a la base de datos
    $conn = conectarBDUsuario();
    // Verificamos la conexión
    if ($conn === NULL) {
        return array();
    }
    // Consultamos la columna específica de la tabla
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
    // Cerramos la conexión a la base de datos
    cerrarBDConexion($conn);
    // Obtenemos solo los valores de la columna
    $valoresColumna = array_column($data, $columna);
    return $valoresColumna;
}

?>