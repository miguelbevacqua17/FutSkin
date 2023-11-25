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
          echo "User Email: $sesion // ";
          echo "User Name: $nombre // ";
          echo "User Role: $rol";
          // Resto del código que usa $nombre y $rol
      } else {
          echo "Error al obtener datos del usuario.";
      }
  } else {
      echo "No hay sesión iniciada.";
  }

  $categoriasHTML = traerCategoriasHTML();
  $productosHTML = traerProductosHTML("detalle");
  $nombresCategorias = traerColumnaTabla('nombre', 'categorias');
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
            <a class="navbar-brand text-success logo h1 align-self-center" href="/principal.php">FutSkin</a>
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
                                <a class="nav-link" href="/crecion-producto.php">Nuevo producto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin.php">Vista Administrador</a>                        
                            </li>
                                <?php } else { ?>
                                    <?php foreach ($nombresCategorias as $categoria) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><?php echo $categoria; ?></a>
                            </li>
                                <?php } } ?>
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

    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="h2 pb-4">Categorías</h1>
                <ul class="list-unstyled templatemo-accordion">
                    <li class="pb-3">
                        <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                            Equipos
                            <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                        </a>
                        <ul class="collapse show list-unstyled pl-3">   
                        <?php foreach ($nombresCategorias as $nombreCategoria): ?>
                            <li><?php echo $nombreCategoria; ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-inline shop-top-menu pb-3 pt-1">
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none mr-3" href="#">Todas</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none mr-3" href="#">River</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none" href="#">Boca</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none" href="#">Selección</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <?php echo $productosHTML ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <!--Controls-->
    <div class="col-1 align-self-center">
        <a class="h1" href="#multi-item-example" role="button" data-bs-slide="next">
            <i class="text-light fas fa-chevron-right"></i>
        </a>
    </div>
    <!--End Controls-->
    
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Brands-->


<!-- FOOTER -->
<footer class="bg-dark" id="tempaltemo_footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light logo">FutSkin</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li>
                            <i class="fas fa-map-marker-alt fa-fw"></i>Dirección
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