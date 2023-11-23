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

  //pasar a funciones  
  $categoriasHTML = traerCategoriasHTML();
  $productosHTML = traerProductosHTML();
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
    

        <!-- BANNER -->
        <div id="template-mo-zay-hero-carousel" class="carousel slide">
            <div class="container">
                <div class="row p-3">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="/assets/img/banner_img_01.jpg" alt="">
                    </div>                            
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left align-self-center">
                            <h1 class="h1 text-success"><b>FutSkin</b> Tienda virtual</h1>
                            <h3 class="h2">Camisetas para los verdaderos hinchas</h3>
                            <p>
                                Las mejores camisetas orignales para alentar a tu equipo en todo momento.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN BANNER -->
    

        <!-- CATEGORIAS -->
        <section class="container py-5">
            <div class="row text-center pt-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Principales categorías</h1>
                    <p>
                      Encontrá a tu equipo
                    </p>
                </div>
            </div>
            <div class="row">

            <?php echo $categoriasHTML ?>

            </div>
        </section>
        <!-- FIN CATEGORIAS -->
    
    
        <!-- PRODUCTOS -->
        <section class="bg-light">
            <div class="container py-5">
                <div class="row text-center py-3">
                    <div class="col-lg-6 m-auto">
                        <h1 class="h1">Los productos más buscados</h1>
                        <p>
                            Estas son los productos más buscados por los hinchas.
                        </p>
                    </div>
                </div>
                <div class="row">

                <?php echo $productosHTML?>

                </div>
            </div>
        </section>
        <!-- FIN PRODUCTOS -->
    

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
    
    </body>
</html>