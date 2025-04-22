<?php
session_start();
require('../assets/conexionBD.php');
$conexion = obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_mascota'])) {
    $id_mascota = intval($_POST['id_mascota']);
    $id_usuario = $_SESSION['id_usuario'];

    try {
        // 1. Obtenemos la información de la mascota, incluyendo la ruta completa de la imagen
        $stmt = $conexion->prepare("SELECT usuario_id, imagen FROM mascotas WHERE id_mascota = ?");
        $stmt->bind_param("i", $id_mascota);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $mascota = $result->fetch_assoc();

            // 2. Verificamos que el usuario sea el dueño de la publicación
            if ($mascota['usuario_id'] == $id_usuario) {

                // 3. Eliminar la imagen principal si existe
                if (!empty($mascota['imagen'])) {
                    // Usamos la ruta exacta almacenada en la base de datos
                    $ruta_imagen = $mascota['imagen'];
                    
                    // Verificamos si la ruta es relativa o absoluta
                    if (!file_exists($ruta_imagen) && file_exists('../'.$ruta_imagen)) {
                        $ruta_imagen = '../'.$ruta_imagen;
                    }

                    if (file_exists($ruta_imagen)) {
                        if (!unlink($ruta_imagen)) {
                            error_log("Error al eliminar: ".$ruta_imagen);
                            throw new Exception("No se pudo eliminar la imagen de la mascota");
                        }
                    }
                }

                // 4. Eliminar el registro de la base de datos
                $stmt = $conexion->prepare("DELETE FROM mascotas WHERE id_mascota = ?");
                $stmt->bind_param("i", $id_mascota);

                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Publicación eliminada correctamente";
                } else {
                    throw new Exception("Error al eliminar la publicación de la base de datos");
                }
            } else {
                throw new Exception("No tienes permiso para eliminar esta publicación");
            }
        } else {
            throw new Exception("La publicación no existe");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
} else {
    $_SESSION['error'] = "Solicitud inválida";
}

$conexion->close();
header("Location: MisPublicaciones.php");
exit();
?>