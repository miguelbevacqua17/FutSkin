<?php
include "sesion.php";
include "bd.php";

$conn = conectarBDUsuario();

// Verificar si la sesión está iniciada y la clave 'email' está presente
if (isset($_SESSION['email'])) {
    $sesion = $_SESSION['email'];
    // Verificar si la consulta de datos del usuario es exitosa
    $usuario = consultaDatosUsuario($conn, $sesion); 
    if ($usuario !== null) {
        $usuarioId = $usuario['id_cliente'];
        $nombre = $usuario['nombre'];
        $rol = $usuario['rol'];
        $direccion = $usuario['direccion'];
        $altura = $usuario['altura'];
        echo "User Email: $sesion // ";
        echo "User Name: $nombre // ";
        echo "User id: $usuarioId // ";
        echo "User Role: $rol // ";
        echo "Dirección de envío: $direccion // ";
        echo "Altura: $altura";
    } else {
        echo "Error al obtener datos del usuario.";
    }
} else {
    echo "No hay sesión iniciada.";
}

$sesionUsuario = controlarSesion();

// Variables para el contenido de los input
$email = "no data";
$apellido = "no data";
$nombre = "no data";
$direccion = "no data";
$altura = "no data";
$piso = "no data";
$barrio = "no data";

if ($sesionUsuario != NULL) {
    // Abrir conexión a base de datos, en este caso 'bd_usuario'
    $conn = conectarBDUsuario();
    // Ejecutar consulta
    $resultado = consultaDatosUsuario($conn, $sesionUsuario);
    // Cerrar conexión '$conn' de base de datos
    cerrarBDConexion($conn);

    if ($resultado != NULL) {
        // Obtener datos del usuario
        $email = $resultado['email'];
        $apellido = $resultado['apellido'];
        $nombre = $resultado['nombre'];
        $direccion = $resultado['direccion'];   
        $altura = $resultado['altura'];   
        $piso = $resultado['piso'];   
        $barrio = $resultado['barrio'];      
        $usuarioId = $resultado['id_cliente'];           
    }
}



 $id = $usuario['id_cliente'];
 
 $nombresCategorias = traerColumnaTabla('nombre', 'categorias');

 $productosCarrito = obtenerProductosCarrito($id);
 $precioTotal = calcularPrecioTotal($productosCarrito);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>FutSkin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="/assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/templatemo.css">
    <link rel="stylesheet" href="/assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">

    <!-- Load map styles -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>

        <!-- NAV -->
        <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
            <div class="container text-light">
                <div class="w-100 d-flex justify-content-between">
                    <div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- FIN NAV -->
    
    
        <!-- HEADER -->
        <nav class="navbar navbar-expand-lg navbar-light shadow">
            <div class="container d-flex justify-content-between align-items-center">
    
                <a class="navbar-brand text-success logo h1 align-self-center" href="/principal.php">
                    FutSkin
                </a>
    
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                    <div class="flex-fill">
                        <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="principal.php">Inicio</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="shop.php">Tienda</a>
                            </li>                            

                            <?php if (isset($_SESSION['email']) && $rol != '1') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/sign-edit.php">Editar datos usuario</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/carrito.php">Carrito</a>
                                </li>
                            <?php } elseif (isset($_SESSION['email']) && $rol = '1') { ?>     
                            <li class="nav-item">
                                <a class="nav-link" href="/creacion-producto.php">Nuevo producto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin.php">Vista Administrador</a>                        
                            </li>
                            
                                <?php } else { ?>
                                    <li class="nav-item"></li>
                                    <li class="nav-item"></li>
                                <?php } ?>

                        </ul>


                    </div>
                    
                    <div class="navbar align-self-center d-flex">
                        <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                            
                            <?php if (isset($_SESSION['email'])) { ?>

                                <li class="nav-item">
                                    <a class="nav-link" href="/sign-edit.php"><?php echo "Hola, $nombre "?></a>
                                </li>

                                <li class="nav-item">
                                    <form action="logout.php" method="post">
                                        <button type="submit" class="nav-link">Logout</button>
                                    </form>
                                </li>

                            <?php } else { ?>
     
                                <li class="nav-item">
                                    <a class="nav-link" href="/signin.html">Iniciar sesión</a>
                                </li>

                            <?php } ?>

                        </ul>
                    </div>
    
                </div>
    
            </div>
        </nav>
        <!-- FIN HEADER -->


    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                    <button type="submit" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Start Content Page -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Finalizar la compra</h1>
            <p>
                Ingresá tus datos de envio para confirmar la compra
            </p>
        </div>
    </div>


    <!-- Start Contact -->
    <div class="container py-5">
        <div class="row py-5">
        <form class="col-md-9 m-auto" method="post" action="venta-pedido.php" role="form">
            <div class="row">

            <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered m-0">
                <thead>
                  <tr>
                    <!-- Set columns width -->
                    <th class="text-center py-3 px-4" style="min-width: 400px;">Producto</th>
                    <th class="text-right py-3 px-4" style="width: 200px;">ID pedido</th>
                    <th class="text-right py-3 px-4" style="width: 200px;">Precio</th>
                    <th class="text-center py-3 px-4" style="width: 200px;">Cantidad</th>
                    <th class="text-right py-3 px-4" style="width: 200px;">Total</th>
                  </tr>
                </thead>
                <tbody>
        
                <?php
                        // Verificar si hay productos en el carrito antes de iterar
                        if (!empty($productosCarrito)) {
                        // Iterar sobre los productos del carrito y generar filas de la tabla
                        foreach ($productosCarrito as $producto) {
                            if (array_key_exists('cantidad_prod', $producto)) {
                                // Acceder a la cantidad si existe
                                $cantidad = $producto['cantidad_prod'];
                                // Resto del código para mostrar la cantidad...
                            } else {
                                // Manejar el caso en el que 'cantidad_prod' no está definido
                                echo "La clave 'cantidad_prod' no está definida para este producto.";
                            }
                            ?>


                    <tr>
                        <td class="p-4">
                            <div class="media align-items-center">
                                <img src="/uploads/<?php echo $producto['imagen']; ?>" class="d-block ui-w-40 ui-bordered mr-4" alt="" height="300" width="300">
                                <div class="media-body">
                                    <a class="d-block text-dark"><?php echo $producto['producto']; ?></a>
                                </div>
                            </div>
                        </td>
                        <td class="text-right font-weight-semibold align-middle p-4"><?php echo $producto['pedido_id']; ?></td>
                        <td class="text-right font-weight-semibold align-middle p-4">$<?php echo $producto['precio_lista']; ?></td>
                        <td class="text-right font-weight-semibold align-middle p-4"><?php echo $producto['cantidad_prod']; ?></td>
                        <td class="text-right font-weight-semibold align-middle p-4">$<?php echo $producto['precio_lista'] * $producto['cantidad_prod']; ?></td>
                        <td class="text-center align-middle px-0">
                        </td>
                    </tr>


                
                <?php
                            }
                        } else {
                            // Mensaje si no hay productos en el carrito
                            echo '<tr><td colspan="5" class="text-center">No hay productos en el carrito.</td></tr>';
                        }
                        ?>

                </tbody>
              </table>
            </div>
            <div class="form-group col-md-12 text-center mb-4 mt-4">
    <label class="h3" for="text">Total a pagar: $<?php echo $precioTotal?></label>
</div>


                </div>
                

                <div class="row">
                    <div class="col text-end mt-2">
                        <input type="hidden" name="usuarioId" value="<?php echo $usuarioId ?>">
                        <button type="submit" name="ventaPedidoBtn" class="btn btn-lg btn-primary mt-2" style="background-color: #198754; color: white;">Finalizar compra</button>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
    <!-- End Contact -->


<!-- FOOTER -->
<footer class="bg-dark" id="tempaltemo_footer">
            <div class="container">
                <div class="row">
    
                    <div class="col-md-4 pt-5">
                        <h2 class="h2 text-success border-bottom pb-3 border-light logo">FutSkin</h2>
                        <ul class="list-unstyled text-light footer-link-list">
                            <li>
                                <i class="fas fa-map-marker-alt fa-fw"></i>
                                Dirección
                            </li>
                            <li>
                                <i class="fa fa-phone fa-fw"></i>
                                <a class="text-decoration-none" href="tel:">4200 - 7777</a>
                            </li>
                            <li>
                                <i class="fa fa-envelope fa-fw"></i>
                                <a class="text-decoration-none" href="mailto:info@company.com">contacto@futskin.com</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 pt-5">
                        <h2 class="h2 text-light border-bottom pb-3 border-light">Categorías</h2>
                        <ul class="list-unstyled text-light footer-link-list">

                        <?php foreach ($nombresCategorias as $categoria) { ?>
                          <li><a class="text-decoration-none" href="#"><?php echo $categoria; ?></a></li>
                        <?php } ?>
                        </ul>
                    </div>
    
                    <div class="col-md-4 pt-5">
                        <h2 class="h2 text-light border-bottom pb-3 border-light">Accesos rápidos</h2>
                        <ul class="list-unstyled text-light footer-link-list">
                            <li><a class="text-decoration-none" href="principal.php">Inicio</a></li>
                            <li><a class="text-decoration-none" href="shop.php">Tienda</a></li>
                        </ul>
                    </div>
                </div>
    
                <div class="row text-light mb-4">
                    <div class="col-12 mb-3">
                        <div class="w-100 my-3 border-top border-light"></div>
                    </div>
                </div>
            </div>
    
            <div class="w-100 bg-black py-3">
                <div class="container">
                    <div class="row pt-2">
                        <div class="col-12">
                            <p class="text-left text-light">
                                Copyright &copy; 2023 FutSkin
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    <!-- FIN FOOTER -->

    <!-- Start Script -->
    <script src="/assets/js/jquery-1.11.0.min.js"></script>
    <script src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/templatemo.js"></script>
    <script src="/assets/js/custom.js"></script>
    <!-- End Script -->
</body>

</html>