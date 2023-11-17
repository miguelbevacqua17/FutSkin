<?php
  include "sesion.php";
  include "bd.php";

  $conn = conectarBDUsuario();

  $sesion = $_SESSION['email'];

  $usuario = consultaDatosUsuario($conn, $sesion); 
  $nombre = $usuario['nombre'];
  $id = $usuario['id'];

  echo "User Email: $sesion // ";
  echo "User ID: $id // ";

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
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/templatemo.css">
    <link rel="stylesheet" href="/assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">

</head>

<body>
    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:info@company.com">contacto@futskin.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="tel:">4200 - 7777</a>
                </div>
                <div>
                    <a class="text-light" href="https://facebook.com/" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://twitter.com/" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin fa-sm fa-fw"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->


         <!-- Header -->
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
                            
                            <li class="nav-item">
                                <a class="nav-link" href="/views/creacion-producto.html">Nuevo producto</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link" href="/views/admin.html"><?php echo "Hola, $sesion "?></a>
                            </li>
                            
                            <li class="nav-item">
                            <form action="logout.php" method="post">
                              <button type="submit">Logout</button>
                            </form>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="navbar align-self-center d-flex">
                        <a class="nav-icon position-relative text-decoration-none" href="/views/carrito.html">
                            <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                            <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">cart</span>
                        </a>
                        <a class="nav-icon position-relative text-decoration-none" href="/signin.php">
                            <i class="fa fa-fw fa-user text-dark mr-3"></i>
                            <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">login</span>
                        </a>
    
                        <a class="nav-icon position-relative text-decoration-none" href="/views/user.html">
                            <i class="fa fa-fw fa-user text-dark mr-3"></i>
                            <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">user</span>
                        </a>
                    </div>
    
                </div>
    
            </div>
        </nav>
        <!-- Close Header -->


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
                    <th class="text-right py-3 px-4" style="width: 100px;">Precio</th>
                    <th class="text-center py-3 px-4" style="width: 120px;">Cantidad</th>
                    <th class="text-right py-3 px-4" style="width: 100px;">Total</th>
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
                        <img src="/assets/img/<?php echo $producto['imagen']; ?>" class="d-block ui-w-40 ui-bordered mr-4" alt="" height="300" width="300">
                        <div class="media-body">
                          <a href="#" class="d-block text-dark"><?php echo $producto['nombre']; ?></a>
                        </div>
                      </div>
                    </td>
                    <td class="text-right font-weight-semibold align-middle p-4">$<?php echo $producto['precio_lista']; ?></td>
                    <td class="align-middle p-4"><input type="text" class="form-control text-center" value="<?php echo $producto['cantidad_prod']; ?>"></td>
                    <td class="text-right font-weight-semibold align-middle p-4">$<?php echo $producto['precio_lista'] * $producto['cantidad_prod']; ?></td>
                    <td class="text-center align-middle px-0"><a href="/eliminar-producto.php?id=' . $producto['id'] . '" class="shop-tooltip close float-none text-danger" title="Eliminar" data-original-title="Eliminar">×</a></td>
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
              <a href="/views/finalizar-compra.html"><button type="button" class="btn btn-lg btn-primary mt-2" style="background-color: #198754; color: white;">Finalizar compra</button></a>
            </div>
        
          </div>
      </div>
  </div>


  
    <!-- Start Footer -->
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
                        <li><a class="text-decoration-none" href="#">River Plate</a></li>
                        <li><a class="text-decoration-none" href="#">Boca Juniors</a></li>
                        <li><a class="text-decoration-none" href="#">Selección Argentina</a></li>
                        <li><a class="text-decoration-none" href="#">San Lorenzo</a></li>
                        <li><a class="text-decoration-none" href="#">Estudiantes</a></li>
                        <li><a class="text-decoration-none" href="#">Racing</a></li>
                        <li><a class="text-decoration-none" href="#">Independiente</a></li>
                    </ul>
                </div>

                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Accesos rápidos</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li><a class="text-decoration-none" href="#">Inicio</a></li>
                        <li><a class="text-decoration-none" href="#">Sobre Nosotros</a></li>
                        <li><a class="text-decoration-none" href="#">Tienda</a></li>
                        <li><a class="text-decoration-none" href="#">Contacto</a></li>
                    </ul>
                </div>

            </div>

            <div class="row text-light mb-4">
                <div class="col-12 mb-3">
                    <div class="w-100 my-3 border-top border-light"></div>
                </div>
                <div class="col-auto me-auto">
                    <ul class="list-inline text-left footer-icons">
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="http://facebook.com/"><i class="fab fa-facebook-f fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.instagram.com/"><i class="fab fa-instagram fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://twitter.com/"><i class="fab fa-twitter fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.linkedin.com/"><i class="fab fa-linkedin fa-lg fa-fw"></i></a>
                        </li>
                    </ul>
                </div>

                <!--
                <div class="col-auto">
                    <label class="sr-only" for="subscribeEmail">Email address</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control bg-dark border-light" id="subscribeEmail" placeholder="Email address">
                        <div class="input-group-text btn-success text-light">Subscribe</div>
                    </div>
                </div>
                -->
                
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
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->
</body>

</html>
