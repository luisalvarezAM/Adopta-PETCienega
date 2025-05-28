<?php
// Conexión a la base de datos
require_once '../assets/conexionBD.php';
$conexion = obtenerConexion();

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT m.id_mascota, m.nombre_mascota, m.descripcion, m.raza, m.edad, 
               m.sexo, m.imagen, m.fecha_registro,mu.nombre_municipio,
               u.nombre_completo, u.telefono, u.correo 
        FROM mascotas m 
        INNER JOIN usuarios u ON m.usuario_id = u.id_usuario
        INNER JOIN municipios mu ON m.municipio = mu.id_municipio
        INNER JOIN estatus_adopcion e ON m.estatus_id = e.id_estatus
        WHERE m.estatus_id = 1";
$result = $conexion->query($sql);

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas en Adopción | Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .pet-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            height: 100%;
        }

        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .pet-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .pet-header {
            background-color: #4e73df;
            color: white;
            padding: 10px 15px;
            border-radius: 10px 10px 0 0 !important;
        }

        .pet-body {
            padding: 15px;
        }

        .pet-name {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .pet-type {
            display: inline-block;
            background-color: #e8f4fc;
            color: #3498db;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .pet-info {
            margin-bottom: 10px;
        }

        .pet-info-label {
            font-weight: 600;
            color: #7f8c8d;
        }

        .filter-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .no-pets {
            text-align: center;
            padding: 50px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .badge-status {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-available {
            background-color: #2ecc71;
        }

        .badge-adopted {
            background-color: #e74c3c;
        }

        .pet-description {
            margin: 10px 0;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <h3>Administrador<span>AdoptaPETCienega</span></h3>
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
                <li class="active">
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
                        </div>
                        <img src="../assets/adoptapetcienega.png" alt="User" class="user-avatar rounded-circle">
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2>Mascotas en Adopción</h2>
                                <p class="text-muted">Listado de mascotas disponibles para adopción</p>
                            </div>
                        </div>
                    </div>

                    <!-- Listado de Mascotas -->
                    <div class="row">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($mascota = $result->fetch_assoc()): ?>
                                <div class="col-md-4">
                                    <div class="card pet-card">
                                        <div class="pet-header d-flex justify-content-between align-items-center">
                                            <span class="text-white">
                                                <i class="fas fa-calendar-alt"></i> <?= date('d M Y', strtotime($mascota['fecha_registro'])) ?>
                                            </span>
                                        </div>
                                        <img src="<?= htmlspecialchars($mascota['imagen'] ?? '../assets/img/mascotas/default.jpg') ?>"
                                            alt="<?= htmlspecialchars($mascota['nombre_mascota']) ?>"
                                            class="pet-img">
                                        <div class="pet-body">
                                            <h3 class="pet-name"><?= htmlspecialchars($mascota['nombre_mascota']) ?></h3>

                                            <div class="pet-info">
                                                <span class="pet-info-label">Raza:</span>
                                                <?= htmlspecialchars($mascota['raza']) ?>
                                            </div>

                                            <div class="pet-info">
                                                <span class="pet-info-label">Edad:</span>
                                                <?= htmlspecialchars($mascota['edad']) ?> 
                                            </div>

                                            <div class="pet-info">
                                                <span class="pet-info-label">Sexo:</span>
                                                <?= htmlspecialchars($mascota['sexo']) ?>
                                            </div>

                                            <div class="pet-description">
                                                <?= htmlspecialchars($mascota['descripcion']) ?>
                                            </div>

                                            <div class="pet-info">
                                                <span class="pet-info-label">Dueño:</span>
                                                <?= htmlspecialchars($mascota['nombre_completo']) ?>
                                            </div>

                                            <div class="pet-info">
                                                <span class="pet-info-label">Ubicación:</span>
                                                <?= htmlspecialchars($mascota['nombre_municipio']) ?>
                                            </div>
                                            <div class="pet-info">
                                                <span class="pet-info-label">Telefono:</span>
                                                <?= htmlspecialchars($mascota['telefono']) ?>
                                            </div>

                                            <div class="pet-info">
                                                <span class="pet-info-label">Correo:</span>
                                                <?= htmlspecialchars($mascota['correo']) ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="no-pets">
                                    <i class="fas fa-paw fa-3x mb-3" style="color: #ddd;"></i>
                                    <h4>No hay mascotas disponibles para adopción</h4>
                                    <p class="text-muted">Actualmente no hay mascotas registradas en el sistema.</p>
                                </div>
                            </div>
                        <?php endif; ?>
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