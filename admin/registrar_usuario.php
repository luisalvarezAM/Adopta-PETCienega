<?php
session_start();
require('../assets/conexionBD.php');
$conexion = obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $telefono = $_POST['telefono'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_registro = date("Y-m-d H:i:s");
    $tipo_usuario = $_POST['tipo_usuario'];
    $imagen = $_FILES['imagen'];
    $directorio = "../adopta/fotos_perfil/";

    $check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'El nombre de usuario "' . htmlspecialchars($username) . '" ya está registrado.'
        ];
        header('Location: registrar_usuario.php');
        exit();
    }

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombre_imagen = uniqid() . "-" . basename($imagen["name"]);
    $ruta_foto = $directorio . $nombre_imagen;

    if (move_uploaded_file($imagen["tmp_name"], $ruta_foto)) {
        $sql = $conexion->prepare("INSERT INTO usuarios (username, nombre_completo, correo, contraseña, telefono, fec_registro, tipo_usuario, img_perfil) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssssssss", $username, $nombre_completo, $email, $password, $telefono, $fecha_registro, $tipo_usuario, $ruta_foto);

        if ($sql->execute()) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Usuario registrado correctamente.'
            ];
            header('Location: registrar_usuario.php');
            exit();
        } else {
            $_SESSION['notification'] = [
                'type' => 'error',
                'message' => 'Error al registrar: ' . htmlspecialchars($sql->error)
            ];
            header('Location: registrar_usuario.php');
            exit();
        }
        $sql->close();
    } else {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Error al subir la imagen.'
        ];
        header('Location: registrar_usuario.php');
        exit();
    }

    $check->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/notificacion.css">
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <h3>Administrador<span>Pro</span></h3>
                <strong>AP</strong>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="usuarios.php">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="administradores.php">
                        <i class="fas fa-user-cog"></i>
                        <span>Administradores</span>
                    </a>
                </li>
                <li>
                    <a href="mascotas.php">
                        <i class="fas fa-paw"></i>
                        <span>Mascotas</span>
                    </a>
                </li>
                <li>
                    <a href="adopciones.php">
                        <i class="fas fa-calendar-check"></i>
                        <span>Adopciones</span>
                    </a>
                </li>
                <li>
                    <a href="publicaciones.php">
                        <i class="fas fa-chart-bar"></i>
                        <span>Publicaciones</span>
                    </a>
                </li>
                <li>
                    <a href="perfil.php">
                        <i class="fas fa-cog"></i>
                        <span>Editar perfil</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <div class="user-profile ml-auto">
                        <div class="user-info">
                            <span class="user-name">Administrador</span>
                            <span class="user-role">Super Admin</span>
                        </div>
                        <img src="https://via.placeholder.com/40" alt="User" class="user-avatar rounded-circle">
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2>Registrar Nuevo Usuario</h2>
                                <p class="text-muted">Complete el formulario para agregar un nuevo usuario</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="usuarios.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Registro -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Información del Usuario</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nombre_completo" class="form-label">Nombre(s) completo</label>
                                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="username" class="form-label">Usuario</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="password" required name="password">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="confirm-password" class="form-label">Confirmar Contraseña</label>
                                        <input type="password" class="form-control" id="confirm-password" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="tipo_usuario" class="form-label">Rol</label>
                                        <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                                            <option value="" selected disabled>Seleccione un rol</option>
                                            <option value="2">Administrador</option>
                                            <option value="1">Usuario normal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Foto de Perfil</label>
                                        <input type="file" class="form-control" id="imagen" name="imagen">
                                        <small class="text-muted">Formatos aceptados: JPG, PNG (Max. 2MB)</small>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="reset" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-undo"></i> Limpiar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Usuario
                                    </button>
                                </div>
                            </form>
                            <!-- Contenedor para notificaciones -->
                            <div id="notificationContainer">
                                <?php if (isset($_SESSION['notification'])): ?>
                                    <div class="custom-notification show <?= $_SESSION['notification']['type'] ?>">
                                        <div class="custom-notification-header">
                                            <span><?= $_SESSION['notification']['type'] === 'success' ? 'Éxito' : 'Error' ?></span>
                                            <button class="custom-notification-close" onclick="this.parentElement.parentElement.remove()">
                                                &times;
                                            </button>
                                        </div>
                                        <div class="custom-notification-body"><?= $_SESSION['notification']['message'] ?></div>
                                    </div>
                                    <?php unset($_SESSION['notification']); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="../js/admin.js"></script>
</body>

</html>