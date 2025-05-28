<?php

require_once '../assets/conexionBD.php';
$conexion = obtenerConexion();

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;


$sql = "SELECT id_usuario, username, nombre_completo, correo, telefono, fec_registro, img_perfil, direccion, m.nombre_municipio
        FROM usuarios INNER JOIN municipios as m ON usuarios.municipio=m.id_municipio
        WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();


$sql_mascotas = "SELECT COUNT(*) as total_mascotas, GROUP_CONCAT(CONCAT(nombre_mascota, ' (', tipo_mascota, ')')) as mascotas_info 
                 FROM mascotas 
                 WHERE usuario_id = ?";
$stmt_mascotas = $conexion->prepare($sql_mascotas);
$stmt_mascotas->bind_param("i", $id_usuario);
$stmt_mascotas->execute();
$result_mascotas = $stmt_mascotas->get_result();
$mascotas_data = $result_mascotas->fetch_assoc();

$sql_adopciones = "SELECT COUNT(*) as total_adopciones 
                   FROM adopciones 
                   WHERE usuario_id = ?";
$stmt_adopciones = $conexion->prepare($sql_adopciones);
$stmt_adopciones->bind_param("i", $id_usuario);
$stmt_adopciones->execute();
$result_adopciones = $stmt_adopciones->get_result();
$adopciones_data = $result_adopciones->fetch_assoc();

$conexion->close();

if (!$usuario) {
    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario | Administrador</title>
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

        .mascota-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .mascota-item:last-child {
            border-bottom: none;
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
                                <h2>Perfil de Usuario</h2>
                                <p class="text-muted">Detalles completos del usuario</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="administradores.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <a href="editarPerfilAdmin.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Editar Perfil
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Perfil del Usuario -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-header text-center">
                                <img src="<?= htmlspecialchars($usuario['img_perfil'] ?? '../assets/img/usuarios/default.png') ?>"
                                    alt="Foto de perfil"
                                    class="profile-img rounded-circle mb-3">
                                <h3><?= htmlspecialchars($usuario['nombre_completo']) ?></h3>
                                <p class="text-muted">@<?= htmlspecialchars($usuario['username']) ?></p>

                                <div class="d-flex justify-content-center mb-3">
                                    <span class="badge bg-primary me-2">Usuario</span>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="mailto:<?= htmlspecialchars($usuario['correo']) ?>?subject=Contacto%20desde%20AdoptaPET%20Cienega&body=Hola%20<?= urlencode($usuario['nombre_completo']) ?>%2C%0A%0A"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-envelope"></i> Enviar mensaje
                                    </a>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Estadísticas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-item">
                                        <small class="text-muted">Mascotas publicadas</small>
                                        <h6 class="mb-0"><?= $mascotas_data['total_mascotas'] ?? 0 ?></h6>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Adopciones realizadas</small>
                                        <h6 class="mb-0"><?= $adopciones_data['total_adopciones'] ?? 0 ?></h6>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Miembro desde</small>
                                        <h6 class="mb-0"><?= date('d M Y', strtotime($usuario['fec_registro'])) ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 profile-details">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Información Personal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <small class="text-muted">Nombre de usuario</small>
                                                <h6 class="mb-0"><?= htmlspecialchars($usuario['username']) ?></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <small class="text-muted">Nombre completo</small>
                                                <h6 class="mb-0"><?= htmlspecialchars($usuario['nombre_completo']) ?></h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <small class="text-muted">Correo electrónico</small>
                                                <h6 class="mb-0"><?= htmlspecialchars($usuario['correo']) ?></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <small class="text-muted">Teléfono</small>
                                                <h6 class="mb-0"><?= htmlspecialchars($usuario['telefono'] ?? 'No especificado') ?></h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <small class="text-muted">Dirección</small>
                                        <h6 class="mb-0">
                                            <?= htmlspecialchars($usuario['direccion'] ?? 'No especificada') ?>,
                                            <?= htmlspecialchars($usuario['nombre_municipio'] ?? 'No especificado') ?>
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Mascotas</h5>
                                </div>
                                <div class="card-body">
                                    <?php if ($mascotas_data['total_mascotas'] > 0): ?>
                                        <h6>Mascotas publicadas por este usuario:</h6>
                                        <ul class="list-unstyled">
                                            <?php
                                            $mascotas = explode(',', $mascotas_data['mascotas_info']);
                                            foreach ($mascotas as $mascota):
                                            ?>
                                                <li class="mascota-item">
                                                    <i class="fas fa-paw me-2"></i><?= htmlspecialchars(trim($mascota)) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Este usuario no ha publicado ninguna mascota.
                                        </div>
                                    <?php endif; ?>
                                </div>
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