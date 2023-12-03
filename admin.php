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
          echo "User Id: $id // ";
          echo "User Role: $rol //";

          // Resto del código que usa $nombre y $rol
      } else {
          echo "Error al obtener datos del usuario.";
      }
  } else {
      echo "No hay sesión iniciada.";
  }

  $venta = traerVentas();

  function sumarPrecioVentas($venta) {
    $estadoDeseado = 'venta'; 
    $totalPrecioVentas = 0;

    // Iterar sobre el array de ventas
    foreach ($venta as $ventaItem) {
        // Verificar si existe la clave 'precio_venta' en la venta
        if (isset($ventaItem['precio_venta']) && $ventaItem['estado'] === $estadoDeseado) {
            // Sumar el precio_venta al total
            $totalGanancia = $ventaItem['precio_venta']*$ventaItem['cantidad_prod'];
            $totalPrecioVentas += $totalGanancia;
        }
    }

    return $totalPrecioVentas;
}

// Uso de la función
$totalVentas = sumarPrecioVentas($venta);

  //contador ventas
  $estadoDeseado = 'venta'; 
  $contadorVentas = 0;

  // Iterar sobre el array de ventas
    foreach ($venta as $ventaItem) {
    // Verificar si el estado de la venta es el deseado
    if (isset($ventaItem['estado']) && $ventaItem['estado'] === $estadoDeseado) {
        // Incrementar el contador
        $contadorVentas++;
    }
}

$nombresCategorias = traerColumnaTabla('nombre', 'categorias');

// Imprimir el resultado
echo "Número de ventas con estado '$estadoDeseado': $contadorVentas";
?>  

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Majestic Admin</title>
  
  <link rel="apple-touch-icon" href="/assets/img/apple-icon.png">
  <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/templatemo.css">
  <link rel="stylesheet" href="/assets/css/custom.css">

  <!-- Load fonts style after rendering the layout styles -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
  <link rel="stylesheet" href="/assets/css/fontawesome.min.css">

  <link rel="stylesheet" href="/assets/css/admin-style.css">
  <!-- endinject -->
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
    
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="me-md-3 me-xl-5">
                    <h2>Panel de Administrador</h2>
                    <p class="mb-md-0">Panel de admin</p>
                  </div>
  
                </div>
                <!--
                <div class="d-flex justify-content-between align-items-end flex-wrap">
                  <button type="button" class="btn btn-light bg-white btn-icon me-3 d-none d-md-block ">
                    <i class="mdi mdi-download text-muted"></i>
                  </button>
                  <button type="button" class="btn btn-light bg-white btn-icon me-3 mt-2 mt-xl-0">
                    <i class="mdi mdi-clock-outline text-muted"></i>
                  </button>
                  <button type="button" class="btn btn-light bg-white btn-icon me-3 mt-2 mt-xl-0">
                    <i class="mdi mdi-plus text-muted"></i>
                  </button>
                  <button class="btn btn-primary mt-2 mt-xl-0">Generate report</button>
                </div>
                -->
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  
                  <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-none d-xl-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-calendar-heart icon-lg me-3 text-primary"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Inicio de actividad</small>
                            <div class="dropdown">
                                <h5 class="mb-0 d-inline-block">26 Oct 2023</h5>
                            </div>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total compras</small>
                            <h5 class="me-2 mb-0"><?php echo $contadorVentas;?></h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-eye me-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Ganancias</small>
                            <?php ?>
                            <h5 class="me-2 mb-0">$<?php echo $totalVentas;?></h5>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                    
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
              
            </div>
            <div class="col-md-5 grid-margin stretch-card">
              
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">ÚLTIMAS VENTAS</p>
                  <div class="table-responsive">
                    <table id="recent-purchases-listing" class="table">
                      <thead>
                        <tr>
                            <th>Número de orden</th>
                            <th>Mail usuario</th>
                            <th>Producto</th>
                            <th>Precio unitario</th>
                            <th>Cantidad</th>
                            <th>Precio total</th>
                            <th>Estado</th> 
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      $orden = 0;

                        foreach ($venta as $ventas) { 
                          $id = $ventas['id']; 
                          $cliente = $ventas['cliente_fk'];
                          $mailCliente = $ventas['email'];
                          $producto = $ventas['producto_fk'];
                          $nombreProducto = $ventas['producto'];
                          $precio = $ventas['precio_venta'];
                          $cantidad = $ventas['cantidad_prod'];
                          $total = $precio*$cantidad;
                          $estado = $ventas['estado'];

                          if ($estado == "venta") {
                            $orden = $orden+1;
                        ?>
                          <tr>
                            <td><?php echo $orden ?></td>
                            <td><?php echo $mailCliente ?></td>
                            <td><?php echo $nombreProducto ?></td>
                            <td>$<?php echo $precio ?></td>
                            <td><?php echo $cantidad ?></td>
                            <td>$<?php echo $total ?></td>
                            <td><?php echo $estado ?></td>
                          </tr>

                      <?php      }  
                              }
                      ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->


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



      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->



</body>

</html>