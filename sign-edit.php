<?php
include "bd.php";
include "sesion.php";


    $sesionUsuario = controlarSesion();

    // Variables para el contenido de los input
    $email = "no data";
    $apellido = "no data";
    $nombre = "no data";
    $password = "no data";

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
            $password = $resultado['contrasena'];
        }
    }

    // Verificar si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario
        $nuevoEmail = $_POST["nuevo_email"];
        $nuevoApellido = $_POST["nuevo_apellido"];
        $nuevoNombre = $_POST["nuevo_nombre"];
        $nuevoPassword = $_POST["nuevo_password"];

        // Abrir conexión a la base de datos
        $conn = conectarBDUsuario();

        // Llamar a la función para actualizar los datos del usuario
        $actualizado = actualizarDatosUsuario($conn, $sesionUsuario, $nuevoEmail, $nuevoApellido, $nuevoNombre, $nuevoPassword);

        // Cerrar conexión '$conn' de base de datos
        cerrarBDConexion($conn);

        if ($actualizado) {
            echo "Datos actualizados correctamente.";
            // Puedes redirigir al usuario a otra página o mostrar un mensaje de éxito.
        } else {
            echo "Error al actualizar los datos.";
            // Puedes mostrar un mensaje de error o realizar alguna otra acción.
        }
    }

// Función para actualizar los datos del usuario en la base de datos
function actualizarDatosUsuario($conn, $sesionUsuario, $nuevoEmail, $nuevoApellido, $nuevoNombre, $nuevoPassword){
    // Aquí debes implementar la lógica para realizar el UPDATE en la base de datos.
    // Debes tener una tabla en la base de datos donde almacenes los datos del usuario.

    // Ejemplo de consulta (asegúrate de adaptarla a tu esquema de base de datos):
    $sql = "UPDATE clientes SET email=?, apellido=?, nombre=?, contrasena=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    // Bind the parameters
    $stmt->bind_param("ssssi", $nuevoEmail, $nuevoApellido, $nuevoNombre, $nuevoPassword, $sesionUsuario);

    // Execute the statement
    $resultado = $stmt->execute();

    return $resultado;
}

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
    <link rel="stylesheet" href="/assets/css/admin-style.css">

</head>


    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="/views/index.html">
                FutSkin
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/views/index.html">Inicio</a>
                        </li>

                        <!--
                        <li class="nav-item">
                            <a class="nav-link" href="/views/about.html">Sobre Nosotros</a>
                        </li>
                        -->

                        <li class="nav-item">
                            <a class="nav-link" href="/views/shop.html">Tienda</a>
                        </li>

                        <!--
                        <li class="nav-item">
                            <a class="nav-link" href="/views/contact.html">Contacto</a>
                        </li>
                        -->
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/views/creacion-producto.html">Nuevo producto</a>
                        </li>
                    </ul>
                </div>
                
                <div class="navbar align-self-center d-flex">

                <!--
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                -->

                    <a class="nav-icon position-relative text-decoration-none" href="/views/carrito.html">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">2</span>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="/views/login.html">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">1</span>
                    </a>
                </div>

            </div>

        </div>
    </nav>
    <!-- Close Header -->


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
            <h1 class="h1">Datos de usuario</h1>
            <p>
                Ver compras y editar perfil
            </p>
        </div>
    </div>


    <!-- Start Contact -->
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
                        <label for="inputname">Contraseña</label>
                        <input type="password" class="form-control mt-1" id="precio" name="precio" placeholder="Contraseña">
                    </div>
                    
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Dirección de entrega</label>
                    <input type="text" class="form-control mt-1" id="name" name="name" placeholder="Dirección de entrega">
                </div>
                
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Altura de la calle</label>
                    <input type="text" class="form-control mt-1" id="precio" name="precio" placeholder="Altura de la calle">
                </div>

                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Piso / departamento</label>
                    <input type="text" class="form-control mt-1" id="name" name="name" placeholder="Piso / departamento">
                </div>
                
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Barrio / Localidad</label>
                    <input type="text" class="form-control mt-1" id="precio" name="precio" placeholder="Barrio / Localidad">
                </div>


                
                <div class="row">
                    <div class="col text-end mt-2">
                        <button <?php $estadoBotonEnviar ?> type="submit" class="btn btn-success btn-lg px-3" onclick="return confirm('Confirma actualizar los datos ?');" class="btn btn-default">Enviar</button>
                        <button type="button" onclick="redirigir('signin.html')" class="btn btn-default">Sign in</button>
                    </div>
                </div>
            </div>
          </form>
            
            


            <div class="col-md-9 m-auto" style="padding-top:20px;">
                <div class="row py-5">
                    <p class="card-title">MIS COMPRAS</p>
                    <div class="table-responsive">
                      <table id="recent-purchases-listing" class="table">
                        <thead>
                          <tr>
                              <th>Número de orden</th>
                              <th>Fecha</th>
                              <th>Monto</th>
                              <th>Estado</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                              <td>003</td>
                              <td>11/09/2023</td>
                              <td>$79.000</td>
                              <td>Pagado</td>
                          </tr>
                          <tr>
                              <td>003</td>
                              <td>11/09/2023</td>
                              <td>$79.000</td>
                              <td>Pagado</td>
                          </tr>
                          <tr>
                            <td>003</td>
                            <td>11/09/2023</td>
                            <td>$79.000</td>
                            <td>Pagado</td>
                        </tr>
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