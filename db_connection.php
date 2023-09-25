<?php

$dbhost = "mysql-futskin.alwaysdata.net";
$dbuser = "futskin";
$dbpass = "futskin-bd2023";
$db = "futskin_db";

$conexion = new mysqli($dbhost, $dbuser, $dbpass,$dbname);

if($conexion -> connect_errno) {
    die("conexion fallida" . $conexion -> connect_errno);
} else {
    echo "conectado";
}

?>