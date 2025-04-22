<?php
require('../assets/conexionBD.php');
$conexion = obtenerConexion();
session_start();
$usuario_id = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_mascota = $_POST['nombre_mascota'];
    $tipo_mascota = $_POST['tipo_mascota'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $descripcion = $_POST['descripcion'];
    $ubicacion_actual = $_POST['ubicacion_actual'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_registro = date("Y-m-d H:i:s");

    $imagen = $_FILES['imagen'];
    $directorio = "fotos_mascotas/";

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombre_imagen = uniqid() . "-" . basename($imagen["name"]);
    $ruta_foto = $directorio . $nombre_imagen;

    if (move_uploaded_file($imagen["tmp_name"], $ruta_foto))
        $sql = "INSERT INTO mascotas(nombre_mascota,tipo_mascota,raza,edad,sexo,descripcion,ubicacion_actual,fecha_registro,usuario_id,imagen) 
        values('$nombre_mascota','$tipo_mascota','$raza','$edad','$sexo','$descripcion','$ubicacion_actual','$fecha_registro','$usuario_id','$ruta_foto')";
    if ($conexion->query($sql) === true) {
       header("Location: //adopta/");
    }
}
$conexion->close();
?>