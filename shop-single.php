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
          $nombre = $usuario['nombre'];
          $rol = $usuario['rol'];
          $usuarioID = $usuario['id_cliente'];

          echo "User Email: $sesion // ";
          echo "User Name: $nombre // ";
          echo "User Id: $usuarioID // ";
          echo "User Role: $rol";
          // Resto del código que usa $nombre y $rol
      } else {
          echo "Error al obtener datos del usuario.";
      }
  } else {
      echo "No hay sesión iniciada.";
  }

// Verificar si se proporciona un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productoID = $_GET['id'];
    // Ahora, $productoID contiene el ID del producto que se debe mostrar
} else {
    // Manejar el caso en que no se proporciona un ID válido
    echo "ID de producto no válido";
    exit; // O redirige a una página de error
}



// Obtener detalles del producto por ID
// Llamada a la función para obtener detalles del producto

$carrito = $_GET['id'];
$producto = obtenerDetalleProducto($productoID);
// Verificar si se encontró el producto
if ($producto === NULL) {
    // Manejar el caso en que no se encontró el producto
    echo "Producto no encontrado";
    exit; // O redirige a una página de error
}


$precio = $producto['precio_lista'];
$usuarioID = $usuario['id_cliente'];

$carritoFinal = agregarProductoAlCarrito($usuarioID, $productoID, $precio);
// Resto del código HTML para mostrar la información del producto
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
    <!-- Slick -->
    <link rel="stylesheet" type="text/css" href="assets/css/slick.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/slick-theme.css">
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

    <!-- Open Content -->
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img class="card-img img-fluid" src="/uploads/<?php echo $producto['imagen']; ?>" id="product-detail">
                    </div>
                    <div class="row">
                    </div>
                </div>
                <!-- col end -->
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2"><?php echo $producto['producto']; ?></h1>
                            <p class="h3 py-2">$<?php echo $producto['precio_lista']; ?></p>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Categoria:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p><strong><?php echo $producto['nombre']; ?></strong></p>
                                </li>
                            </ul>
                            <h6>Descripción:</h6>
                            <p><?php echo $producto['descripcion']; ?></p>

                            
                            <form action="principal.php" method="GET">
                                <input type="hidden" name="carrito" value="<?php echo $carrito; ?>">
                                <div class="row pb-3">
                                    <div class="col d-grid">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            Agregar al Carrito
                                        </button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close Content -->


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