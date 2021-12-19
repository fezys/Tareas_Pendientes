<?php 
    require_once 'login.php';
    $conexion = new mysqli($hn, $un, $pw, $db,$port);

    if($conexion->connect_error) die("Error fatal de conexión ");

    date_default_timezone_set("America/Lima");

    $query = "CREATE TABLE tarea (
        titulo varchar (50) not null,
        contenido text default null,
        fecha_registro datetime,
        fecha_vencimiento datetime,
        estado varchar (10),
        username VARCHAR(32) NOT NULL 
    )";
    
    $result = $conexion->query($query);
    if (!$result) die("Error fatal de ingreso");
?>