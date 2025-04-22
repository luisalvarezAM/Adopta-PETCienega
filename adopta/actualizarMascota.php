<?php
session_start();
require('../assets/conexionBD.php');
$conexion = obtenerConexion();
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mascota = intval($_POST['id_mascota']);
    
    // Verificar que el usuario es dueño de la mascota
    $sql_verificar = "SELECT usuario_id FROM mascotas WHERE id_mascota = $id_mascota";
    $result_verificar = $conexion->query($sql_verificar);
    
    if ($result_verificar->num_rows == 0 || $result_verificar->fetch_assoc()['usuario_id'] != $id_usuario) {
        $_SESSION['error'] = "No tienes permiso para editar esta mascota";
        header("Location: MisPublicaciones.php");
        exit();
    }

    // Recoger datos del formulario
    $nombre_mascota = $conexion->real_escape_string($_POST['mascota']);
    $tipo_mascota = $conexion->real_escape_string($_POST['tipo_mascota']);
    $raza = $conexion->real_escape_string($_POST['raza']);
    $edad = intval($_POST['edad']);
    $sexo = $conexion->real_escape_string($_POST['sexo']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $ubicacion_actual = $conexion->real_escape_string($_POST['ubicacion_actual']);
    $estatus_adopcion = $conexion->real_escape_string($_POST['estatus_adopcion']);

    // Procesar imagen si se subió una nueva
    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        $directorio = "fotos_mascotas/";

        // Validar tipo de archivo
        $permitidos = ['image/jpeg', 'image/png'];
        if (!in_array($imagen['type'], $permitidos)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG o PNG";
            header("Location: editarMascota.php?id=$id_mascota");
            exit();
        }

        // Validar tamaño (máx 2MB)
        if ($imagen['size'] > 2097152) {
            $_SESSION['error'] = "La imagen es demasiado grande. Máximo 2MB permitidos";
            header("Location: editarMascota.php?id=$id_mascota");
            exit();
        }

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombre_imagen = uniqid() . "-" . basename($imagen["name"]);
        $ruta_foto = $directorio . $nombre_imagen;

        if (move_uploaded_file($imagen["tmp_name"], $ruta_foto)) {
            // Eliminar la imagen anterior si existe
            $sql_old = "SELECT imagen FROM mascotas WHERE id_mascota=$id_mascota";
            $result = $conexion->query($sql_old);
            if ($result->num_rows > 0) {
                $old_img = $result->fetch_assoc()['imagen'];
                if (!empty($old_img) && file_exists($old_img)) {
                    unlink($old_img);
                }
            }

            // Actualizar con nueva imagen
            $sql = "UPDATE mascotas SET 
                    nombre_mascota='$nombre_mascota', 
                    tipo_mascota='$tipo_mascota', 
                    raza='$raza', 
                    edad=$edad, 
                    sexo='$sexo', 
                    descripcion='$descripcion', 
                    ubicacion_actual='$ubicacion_actual', 
                    estatus_adopcion='$estatus_adopcion', 
                    imagen='$ruta_foto' 
                    WHERE id_mascota=$id_mascota";
        } else {
            $_SESSION['error'] = "Error al subir la imagen";
            header("Location: editarMascota.php?id=$id_mascota");
            exit();
        }
    } else {
        // Actualizar sin cambiar la imagen
        $sql = "UPDATE mascotas SET 
                nombre_mascota='$nombre_mascota', 
                tipo_mascota='$tipo_mascota', 
                raza='$raza', 
                edad=$edad, 
                sexo='$sexo', 
                descripcion='$descripcion', 
                ubicacion_actual='$ubicacion_actual', 
                estatus_adopcion='$estatus_adopcion' 
                WHERE id_mascota=$id_mascota";
    }

    if ($conexion->query($sql)) {
        $_SESSION['mensaje'] = "Mascota actualizada correctamente";
    } else {
        $_SESSION['error'] = "Error al actualizar la mascota: " . $conexion->error;
    }

    header("Location: MisPublicaciones.php");
    exit();
}

$conexion->close();
?>