<?php
session_start();
require('../assets/conexionBD.php');
$conexion = obtenerConexion();

// Obtener ID de la mascota
$mascota_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario_id = $_SESSION['id_usuario'];

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mascota_id'])) {
    $mascota_id = intval($_POST['mascota_id']);
    $nombre_adoptante = $conexion->real_escape_string($_POST['nombre_adoptante']);
    $telefono_adoptante = $conexion->real_escape_string($_POST['telefono_adoptante']);
    $fecha_adopcion = $conexion->real_escape_string($_POST['fecha_adopcion']);

    // Procesar imagen de evidencia
    $evidencia_path = '';
    if (!empty($_FILES['evidencia']['name']) && $_FILES['evidencia']['error'] == UPLOAD_ERR_OK) {
        $evidencia = $_FILES['evidencia'];
        $directorio = "evidencias_adopciones/";

        // Validaciones
        $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($evidencia['type'], $permitidos)) {
            $error = "Solo se permiten imágenes JPG/PNG o PDF";
        } elseif ($evidencia['size'] > $max_size) {
            $error = "El archivo es demasiado grande (máx 2MB)";
        } else {
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $nombre_archivo = uniqid() . '_' . basename($evidencia["name"]);
            $evidencia_imagen = $directorio . $nombre_archivo;

            if (!move_uploaded_file($evidencia["tmp_name"], $evidencia_imagen)) {
                $error = "Error al subir la evidencia";
            }
        }
    } else {
        $error = "Debes subir una evidencia de adopción";
    }

    // Si no hay errores, actualizar la base de datos
    if (!isset($error)) {
        // Primera consulta: Actualizar tabla mascotas
        $sql = "UPDATE mascotas SET 
            estatus_adopcion = 'Adoptado'
            WHERE id_mascota = $mascota_id AND usuario_id = $usuario_id";

        // Segunda consulta: Actualizar tabla adopciones
        $sql2 =  "INSERT INTO adopciones 
        (mascota_id, usuario_id, nombre_adoptante, numero_telefonico, fecha_adopcion, imagen_evidencia)
        VALUES ('$mascota_id', '$usuario_id', '$nombre_adoptante', '$telefono_adoptante', '$fecha_adopcion', '$evidencia_imagen')";

        // Ejecutar las consultas por separado
        $success = true;

        if (!$conexion->query($sql)) {
            $error = "Error al actualizar mascota: " . $conexion->error;
            $success = false;
        }

        if ($success && !$conexion->query($sql2)) {
            $error = "Error al actualizar adopción: " . $conexion->error;
            $success = false;
        }

        if ($success) {
            $mensaje = "Adopción registrada correctamente";
        }
    }
}

// Obtener datos de la mascota
$sql_mascota = "SELECT nombre_mascota FROM mascotas WHERE id_mascota = $mascota_id AND usuario_id = $usuario_id";
$result = $conexion->query($sql_mascota);
$mascota = $result->fetch_assoc();

if (!$mascota) {
    die("Mascota no encontrada o no tienes permisos");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Adopción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .pet-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Registrar Adopción</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif (isset($mensaje)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <div class="pet-info">
                <h4>Mascota: <?= htmlspecialchars($mascota['nombre_mascota']) ?></h4>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="mascota_id" value="<?= $mascota_id ?>">
                <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">

                <div class="mb-3">
                    <label for="nombre_adoptante" class="form-label">Nombre del adoptante</label>
                    <input type="text" class="form-control" id="nombre_adoptante" name="nombre_adoptante"
                        value="<?= isset($_POST['nombre_adoptante']) ? htmlspecialchars($_POST['nombre_adoptante']) : '' ?>" required>
                </div>

                <div class="mb-3">
                    <label for="telefono_adoptante" class="form-label">Teléfono del adoptante</label>
                    <input type="tel" class="form-control" id="telefono_adoptante" name="telefono_adoptante"
                        value="<?= isset($_POST['telefono_adoptante']) ? htmlspecialchars($_POST['telefono_adoptante']) : '' ?>"
                        required pattern="[0-9]{10}" title="10 dígitos sin espacios">
                </div>

                <div class="mb-3">
                    <label for="fecha_adopcion" class="form-label">Fecha de adopción</label>
                    <input type="date" class="form-control" id="fecha_adopcion" name="fecha_adopcion"
                        value="<?= isset($_POST['fecha_adopcion']) ? htmlspecialchars($_POST['fecha_adopcion']) : date('Y-m-d') ?>"
                        required max="<?= date('Y-m-d') ?>">
                </div>

                <div class="mb-3">
                    <label for="evidencia" class="form-label">Evidencia de adopción</label>
                    <input type="file" class="form-control" id="evidencia" name="evidencia" required accept="image/*,.pdf">
                    <small class="text-muted">Sube una imagen o PDF del comprobante (máx 2MB)</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registrar Adopción</button>
                    <a href="MisPublicaciones.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conexion->close(); ?>