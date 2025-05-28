<?php
session_start();
require('../assets/conexionBD.php');
$conexion = obtenerConexion();
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $conexion->real_escape_string($_POST['nombre_completo']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);

    // Procesar imagen de perfil
    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        $directorio = "../assets/img/fotos_perfil/";

        // Validar tipo de archivo
        $permitidos = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($imagen['type'], $permitidos)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG, PNG o JPG";
            header("Location: MiCuenta.php");
            exit();
        }

        // Validar tamaño (máx 2MB)
        if ($imagen['size'] > 2097152) {
            $_SESSION['error'] = "La imagen es demasiado grande. Máximo 2MB permitidos";
            header("Location: MiCuenta.php");
            exit();
        }

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombre_imagen = uniqid() . "-" . basename($imagen["name"]);
        $ruta_foto = $directorio . $nombre_imagen;

        if (move_uploaded_file($imagen["tmp_name"], $ruta_foto)) {
            // Eliminar la imagen anterior si existe
            $sql_old = "SELECT img_perfil FROM usuarios WHERE id_usuario=$id_usuario";
            $result = $conexion->query($sql_old);
            if ($result->num_rows > 0) {
                $old_img = $result->fetch_assoc()['img_perfil'];
                if (!empty($old_img) && file_exists($old_img)) {
                    unlink($old_img);
                }
            }

            // Actualizar con nueva imagen
            $sql = "UPDATE usuarios SET nombre_completo='$nombre_completo', telefono='$telefono', img_perfil='$ruta_foto', direccion='$direccion' WHERE id_usuario=$id_usuario";
        } else {
            $_SESSION['error'] = "Error al subir la imagen";
            header("Location: MiCuenta.php");
            exit();
        }
    } else {
        // Actualizar sin cambiar la imagen
        $sql = "UPDATE usuarios SET nombre_completo='$nombre_completo', telefono='$telefono', direccion='$direccion' WHERE id_usuario=$id_usuario";
    }

    if ($conexion->query($sql)) {
        $_SESSION['mensaje'] = "Perfil actualizado correctamente";
    } else {
        $_SESSION['error'] = "Error al actualizar el perfil: " . $conexion->error;
    }

    header("Location: MiCuenta.php");
    exit();
}

$conexion->close();
