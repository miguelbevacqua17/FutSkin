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
          $id = $usuario['id_cliente'];
  
          echo "User Email: $sesion // ";
          echo "User Name: $nombre // ";
          echo "User ID: $id // ";
          echo "User Role: $rol";


          // Resto del código que usa $nombre y $rol
      } else {
          echo "Error al obtener datos del usuario.";
      }
  } else {
      echo "No hay sesión iniciada.";
  }

  $productosCarrito = obtenerProductosCarrito($id);
  $precioTotal = calcularPrecioTotal($productosCarrito);
  $nombresCategorias = traerColumnaTabla('nombre', 'categorias');

?>  

<!DOCTYPE html>
<html lang="en">

<head>
    <title>FutSkin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="/assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/templatemo.css">
    <link rel="stylesheet" href="/assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">

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


<div class="container px-3 my-5 clearfix">
    <!-- Shopping cart table -->
    <div class="card">
        <div class="card-header">
            <h2>Carrito</h2>
        </div>
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
                    <th class="text-center align-middle py-3 px-0" style="width: 40px;"><a href="#" class="shop-tooltip float-none text-light" title="" data-original-title="Clear cart"><i class="ino ion-md-trash"></i></a></th>
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
                        <form action="eliminar_pedido.php" method="POST">
                            <input type="hidden" name="pedidoID" value="<?php echo $producto['pedido_id']; ?>">
                            <button type="submit" class="eliminarPedidoBtn" name="eliminarPedidoBtn">×</button>
                        </form>
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
            <!-- / Shopping cart table -->
        
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-4">
              <div class="d-flex">
                </div>
                <div class="text-right mt-4">
                  <label class="text-muted font-weight-normal m-0">PRECIO TOTAL</label>
                  <div class="text-large"><strong>$<?php echo $precioTotal?></strong></div>
                </div>
              </div>
            </div>
        
            <div class="float-right">
              <a href="/finalizar-compra.php"><button type="button" class="btn btn-lg btn-primary mt-2" style="background-color: #198754; color: white;">Finalizar compra</button></a>
            </div>
        
          </div>
      </div>
  </div>


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
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->
</body>

</html>
