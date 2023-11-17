<?php
  function conectarBDUsuario(){   
    // Datos para conectar a la base de datos.
    $nombreServidor = "mysql-futskin.alwaysdata.net";
    $nombreUsuario = "futskin";
    $passwordBaseDeDatos = "futskin-bd2023";
    $nombreBaseDeDatos = "futskin_bd";

    // Crear conexión con la base de datos.
    // https://www.w3schools.com/php/php_ref_mysqli.asp
    // https://www.php.net/manual/es/class.mysqli-sql-exception.php
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



#########################

function consultaDatosUsuario($conn, $email) {
  $resultado = NULL;

  if ($conn != NULL) {
      // Confección del string de la Consulta segura para evitar inyecciones SQL.
      $sql = "SELECT * FROM clientes WHERE email = ? ";
      
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


  function actualizarUsuario($conn,$email,$password,$apellido,$nombre ){
    $filasAfectadas = 0;
    if ($conn!=NULL){     
      /* crear una sentencia preparada */
      if ($stmt = $conn->prepare("UPDATE clientes SET apellido = ? , nombre = ? WHERE email= ? AND password=?")) {
        /* ligar parámetros para marcadores */
        $stmt->bind_param('ssss',$apellido,$nombre,$email,$password);
        /* ejecutar la consulta */
        $stmt->execute();
        /* obtener la cantidad de filas afectadas en la inserción */
        $filasAfectadas=$stmt->affected_rows;
        /* cerrar sentencia */
        $stmt->close();
      }
    }
    return $filasAfectadas;

  }


    ############################


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
    
            $html .= '<div class="col-12 col-md-4 p-5 mt-3">';
            $html .= '<a href="#"><img src="/assets/img/' . $imagen . '" class="rounded-circle img-fluid border"></a>';
            $html .= '<h5 class="text-center mt-3 mb-3">' . $categoria . '</h5>';
            $html .= '<p class="text-center"><a class="btn btn-success">ver</a></p>';
            $html .= '</div>';
        }
    
        return $html;
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
      $nombre = $row['nombre'];
      $descripcion = $row['descripcion'];
      $imagen = $row['imagen'];
      $id = $row['id'];

      if ($tipo === "detalle") {
          // HTML para vista detallada
          $html .= '<div class="col-md-4">';
          $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">'; // Agregado
          $html .= '<div class="card mb-4 product-wap rounded-0">';
          $html .= '<div class="card rounded-0">';
          $html .= '<img class="card-img rounded-0 img-fluid" src="/assets/img/' . $imagen . '">';
          $html .= '<div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">';
          $html .= '</div>';
          $html .= '<div class="card-body">';
          $html .= '<a href="shop-single.php?id=' . $id . '" class="h3 text-decoration-none">' . $nombre . '</a>'; // Modificado
          $html .= '<ul class="w-100 list-unstyled d-flex justify-content-between mb-0">';
          $html .= '<li>S/M/L/X/XL</li>';
          $html .= '<li class="pt-2">';
          $html .= '<span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>';
          $html .= '<span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>';
          $html .= '<span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>';
          $html .= '<span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>';
          $html .= '<span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>';
          $html .= '</li>';
          $html .= '</ul>';
          $html .= '<p class="text-center mb-0">$' . $precio . '</p>';
          $html .= '</div>';
          $html .= '</div>';
          $html .= '</div>';
          $html .= '</a>'; // Agregado
          $html .= '</div>';
          
      } else {
          // HTML por defecto
          $html .= '<div class="col-12 col-md-4 mb-4">';
          $html .= '<div class="card h-100">';
          $html .= '<a href="shop-single.php?id=' . $id . '" class="text-decoration-none">'; // Agregado
          $html .= '<img src="/assets/img/' . $imagen . '" class="card-img-top" alt="...">';
          $html .= '</a>';
          $html .= '<div class="card-body">';
          $html .= '<ul class="list-unstyled d-flex justify-content-between">';
          $html .= '<li class="text-muted text-right">$' . $precio . '</li>';
          $html .= '</ul>';
          $html .= '<a href="shop-single.php?id=' . $id . '" class="h2 text-decoration-none text-dark">' . $nombre . '</a>'; // Modificado
          $html .= '<p class="card-text">' . $descripcion . '</p>';
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
    


#################

function obtenerDetalleProducto($productoID) {
    $conn = conectarBDUsuario();

    if ($conn === NULL) {
        return NULL;
    }

    $sql = "SELECT * FROM productos WHERE id = ?";
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



#####################


function obtenerProductosCarrito($usuarioID) {
    $conn = conectarBDUsuario();

    if ($conn === NULL) {
        return NULL;
    }

    $sql = "SELECT productos.id, productos.nombre, productos.imagen, productos.precio_lista, productos.descuento, pedidos.cantidad_prod
    FROM productos
    INNER JOIN pedidos ON productos.id = pedidos.producto_fk
    WHERE pedidos.cliente_fk = ? AND pedidos.estado = 'pendiente'";


    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return NULL;
    }

    $stmt->bind_param("i", $usuarioID);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
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




##########


// bd.php

function insertarNuevoProducto($categoriaID, $nombre, $imagen, $descripcion, $precioLista, $descuento, $stock) {
    $conn = conectarBDUsuario();

    if ($conn === NULL) {
        return false;
    }

    $sql = "INSERT INTO productos (categoria_fk, nombre, imagen, descripcion, precio_lista, descuento, stock, deleteable) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        cerrarBDConexion($conn);
        return false;
    }

    $stmt->bind_param("issdisi", $categoriaID, $nombre, $imagen, $descripcion, $precioLista, $descuento, $stock);
    $resultado = $stmt->execute();

    cerrarBDConexion($conn);

    return $resultado;
}



##########
function agregarProductoAlCarrito($usuarioID, $productoID) {
    $conn = conectarBDUsuario(); // Ajusta según tu función de conexión

    if ($conn === NULL) {
        return false;
    }

    // Puedes realizar validaciones adicionales aquí antes de ejecutar la consulta

    $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        cerrarBDConexion($conn);
        return false;
    }

    $stmt->bind_param("ii", $usuarioID, $productoID);
    $resultado = $stmt->execute();

    cerrarBDConexion($conn);

    return $resultado;
}
?>

