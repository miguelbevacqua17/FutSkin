<?php
  // Incluimos el código de sesion.php y bd.php
include "sesion.php";
include "bd.php";

  // Conectamos con la base de datos
$conn = conectarBDUsuario();

// Verificamos si la sesión está iniciada y la clave 'email' está presente
if (isset($_SESSION['email'])) {
    $sesion = $_SESSION['email'];
    // Verificamos si la consulta de datos del usuario es exitosa
    $usuario = consultaDatosUsuario($conn, $sesion); 
    
    // Si la variable usuario trae datos, los declaramos en variables y las mostramos con echo
    if ($usuario !== null) {
        $nombre = $usuario['nombre'];
        $rol = $usuario['rol'];
        $direccion = $usuario['direccion'];
        $altura = $usuario['altura'];
        $id = $usuario['id_cliente'];

        echo "User Email: $sesion // ";
        echo "User Name: $nombre // ";
        echo "User Id: $id // ";
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
    // Abrimos la conexión a base de datos
    $conn = conectarBDUsuario();
    // Ejecutamos la consulta
    $resultado = consultaDatosUsuario($conn, $sesionUsuario);
    // Cerramos la conexión '$conn' de base de datos
    cerrarBDConexion($conn);

    if ($resultado != NULL) {
        // Obtenemos los datos del usuario
        $email = $resultado['email'];
        $apellido = $resultado['apellido'];
        $nombre = $resultado['nombre'];
        $direccion = $resultado['direccion'];   
        $altura = $resultado['altura'];   
        $piso = $resultado['piso'];   
        $barrio = $resultado['barrio'];               
    }
}

// En la variable $ventasUsuario traemos el resultado de la funcion buscarPedidosUsuario()
// para ver el historial de las compras que realizo
 $ventasUsuario = buscarPedidosUsuario($_SESSION['email']);

// Traemos los datos de las categorias para mostrar en el footer
 $nombresCategorias = traerColumnaTabla('nombre', 'categorias');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>FutSkin - Editar Usuario</title>
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
    <link rel="stylesheet" href="/assets/css/admin-style.css">

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

                            <!-- Si el usuario no es admin, mostramos las vistas para el usuario -->
                            <?php if (isset($_SESSION['email']) && $rol != '1') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/sign-edit.php">Editar datos usuario</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/carrito.php">Carrito</a>
                                </li>

                            <!-- Si el usuario es admin, mostramos las vistas para el administrador -->
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
                            
                        <!-- Si la sesion está iniciada, muestra el mensaje "Hola, $nombre" y el botón LOGOUT -->
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

    <!-- Modal (?) -->
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

    <!-- Contenido -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Datos de usuario</h1>
            <p>
                Ver compras y editar perfil
            </p>
        </div>
    </div>

    <!-- Formulario para editar los datos del usuario (agregar datos envio o modificar usuario o envios) -->
    <div class="container py-5">
        <div class="row py-5">
            <form class="col-md-9 m-auto" action="sign-update.php" method="post">
                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="inputname">Nombre de usuario</label>
                        <input type="text" class="form-control mt-1" id="nombre" name="nombre" placeholder="Nombre" required value="<?php echo $nombre?>">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="inputname">Apellido</label>
                        <input type="text" class="form-control mt-1" id="apellido" name="apellido" placeholder="Apellido" required value="<?php echo $apellido?>">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="inputname">Email</label>
                        <input type="text" class="form-control mt-1" id="email" name="email" placeholder="Email" required value="<?php echo $email?>">
                    </div>

                    <div class="form-group col-md-6 mb-3">
                    </div>
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Dirección de entrega</label>
                    <input type="text" class="form-control mt-1" id="direccion" name="direccion" placeholder="Dirección de entrega" required value="<?php echo $direccion?>">
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Altura de la calle</label>
                    <input type="text" class="form-control mt-1" id="altura" name="altura" placeholder="Altura de la calle" required value="<?php echo $altura?>">
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Piso / departamento</label>
                    <input type="text" class="form-control mt-1" id="piso" name="piso" placeholder="Piso / departamento" required value="<?php echo $piso?>">
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Barrio / Localidad</label>
                    <input type="text" class="form-control mt-1" id="barrio" name="barrio" placeholder="Barrio / Localidad" required value="<?php echo $barrio?>">
                </div>
                <div class="row">
                    <div class="col text-end mt-2">

                        <!-- Botón de envio del formulario -->
                        <button type="submit" class="btn btn-success btn-lg px-3" onclick="return confirm('Confirma actualizar los datos?');">Aplicar cambios</button>
                    </div>
                </div>
            </div>
          </form>
        <!-- Fin Formulario -->

            
            <div class="col-md-9 m-auto" style="padding-top:20px;">
                <div class="row py-5">
                    <p class="card-title">HISTORIAL DE COMPRAS</p>
                    <div class="table-responsive">
                      <table id="recent-purchases-listing" class="table">
                        <thead>
                          <tr>
                              <th>Producto</th>
                              <th>Precio unitario</th>
                              <th>Cantidad</th>
                              <th>Precio final</th>
                          </tr>
                        </thead>
                        <tbody>

                        <?php
                        // traemos las ventas del usuario para el historial de compra
                        foreach ($ventasUsuario as $ventas) { 
                            $producto = $ventas['producto_fk'];
                            $nombreProducto = $ventas['producto'];
                            $precio = $ventas['precio_venta'];
                            $cantidad = $ventas['cantidad_prod'];
                            $total = $precio*$cantidad;
                        ?>
                          <tr>
                              <td><?php echo $nombreProducto ?></td>
                              <td>$<?php echo $precio ?></td>
                              <td><?php echo $cantidad ?></td>
                              <td>$<?php echo $total ?></td>
                          </tr>
                        <?php } ?>
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
            </div>
            </div>
            </div>
        </div>
    </div>
    <!-- End Contact -->

    <!-- Start Script -->
    <script src="/assets/js/jquery-1.11.0.min.js"></script>
    <script src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/templatemo.js"></script>
    <script src="/assets/js/custom.js"></script>
    <!-- End Script -->
</body>
    
<script type="text/javascript">
  function redirigir(url){
    window.location.href = url;
  }  
</script>

</html>