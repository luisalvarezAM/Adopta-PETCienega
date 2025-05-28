<?php
require_once '../assets/conexionBD.php';
$conexion = obtenerConexion();

$sql = "SELECT a.id_adopcion, m.id_mascota, m.nombre_mascota, m.descripcion, m.raza, m.edad, 
               m.sexo, m.imagen, m.fecha_registro, a.fecha_adopcion,
               mu.nombre_municipio, 
               a.nombre_adoptante, a.numero_telefonico, a.correo, a.imagen_evidencia
        FROM adopciones a
        INNER JOIN mascotas m ON a.mascota_id = m.id_mascota
        INNER JOIN municipios mu ON m.municipio = mu.id_municipio
        WHERE m.estatus_id = 3";

$result = $conexion->query($sql);
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mascotas Adoptadas</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .pet-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
        }

        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .pet-img {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }

        .evidence-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .pet-body {
            padding: 20px;
        }

        .pet-name {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .badge-adopted {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .section-title {
            text-align: center;
            margin: 40px 0 20px;
            color: #34495e;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <h3>Administrador<span> Adopta PETCienega</span></h3>
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
        <div class="container">
            <h2 class="section-title">Mascotas Adoptadas</h2>
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($mascota = $result->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="pet-card">
                                <img src="<?= htmlspecialchars($mascota['imagen']) ?>" class="pet-img" alt="Foto de <?= htmlspecialchars($mascota['nombre_mascota']) ?>">
                                <div class="pet-body">
                                    <span class="badge badge-adopted">Adoptado el <?= date('d/m/Y', strtotime($mascota['fecha_adopcion'])) ?></span>
                                    <h3 class="pet-name mt-2"><?= htmlspecialchars($mascota['nombre_mascota']) ?></h3>

                                    <p><span class="info-label">Raza:</span> <?= htmlspecialchars($mascota['raza']) ?></p>
                                    <p><span class="info-label">Edad:</span> <?= htmlspecialchars($mascota['edad']) ?></p>
                                    <p><span class="info-label">Sexo:</span> <?= htmlspecialchars($mascota['sexo']) ?></p>
                                    <p><span class="info-label">Municipio:</span> <?= htmlspecialchars($mascota['nombre_municipio']) ?></p>
                                    <p class="mb-2"><?= htmlspecialchars($mascota['descripcion']) ?></p>

                                    <hr>
                                    <h6 class="text-muted">Datos del Adoptante</h6>
                                    <p><span class="info-label">Nombre:</span> <?= htmlspecialchars($mascota['nombre_adoptante']) ?></p>
                                    <p><span class="info-label">Teléfono:</span> <?= htmlspecialchars($mascota['numero_telefonico']) ?></p>
                                    <p><span class="info-label">Correo:</span> <?= htmlspecialchars($mascota['correo']) ?></p>

                                    <?php if (!empty($mascota['imagen_evidencia'])): ?>
                                        <hr>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No hay mascotas adoptadas registradas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</body>

</html>