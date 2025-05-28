<?php
require('../assets/conexionBD.php');
$conexion = obtenerConexion();
session_start();

// Verificar sesión y permisos
if (!isset($_SESSION['id_usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado");
}

$usuario_id = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y sanitizar datos de entrada
    $nombre_mascota = trim($conexion->real_escape_string($_POST['nombre_mascota'] ?? ''));
    $tipo_mascota = intval($_POST['tipo_mascota'] ?? 0);
    $raza = trim($conexion->real_escape_string($_POST['raza'] ?? ''));
    $edad = intval($_POST['edad'] ?? 0);
    $sexo = in_array($_POST['sexo'] ?? '', ['M', 'H']) ? $_POST['sexo'] : 'M';
    $descripcion = trim($conexion->real_escape_string($_POST['descripcion'] ?? ''));
    $municipio = trim($conexion->real_escape_string($_POST['municipio'] ?? ''));
    $direccion = trim($conexion->real_escape_string($_POST['direccion'] ?? ''));
    date_default_timezone_set('America/Mexico_City');
    $fecha_registro = date("Y-m-d H:i:s");
    $estatus_id = 1;
    
    // Validar campos obligatorios (CORREGIDO: quitamos $ubicacion_actual)
    if (empty($nombre_mascota) || empty($raza) || empty($descripcion) || empty($municipio) || empty($direccion)) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Todos los campos obligatorios deben ser completados'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Validar edad
    if ($edad < 0 || $edad > 30) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'La edad debe estar entre 0 y 30 años'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Procesar imagen de la mascota
    $directorio = "../assets/img/fotos_mascotas/";
    $ruta_foto = null;
    $error_imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        
        // Validar tipo de archivo
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $extensiones_permitidas)) {
            $error_imagen = 'Formato de imagen no válido. Solo se permiten JPG, PNG o WEBP';
        }
        
        // Validar tamaño de archivo (máximo 5MB)
        elseif ($imagen['size'] > 5 * 1024 * 1024) {
            $error_imagen = 'La imagen es demasiado grande. Máximo 5MB permitidos';
        }
        
        // Si no hay errores, procesar la imagen
        if (!$error_imagen) {
            // Crear directorio si no existe
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            // Generar nombre único para la imagen
            $nombre_imagen = uniqid('mascota_', true) . '.' . $extension;
            $ruta_foto = $directorio . $nombre_imagen;
            
            // Mover archivo subido
            if (!move_uploaded_file($imagen['tmp_name'], $ruta_foto)) {
                $error_imagen = 'Error al subir la imagen';
                $ruta_foto = null;
            }
        }
    } else {
        $error_imagen = 'Debes subir una imagen de la mascota';
    }

    // Si hay error con la imagen, mostrar mensaje
    if ($error_imagen) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => $error_imagen
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Insertar en la base de datos usando consulta preparada (CORREGIDO)
    $sql = "INSERT INTO mascotas(
        nombre_mascota, 
        tipo_mascota, 
        raza, 
        edad, 
        sexo, 
        descripcion, 
        municipio, 
        direccion, 
        fecha_registro, 
        usuario_id, 
        imagen,
        estatus_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Error al preparar la consulta: ' . $conexion->error
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // CORREGIDO: tipos y parámetros correctos
    $stmt->bind_param(
        "sisisssssssi", // 12 tipos
        $nombre_mascota, 
        $tipo_mascota, 
        $raza, 
        $edad, 
        $sexo, 
        $descripcion, 
        $municipio, 
        $direccion,
        $fecha_registro, 
        $usuario_id, 
        $ruta_foto,
        $estatus_id
    );

    if ($stmt->execute()) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Mascota registrada exitosamente'
        ];
        header("Location: /");
        exit();
    } else {
        // Eliminar imagen si falló la inserción
        if ($ruta_foto && file_exists($ruta_foto)) {
            unlink($ruta_foto);
        }
        
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Error al registrar la mascota: ' . $stmt->error
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $stmt->close();
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    exit("Método no permitido");
}

$conexion->close();
?>