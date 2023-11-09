<!--
CÓDIGO PHP CON HTML EMBEBIDO
-->
<?php
include "bd.php";     //https://www.w3schools.com/php/php_includes.asp
include "sesion.php";

function main(){
  $sesionUsuario = controlarSesion();

  //variables para el contenido de los input
  $email="";
  $apellido="";
  $nombre="";
  $password="";
  //estado inicial del boton enviar 
  //$estadoBotonEnviar="disabled";
  $estadoBotonEnviar="";
  if ($sesionUsuario!=NULL){

    // abrir conexión a base de datos, en este caso 'bd_usuario'
    $conn = conectarBDUsuario();
    // Ejecutar consulta
    $resultado = consultaDatosUsuario($conn,$sesionUsuario);
    // cerrar conexión '$conn' de base de datos
    cerrarBDConexion($conn);

    if($resultado!=NULL ){  
        $email=$resultado['email'];
        $apellido=$resultado['apellido'];
        $nombre=$resultado['nombre'];
        $password=$resultado['password'];
    }
    
  }

  $pagina=<<<PAGINA
      <!DOCTYPE html>
      <html>
        <head>
          <title>Login en PHP</title>
          <meta name="viewport" content="initial-scale=1.0">
          <meta charset="utf-8">
          <!-- Latest compiled and minified CSS -->
          
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

          <style>
            .container{margin-top:100px}
          </style>
        </head>
        <body>
          <div class="container">
          
            <form class="form-horizontal" action="sign-update.php" method="post">       

              <div class="form-group">
                  <label class="col-sm-2 "></label>
                  <div class="col-sm-10">
                      <p class="h3"> EDITAR USUARIO</p>
                  </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input disabled  type="email" class="form-control" name="email" id="inputEmail3" placeholder="Email" required value={$email}>
                </div>
              </div>
              <div class="form-group">
                  <label for="inputApellido" class="col-sm-2 control-label">Apellido</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="apellido" id="inputApellido" placeholder="Apellido" required value={$apellido}>
                  </div>
              </div>
              <div class="form-group">
                  <label for="inputNombre" class="col-sm-2 control-label">Nombre</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="nombre" id="inputNombre" placeholder="Nombre" required value={$nombre} >
                  </div>
              </div>


              <div class="form-group">
                <label for="inputPassword5" class="col-sm-2 control-label">Password Actual</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" name="password" id="inputPassword5" placeholder="Password" required>
                </div>
              </div>       

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button {$estadoBotonEnviar} type="submit" onclick="return confirm('Confirma actualizar los datos ?');" class="btn btn-default">Enviar</button>
                  <button type="button" onclick="redirigir('signin.html')" class="btn btn-default">Sign in</button>
              </div>      
              </div>
              
            </form>
          </div>

          
      <script type="text/javascript">
        function redirigir(url){
          window.location.href = url;
        }  
      </script>

      </html>

      PAGINA;
echo $pagina;
}
 
main();
?>


