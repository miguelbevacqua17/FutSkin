<?php
###################################################################################
## Seteo y Configuracion inicial
//directorio donde se suben los archivos. Debe estar creado previamente
// hay que pasar por terminal para que funciona: 
// chmod 777 uploads
// chmod 755 uploads



include "sesion.php";
include "bd.php";

$dirUplod="uploads/";

// extensiones válidas .. agregar aqui si flta alguna
$extValImagen     = ["jpg","png","jpeg","gif","bmp","svg", "webp"]; 
$extValDocumento  = ["pdf","doc","docx","xls","xlsx","txt","csv","docm","dot","dotx","ppt","pptx"]; 
$extValAudio      = ["wav","aiff","mp3","mpga","mp4","wave", "bwf","wma","mid","midi"]; 
$extValVideo      = ["mp4","m4v","avi","mkv","flv","mov","mpeg","mpg","wmv","asf"];
$extValComprimido = ["dmg","iso","gz","gzip","7z","zip","rar"];
$extValOtros      = [];


// tamaño máximo de archivo a subir
$tamMaxArchivo = 500000;

###################################################################################
function comprobarExtension($fileName){
    // Comprueba si la extensión de 'fileName' es válida par subir al servidor
    $checkOK = 1;    
    global $extValImagen;
    global $extValDocumento;
    global $extValAudio; 
    global $extValVideo;
    global $extValComprimido;
    global $extValOtros;

    $fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));

    $esta =in_array($fileExt,  $extValImagen) || in_array($fileExt,  $extValDocumento);
    $esta =$esta || in_array($fileExt,  $extValAudio) || in_array($fileExt,  $extValVideo);
    $esta =$esta || in_array($fileExt,  $extValComprimido) || in_array($fileExt,  $extValOtros);

    if( !($esta) ) {       
        $_SESSION['message']="ERROR: El archivo no es del tipo aceptado.";
        $_SESSION['error']=TRUE;
        $checkOK = 0;
    }
    return $checkOK;
}

function comprobacionPrevia($target_dir,$file_name){
    // compruebaciones varias
    $checkOK = 1;
    $target_file = $target_dir . $file_name;   
    $fileTypeExtension = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    global $tamMaxArchivo;
     
    // comprobar que haya una archivo seleccionado
    if($file_name==""){
        $_SESSION['message']="ERROR: Ningun archivo seleccionado.";
        $_SESSION['error']=TRUE;    
        $checkOK = 0;
    }
    // Comprobar el tamaño del archivo
    else if ($_FILES["fileToUpload"]["size"] > $tamMaxArchivo) {    
        $_SESSION['message']="ERROR: El archivo es demasiado grande.";
        $_SESSION['error']=TRUE;
        $checkOK = 0;
    }
    // Comprobar si existe el archivo
    else if (file_exists($target_file)==1) {        
        $_SESSION['message']="ERROR: El archivo ya existe.";
        $_SESSION['error']=TRUE;
        $checkOK = 0;
    }

    return $checkOK;
} 

function transferirArchivo($target_dir, $file_name, $uploaded_file){
    // transfiere el archivo al servidor, al directorio especificado. 
    // El archivo se encuentra en el directorio temporal del servidor y lo transfiere al directorio especificado
    $target_file = $target_dir . $file_name;   
    $uploadOk = 1;
    
    if (move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
        $msj = "El archivo '".htmlspecialchars(basename($uploaded_file["name"]))."' ha sido subido con éxito.";
        $msj = $msj." Y se almacenó bajo el nombre único: '".$file_name."'.";
        $_SESSION['message'] = "OK: ".$msj;
        $_SESSION['error'] = FALSE;

    } else {
        $msj = "Hubo un error al cargar el archivo.";
        $_SESSION['message'] = "ERROR: ".$msj;
        $_SESSION['error'] = TRUE;
    }
    return $uploadOk;
}

function crearNombreUnicoArchivo($fileTypeExtension){
    // retorna un string para ser utilizado como nuevo nombre y único en el directorio del servidor
    // Se utiliza un nuevo nombre desde la variable sesion $_SESION['rename'].
    // Si no está seteada $_SESION['rename'], entonces se genera un string unico con md5( uniqid())
    $nuevo_nombre="";
    if (isset($_SESION['rename'])){
        $nuevo_nombre=$_SESION['rename'];
    }else{
        $nuevo_nombre = md5( uniqid()).".".$fileTypeExtension;
    }
    return $nuevo_nombre;
}
function subirArchivo($target_dir, $uploaded_file){
    // función principal, encargada de subir el archivo al directorio target_dir
    // retorna NULL si no pudo subir archivo
    // retorna el arreglo arrFileName  si pudo subir con éxito
    session_start();
    $arrFileName = NULL;
    $unique_file_name="";   
    
    $original_file_name = basename($uploaded_file["name"]);
    $target_file = $target_dir . $original_file_name;   
    $fileTypeExtension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
   
    #chequeos
    $checkOk = comprobacionPrevia($target_dir, $original_file_name);  
    $checkOk = $checkOk && comprobarExtension($original_file_name);

    // realiza la transferencia si checkOk es TRUE
    if ($checkOk){          
        $unique_file_name = crearNombreUnicoArchivo($fileTypeExtension); // Renombra a un nombre único
        $uploadOk = transferirArchivo($target_dir, $unique_file_name, $uploaded_file); // transfiere archivo
        if ($uploadOk){ // si el archivo se transfirió
            $arrFileName = array($original_file_name, $unique_file_name); // preparar variable de retorno
        }
    }        
    return $arrFileName;
}



function main(){
    global $dirUplod;

    try {
        //**** Forma de llamada para subir un archivo */
        $uploaded_files = subirArchivo($dirUplod, $_FILES["fileToUpload"]);
        //****----------------------------------------*/

        // Verifica si el formulario se ha enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $uploaded_files) {
            list($original_file_name, $unique_file_name) = $uploaded_files;


            // Recoge los datos del formulario
            $nombre = $_POST["nombre"];
            $precioLista = $_POST["precio"];
            $categoriaID = $_POST["categoria"];
            $descuento = $_POST["descuento"];
            $stock = $_POST["stock"];
            $descripcion = $_POST["descripcion"];
            $imagen = $unique_file_name; // Utiliza el nuevo nombre del archivo

            $conn = conectarBDUsuario();

            // Llamar a la función para insertar el nuevo producto
            $resultado = insertarNuevoProducto($categoriaID, $nombre, $imagen, $descripcion, $precioLista, $descuento, $stock);

            // Verificar el resultado
            if ($resultado) {
                echo "Nuevo producto agregado correctamente.";
            } else {
                echo "Error al agregar el nuevo producto.";
            }

            cerrarBDConexion($conn);
        }
    } catch (Exception $e) {
        $rrr = 0;
        $_SESSION['message'] = "ERROR: No se pudo realizar la operación";
        $_SESSION['error'] = TRUE;
    }

    header("Location: principal.php");
}

main(); // Llama a la función principal

?>
