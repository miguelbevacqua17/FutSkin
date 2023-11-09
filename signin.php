<?php

include "bd.php";     //https://www.w3schools.com/php/php_includes.asp
include "sesion.php";
  //session_start();
  
  // Obtengo los datos cargados en el formulario de signin.
  $email = $_POST['email'];       //"mariano@gmail.com";
  $password = $_POST['password']; //"1234";

  // abrir conexión a base de datos, en este caso 'bd_usuario'
  $conn = conectarBDUsuario();
  // Ejecutar consulta

  // Consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT * FROM clientes WHERE email= $email AND contrasena = $password";

  $resultado = consultarUsuario($conn,$email,$password);

  // cerrar conexión '$conn' de base de datos
  cerrarBDConexion($conn);
  
  if($resultado!=NULL && $resultado->num_rows>0){  
    crearSesion('email', $email); // crea sesion y redirige
  }else{
    echo "$email $password $sesion";
    echo 'El email o password es incorrecto, <a href="signin.html">vuelva a intenarlo</a>.<br/>';
  }
  
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
<section class="vh-100">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 text-black">
        <!--
          <div class="px-5 ms-xl-4">
            <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
            <span class="h1 fw-bold mb-0">FutSkin</span>
          </div>
        -->
          <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
  
            <form action="signin.php" method="post" style="width: 23rem;">
  
              <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Inicio de sesión</h3>
  
              <div class="form-outline mb-4">
                <input type="email" id="email" name="email" class="form-control form-control-lg" />
                <label class="form-label" for="form2Example18">Email</label>
              </div>
  
              <div class="form-outline mb-4">
                <input type="contrasena" id="password" name="password" class="form-control form-control-lg" />
                <label class="form-label" for="form2Example28">Contraseña</label>
              </div>
  
              <div class="pt-1 mb-4">
                <button class="btn btn-info btn-lg btn-block" type="submit" style="background-color: #198754; color: white;">Iniciar sesión</button>
              </div>

            <!--
                <p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Olvidaste tu contraseña?</a></p>
            -->
                <p>No tenés una cuenta? <a href="/signup.php" class="link-info">REGISTRARSE AQUÍ</a></p>
  
            </form>
  
          </div>
  
        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="/assets/img/categoria-seleccion.jpg"
            alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
        </div>
      </div>
    </div>
  </section>


    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->

  </body>

<script type="text/javascript">
  function redirigir(url){
    window.location.href = url;
  }  
</script>

</html>