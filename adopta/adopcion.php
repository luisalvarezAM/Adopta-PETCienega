<?php
session_start();
require('../assets/conexionBD.php');
$conexion = obtenerConexion();

// Obtener ID de la mascota
$mascota_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario_id = $_SESSION['id_usuario'];

// Obtener datos de la mascota y verificar permisos
$sql_mascota = "SELECT nombre_mascota FROM mascotas WHERE id_mascota = $mascota_id AND usuario_id = $usuario_id";
$result = $conexion->query($sql_mascota);
$mascota = $result->fetch_assoc();

if (!$mascota) {
    die("Mascota no encontrada o no tienes permisos");
}

// Obtener lista de interesados para esta mascota
$sql_interesados = "SELECT i.id_interesado, u.nombre_completo, u.correo, u.telefono
                    FROM interesados i 
                    INNER JOIN usuarios u ON i.id_interesado = u.id_usuario
                    WHERE i.id_mascota = $mascota_id";
$result_interesados = $conexion->query($sql_interesados);
$interesados = [];
while ($row = $result_interesados->fetch_assoc()) {
    $interesados[] = $row;
}

// Inicializar variables
$fecha_adopcion = date('Y-m-d');
$direccion = '';
$notas = '';
$evidencia_imagen = '';
$error = '';
$mensaje = '';

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mascota_id'])) {
    $mascota_id = intval($_POST['mascota_id']);
    $interesado_id = isset($_POST['interesado_id']) ? intval($_POST['interesado_id']) : 0;
    $fecha_adopcion = isset($_POST['fecha_adopcion']) ? $conexion->real_escape_string($_POST['fecha_adopcion']) : date('Y-m-d');
    $direccion = isset($_POST['direccion']) ? $conexion->real_escape_string($_POST['direccion']) : '';
    $notas = isset($_POST['notas']) ? $conexion->real_escape_string($_POST['notas']) : '';

    if ($interesado_id <= 0) {
        $error = "Debes seleccionar un interesado";
    } else {
        // Obtener datos del interesado seleccionado
        $sql_datos_interesado = "SELECT nombre_completo, correo, telefono 
                                 FROM usuarios
                                 WHERE id_usuario = $interesado_id";
        $result_interesado = $conexion->query($sql_datos_interesado);

        if ($result_interesado && $result_interesado->num_rows > 0) {
            $interesado = $result_interesado->fetch_assoc();
    
    $nombre_adoptante = $conexion->real_escape_string($interesado['nombre_completo']);
    $correo_adoptante = $conexion->real_escape_string($interesado['correo']);
    $telefono_adoptante = $conexion->real_escape_string($interesado['telefono']);

    // Aquí se sobrescriben dirección y notas con el correo
    $direccion = $correo_adoptante;
    $notas = $correo_adoptante;
$notas = $correo_adoptante;
            // Procesar imagen de evidencia
            if (!empty($_FILES['evidencia']['name']) && $_FILES['evidencia']['error'] == UPLOAD_ERR_OK) {
                $evidencia = $_FILES['evidencia'];
                $directorio = "../assets/img/evidencia_adopciones/";
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

            // Si no hay errores, actualizar base de datos
            if (empty($error)) {
                $sql = "UPDATE mascotas SET 
                        estatus_id = '3'
                        WHERE id_mascota = $mascota_id AND usuario_id = $usuario_id";

                $sql2 = "INSERT INTO adopciones 
                        (mascota_id, usuario_id, nombre_adoptante, correo, 
                         numero_telefonico, fecha_adopcion, imagen_evidencia)
                        VALUES ('$mascota_id', '$usuario_id','$nombre_adoptante', 
                        '$correo_adoptante', '$telefono_adoptante', '$fecha_adopcion', 
                        '$evidencia_imagen')";

                $sql3 = "DELETE FROM interesados WHERE id_mascota = $mascota_id AND id_interesado != $interesado_id";

                $conexion->begin_transaction();
                $success = true;

                if (!$conexion->query($sql)) {
                    $error = "Error al actualizar mascota: " . $conexion->error;
                    $success = false;
                }

                if ($success && !$conexion->query($sql2)) {
                    $error = "Error al registrar adopción: " . $conexion->error;
                    $success = false;
                }

                if ($success && !$conexion->query($sql3)) {
                    $error = "Error al limpiar interesados: " . $conexion->error;
                    $success = false;
                }

                if ($success) {
                    $conexion->commit();
                    $mensaje = "Adopción registrada correctamente";
                    header("refresh:2;url=MisPublicaciones.php");
                } else {
                    $conexion->rollback();
                }
            }
        } else {
            $error = "Interesado no encontrado";
        }
    }
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
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        .pet-info {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 5px solid #0d6efd;
        }

        .interesado-info {
            background-color: #e7f1ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 5px solid #198754;
        }

        .form-section {
            margin-bottom: 25px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            color: #0d6efd;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Registrar Adopción</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif (!empty($mensaje)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <div class="pet-info">
                <h4><i class="bi bi-pet"></i> Mascota: <?= htmlspecialchars($mascota['nombre_mascota']) ?></h4>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="mascota_id" value="<?= $mascota_id ?>">
                <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">

                <div class="form-section">
                    <h4 class="form-title">1. Seleccionar adoptante</h4>

                    <div class="mb-4">
                        <label for="interesado_id" class="form-label fw-bold">Interesado registrado:</label>
                        <select class="form-select form-select-lg" id="interesado_id" name="interesado_id" required>
                            <option value="">-- Seleccione un interesado --</option>
                            <?php foreach ($interesados as $interesado): ?>
                                <option value="<?= $interesado['id_interesado'] ?>"
                                    <?= (isset($_POST['id_interesado']) && $_POST['id_interesado'] == $interesado['id_interesado']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($interesado['nombre_completo']) ?> -
                                    Tel: <?= htmlspecialchars($interesado['telefono']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Seleccione de la lista de personas que mostraron interés</small>
                    </div>

                    <?php if (isset($_POST['interesado_id']) && $_POST['interesado_id'] > 0):
                        $selected_id = $_POST['interesado_id'];
                        $selected_interesado = null;
                        foreach ($interesados as $i) {
                            if ($i['id_interesado'] == $selected_id) {
                                $selected_interesado = $i;
                                break;
                            }
                        }
                        if ($selected_interesado): ?>
                            <div class="interesado-info">
                                <h5><i class="bi bi-person-check"></i> Datos del adoptante seleccionado:</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre completo:</strong><br><?= htmlspecialchars($selected_interesado['nombre_completo']) ?></p>
                                        <p><strong>Teléfono:</strong><br><?= htmlspecialchars($selected_interesado['telefono']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Correo electrónico:</strong><br><?= htmlspecialchars($selected_interesado['correo']) ?></p>
                                    </div>
                                </div>
                            </div>
                    <?php endif;
                    endif; ?>
                </div>

                <div class="form-section">
                    <h4 class="form-title">2. Información de la adopción</h4>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha_adopcion" class="form-label fw-bold">Fecha de adopción</label>
                            <input type="date" class="form-control" id="fecha_adopcion" name="fecha_adopcion"
                                value="<?= htmlspecialchars($fecha_adopcion) ?>"
                                required max="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-bold">Dirección del adoptante</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            value="<?= htmlspecialchars($direccion) ?>"
                            required>
                        <small class="text-muted">Donde vivirá la mascota</small>
                    </div>

                    <div class="mb-3">
                        <label for="notas" class="form-label fw-bold">Notas adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3"><?= htmlspecialchars($notas) ?></textarea>
                        <small class="text-muted">Cualquier información adicional sobre la adopción</small>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-title">3. Documentación</h4>

                    <div class="mb-3">
                        <label for="evidencia" class="form-label fw-bold">Evidencia de adopción</label>
                        <input type="file" class="form-control" id="evidencia" name="evidencia" required accept="image/*,.pdf">
                        <small class="text-muted">Sube una imagen o PDF del comprobante de adopción (máx 2MB). Puede ser contrato, foto de entrega, etc.</small>
                    </div>
                </div>

                <div class="d-grid gap-3 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Registrar Adopción
                    </button>
                    <a href="MisPublicaciones.php" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar datos del interesado seleccionado
        document.getElementById('interesado_id').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>

</html>

<?php $conexion->close(); ?>