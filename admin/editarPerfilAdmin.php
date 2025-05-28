<?php
session_start();
require_once '../assets/conexionBD.php';
$conexion = obtenerConexion();

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT u.id_usuario, u.username, u.nombre_completo, u.correo, u.telefono, 
               u.fec_registro, u.img_perfil, u.direccion, u.municipio, m.nombre_municipio
        FROM usuarios u
        LEFT JOIN municipios m ON u.municipio = m.id_municipio
        WHERE u.id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: administradores.php");
    exit();
}

$sql_municipios = "SELECT id_municipio, nombre_municipio FROM municipios ORDER BY nombre_municipio";
$result_municipios = $conexion->query($sql_municipios);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $conexion->real_escape_string($_POST['nombre_completo']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    $municipio = intval($_POST['municipio']);
    $ruta_foto = $usuario['img_perfil'] ?? '../assets/usuarioVacio.jpg';

    $uploadDir = '../assets/img/fotos_perfil/';
    define('IMAGEN_PREDETERMINADA', '../assets/usuarioVacio.jpg');

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        $ext = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowedExtensions)) {
            if ($imagen['size'] > 2097152) {
                $_SESSION['notificacion'] = [
                    'type' => 'error',
                    'message' => 'La imagen es demasiado grande. Máximo 2MB permitidos'
                ];
                header("Location: editarPerfilAdmin.php?id=" . $id_usuario);
                exit();
            }

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $username = preg_replace('/[^a-z0-9_]/i', '', $usuario['username']);
            $filename = $username . '.' . $ext;
            $uploadFile = $uploadDir . $filename;

            if (file_exists($uploadFile)) {
                unlink($uploadFile);
            }

            if (move_uploaded_file($imagen['tmp_name'], $uploadFile)) {
                if (
                    !empty($usuario['img_perfil']) &&
                    file_exists($usuario['img_perfil']) &&
                    $usuario['img_perfil'] !== IMAGEN_PREDETERMINADA &&
                    $usuario['img_perfil'] !== $uploadFile
                ) {
                    unlink($usuario['img_perfil']);
                }
                $ruta_foto = $uploadFile;
            } else {
                $_SESSION['notification'] = [
                    'type' => 'error',
                    'message' => 'Error al subir la imagen'
                ];
                header("Location: editarPerfilAdmin.php?id=" . $id_usuario);
                exit();
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'error',
                'message' => 'Formato de imagen no válido. Solo se permiten JPG, PNG o JPEG'
            ];
            header("Location: editarPerfilAdmin.php?id=" . $id_usuario);
            exit();
        }
    }

    $sql = "UPDATE usuarios SET 
            nombre_completo = ?,
            telefono = ?,
            direccion = ?,
            municipio = ?,
            img_perfil = ?
            WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssisi", $nombre_completo, $telefono, $direccion, $municipio, $ruta_foto, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Perfil actualizado correctamente'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Error al actualizar el perfil: ' . $conexion->error
        ];
        header("Location: administradores.php"); 
        exit();
    }
    header("Location: administradores.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil de Usuario | Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .profile-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-details .card {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .profile-details .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .info-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .img-preview {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
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

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
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
                                <h2>Editar Perfil de Administrador</h2>
                                <p class="text-muted">Modifica los datos del administrador</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="administradores.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Mensajes de éxito/error -->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['mensaje'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <!-- Mensajes de éxito/error -->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['mensaje'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <!-- Formulario de edición -->
                    <div class="row">
                        <div class="col-md-12">
                            <form action="editarPerfilAdmin.php?id=<?= $id_usuario ?>" method="POST" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Información Personal</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-3 text-center">
                                                <img src="<?= htmlspecialchars($usuario['img_perfil'] ?? '../assets/img/usuarios/default.png') ?>"
                                                    alt="Foto de perfil"
                                                    class="profile-img rounded-circle mb-3"
                                                    id="current-img">
                                                <div class="mb-3">
                                                    <label for="imagen" class="form-label">Cambiar imagen</label>
                                                    <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
                                                    <img id="img-preview" class="img-preview rounded-circle">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="username" class="form-label">Nombre de usuario</label>
                                                        <input type="text" class="form-control" id="username"
                                                            value="<?= htmlspecialchars($usuario['username']) ?>" disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="correo" class="form-label">Correo electrónico</label>
                                                        <input type="email" class="form-control" id="correo"
                                                            value="<?= htmlspecialchars($usuario['correo']) ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="nombre_completo" class="form-label">Nombre completo</label>
                                                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo"
                                                            value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="telefono" class="form-label">Teléfono</label>
                                                        <input type="tel" class="form-control" id="telefono" name="telefono"
                                                            value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="municipio" class="form-label">Municipio</label>
                                                        <select class="form-select" id="municipio" name="municipio" required>
                                                            <option value="">Seleccione un municipio</option>
                                                            <?php while ($mun = $result_municipios->fetch_assoc()): ?>
                                                                <option value="<?= $mun['id_municipio'] ?>"
                                                                    <?= ($mun['id_municipio'] == $usuario['municipio']) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($mun['nombre_municipio']) ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="direccion" class="form-label">Dirección</label>
                                                        <input type="text" class="form-control" id="direccion" name="direccion"
                                                            value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="fec_registro" class="form-label">Fecha de registro</label>
                                                    <input type="text" class="form-control" id="fec_registro"
                                                        value="<?= date('d/m/Y', strtotime($usuario['fec_registro'])) ?>" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-header">
        
    </div>

   
    <?php if (isset($_SESSION['notificacion'])): ?>
        <div class="alert alert-<?= $_SESSION['notificacion']['tipo'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['notificacion']['mensaje'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notificacion']); ?>
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script src="../js/admin.js"></script>

    <script>
        
        document.getElementById('imagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('img-preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';

                    // Ocultar la imagen actual
                    document.getElementById('current-img').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>