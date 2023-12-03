<?php
  // Incluimos el código de sesion.php y bd.php
    include "bd.php";     
    include "sesion.php";
  //session_start();

function main(){
    // Obtenemos los datos cargados en el formulario de signup.
    $apellido = $_POST['apellido'];       
    $nombre = $_POST['nombre']; 
    $email = $_POST['email'];       
    $password = $_POST['password']; 
    $password_r = $_POST['password_r'];

    // abrimos conexión a base de datos, en este caso 'bd_usuario'
    $conn = conectarBDUsuario();  
    if ($password==$password_r){
      
        // Ejecutamos consulta select
        // Verificación si existe el email en base de datos
        $resVerEmail = verficarEmail($conn,$email);
        //die();
        if($resVerEmail!=NULL && $resVerEmail->num_rows==0){ 
            
            // Ejecutamos la consulta inserción 
            // agregar nuevo usuario
            $filasAfectadas = agregarUsuario($conn,$apellido,$nombre,$email,$password);

            // cerrar conexión '$conn' de base de datos
            cerrarBDConexion($conn);  
    
            if ($filasAfectadas>0){
                // o bien ir a signin.html
                crearSesion('email', $email); // crea sesion y redirige a principal
            }            
            } else {
                if ($resVerEmail!=NULL){
                    echo 'Email existente. <a href="signup.html">vuelva a intenarlo</a>.<br/>';
                } else { 
                echo "$resVerEmail";
                echo ' <a href="signup.php">vuelva a intenarlo</a>.<br/>';            
            }         
        }
    } else {   
        echo 'No coincide los password. <a href="signup.html">vuelva a intenarlo</a>.<br/>';
    }    
// cerrar conexión '$conn' de base de datos
cerrarBDConexion($conn);   
}

main();

?>
